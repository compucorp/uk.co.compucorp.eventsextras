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
   * in Font Page configuration section
   *
   * @var string[]
   */
  private $frontpageConfig = [];

  /**
   * Contains array of names, which must be displayed
   * in fee configuration section
   *
   * @var string[]
   */
  private $feeConfig = [];

  /**
   * Contains array of names, which must be displayed
   * in online registration configuration section
   *
   * @var string[]
   */
  private $onlineRegistrationConfig = []; 

  public function buildQuickForm() {
    CRM_Utils_System::setTitle(E::ts('CiviEvent Extras Settings'));
    $configFields = SettingsManager::getConfigFields();
    foreach ($configFields as $name => $config) {
      $this->addRadio(
        $name, 
        E::ts($config['title']),
          [1 => E::ts('Show'), 0 => E::ts('Hide')]
      );
      $this->assignConfigSections($name, $config['section']);
    }
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ],
    ]);

    $this->assign('frontpageConfigSection', $this->frontpageConfig);
    $this->assign('feeConfigSection', $this->feeConfig);
    $this->assign('onlineRegistrationSection', $this->onlineRegistrationConfig);;
  }

  public function postProcess() {
    $configFields = SettingsManager::getConfigFields();
    $submittedValues = $this->exportValues();
    $valuesToSave = array_intersect_key($submittedValues, $configFields);
    $result = civicrm_api3('setting', 'create', $valuesToSave);
    $session = CRM_Core_Session::singleton();
    if ($result['is_error']== 0){
      $session->setStatus(E::ts('Settings have been saved'),ts('Events Extra Settings'), 'success');
    } else{
      $session->setStatus(E::ts('Settings could not be saved, please contact Administrator'),ts('Events Extra Settings'), 'error');
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
      case 'front_page':
        $this->frontpageConfig[] = $name;
        break;

      case 'fee':
        $this->feeConfig[] = $name;
        break;

      case 'online_registration':
        $this->onlineRegistrationConfig[] = $name;
        break;
    }
  }
}
