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
    SettingsManager::EVENT_INFO => ['name' => 'Event Info Settings'],
    SettingsManager::EVENT_FEE => ['name' => 'Fee Settings'],
    SettingsManager::EVENT_REGISTRATION => ['name' => 'Online Registration Settings'],
  ];

  public function buildQuickForm() {
    CRM_Utils_System::setTitle(E::ts('CiviEvent Extras Settings'));
    $configFields = SettingsManager::getConfigFields();
    foreach ($configFields as $name => $config) {
      $this->addRadio(
        $name, 
        E::ts($config['title']),
          [1 => E::ts('Show'), 0 => E::ts('Hide')]
      );
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
    $this->assign('displaySections', $this->displaySections);
  }

  public function postProcess() {
    $configFields = SettingsManager::getConfigFields();
    $submittedValues = $this->exportValues();
    $valuesToSave = array_intersect_key($submittedValues, $configFields);
    $result = civicrm_api3('setting', 'create', $valuesToSave);
    $session = CRM_Core_Session::singleton();
    if ($result['is_error']== 0){
      $session->setStatus(E::ts('Settings have been saved'),E::ts('Events Extra Settings'), 'success');
    } else{
      $session->setStatus(E::ts('Settings could not be saved, please contact Administrator'),E::ts('Events Extra Settings'), 'error');
    }
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
    $defaults = [];
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
        $this->displaySections[SettingsManager::EVENT_INFO]['fields'][] = $name;
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
