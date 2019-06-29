<?php

use CRM_EventsExtras_ExtensionUtil as E;
use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_EventsExtras_Form_Settings extends CRM_Core_Form {

  /**
   * Contains array of names, which must be displayed
   * in configuration section
   *
   * @var array[]
   */
  private $displaySections = [
    SettingsManager::EVENT_INFO => ['title' => 'Event Info Settings'],
    SettingsManager::EVENT_FEE => ['title' => 'Fee Settings'],
    SettingsManager::EVENT_REGISTRATION => ['title' => 'Online Registration Settings'],
  ];

  private $parentSettings = [];
  private $defaultSettingsDescription = [];
  private $defaultSettingsHelp = [];

  public function buildQuickForm() {
    CRM_Utils_System::setTitle(E::ts('CiviEvent Extras Settings'));
    $configFields = SettingsManager::getConfigFields();
    foreach ($configFields as $name => $config) {
      if(!array_key_exists('parent_setting', $config['extra_attributes'])){
        $this->addRadio(
          $name,
          E::ts($config['title']),
          [1 => E::ts('Show'), 0 => E::ts('Hide')]
        );
        $this->parentSettings[$config['name']] = TRUE;
      } else { //handle default setting form
        if ($config['html_type'] == 'select') {
          $this->generateDefaultSelectList($config);
        }else {
          $this->generateDefaultSettingField($config);
        }
        $this->defaultSettingsDescription[$config['name']] = $config['description'];
        $this->defaultSettingsHelp[$config['name']] = $config['is_help'];
      }
      $this->assignConfigSections($name, $config['extra_attributes']['section']);
    }
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => E::ts('Cancel'),
      ],
    ]);
    $this->assign('settingsHelp', $this->defaultSettingsHelp);
    $this->assign('settingsDescription', $this->defaultSettingsDescription);
    $this->assign('parentSettings', $this->parentSettings);
    $this->assign('displaySections', $this->displaySections);
    $this->assign('eventRegistrationSection', SettingsManager::EVENT_REGISTRATION);
  }

  public function addRules() {
    $this->addFormRule(['CRM_EventsExtras_Form_Settings', 'validateRules']);
  }


  public static function validateRules($values) {
    $errors = [];

    $roles = SettingsManager::SETTING_FIELDS['ROLES'];
    $rolesDefault = SettingsManager::SETTING_FIELDS['ROLES_DEFAULT'];
    $paymentProcessor = SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION'];
    $paymentProcessorDefault =SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT'];
    $payLaterOption = SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION'];
    $paylaterOptionDefault= SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT'];
    $payLaterLabel = SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT_LABEL'];
    $payLaterInsuection = SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT_LABEL_INSTRUCTION'];

    if ($values[$roles] == 0 && ($values[$rolesDefault]) == NULL) {
      $errors[$rolesDefault] = E::ts('Default Role is a required field');
    }

    if ($values[$paymentProcessor] == 0 && !isset($values[$paymentProcessorDefault])){
      $errors[$paymentProcessorDefault] = E::ts('Please select at least one payment processor and/or enable the pay later option.');
    }

    if ($values[$payLaterOption] == 0 && isset($values[$paylaterOptionDefault]) && $values[$paylaterOptionDefault] == 1){
      if ($values[$payLaterLabel] == NULL){
        $errors[$payLaterLabel] = E::ts(' Please enter the Pay Later prompt to be displayed on the Registration form.');
      }
      if ($values[$payLaterInsuection] == NULL){
        $errors[$payLaterInsuection] = E::ts('Please enter the Pay Later instructions to be displayed to your users.');
      }
    }

    return empty($errors) ? TRUE : $errors;
  }

  public function postProcess() {
    $configFields = SettingsManager::getConfigFields();
    $submittedValues = $this->exportValues();
    if (isset($submittedValues[SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT']])) {
      $paymentProcessors = $submittedValues[SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT']];
      //flip selected default paymentProcessors before saving to setting
      $flipedPaymentProcessor = [];
      foreach ($paymentProcessors as $selectValue => $selected){
        $flipedPaymentProcessor[] = $selectValue;
      }
      $submittedValues[SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT']] = $flipedPaymentProcessor;
    }
    $valuesToSave = array_intersect_key($submittedValues, $configFields);
    $result = civicrm_api3('setting', 'create', $valuesToSave);
    $session = CRM_Core_Session::singleton();
    if ($result['is_error']== 0){
      $session->setStatus(E::ts('Settings have been saved'),E::ts('Events Extra Settings'), 'success');
    } else{
      $session->setStatus(E::ts('Settings could not be saved, please contact Administrator'),E::ts('Events Extra Settings'), 'error');
    }
  }

  private function generatePaymentProcessorElement($setting){
    $paymentProcessor = CRM_Core_PseudoConstant::paymentProcessor();
    $this->addCheckBox(
      $setting['name'],
      E::ts($setting['title']),
      array_flip($paymentProcessor),
      NULL, NULL, NULL, NULL,
      array('&nbsp;&nbsp;', '&nbsp;&nbsp;', '&nbsp;&nbsp;', '<br/>')
    );
  }

   /**
   * Generate a default Setting field to the form.
   *
   * @param array $field
   */
  private function generateDefaultSettingField($setting) {
    if ($setting['name'] ==  SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT']) {
      $this->generatePaymentProcessorElement($setting);
    } else {
      $this->add(
        $setting['html_type'],
        $setting['name'],
        E::ts($setting['title'])
      );
    }
  }

   /**
   * Generate select options from settings.
   *
   * @param array $setting
   */
  private function generateDefaultSelectList($setting) {
    $options = [];
    if (!empty($setting['pseudoconstant']['optionGroupName'])){
      $values = civicrm_api3('OptionGroup', 'get', [
        'sequential' => 1,
        'name' =>  $setting['pseudoconstant']['optionGroupName'],
        'api.OptionValue.get' => ['
          option_group_id' => "id",
          'options' => [
            'limit' => 0,
          ],
          'return' => ['value', 'label']
        ]
      ])['values'][0]['api.OptionValue.get']['values'];
      foreach ($values as $value){
        $options[$value['value']] = $value['label'];
      }
    } else {  // for default maxinum participant
      foreach (range(1, 9) as $value){
        $options[$value] = $value;
      }
    }
    $this->add(
      $setting['html_type'],
      $setting['name'],
      E::ts($setting['title']),
      $options,
      FALSE,
      $setting['extra_attributes']
    );
  }

  /**
   * Set defaults for form.
   *
   * @see CRM_Core_Form::setDefaultValues()
   */
  public function setDefaultValues() {
    $defaults = [];
    $currentValues = civicrm_api3('setting', 'get',
      ['return' => array_keys(SettingsManager::getConfigFields())]);
    $domainID = CRM_Core_Config::domainID();
    foreach ($currentValues['values'][$domainID] as $name => $value) {
      $defaults[$name] = $value;
    }
    return $defaults;
  }

  /**
   * Assign fields between UI sections
   *
   * @param string $name
   * @param string $section
   */
  private function assignConfigSections($name, $section) {
    switch ($section) {
      case SettingsManager::EVENT_INFO:
        $this->displaySections[SettingsManager::EVENT_INFO]['fields'][$name] = $name;
        break;

      case SettingsManager::EVENT_FEE:
        $this->displaySections[SettingsManager::EVENT_FEE]['fields'][] = $name;
        break;

      case SettingsManager::EVENT_REGISTRATION:
        $this->displaySections[SettingsManager::EVENT_REGISTRATION]['fields'][] = $name;
        break;
    }
  }
}
