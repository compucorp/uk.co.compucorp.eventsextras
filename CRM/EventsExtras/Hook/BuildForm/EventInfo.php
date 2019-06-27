<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for EventInfo Form BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventInfo extends CRM_EventsExtras_Hook_BuildForm_BaseEvent {

 /**
  *
  * @param string $eventTab
  */
  public function __construct() {
    parent::__construct(SettingsManager::EVENT_INFO);
  }

   /**
   * Hides options on the Event Info page
   *
   * @param string $formName
   * @param CRM_Event_Form_ManageEvent_EventInfo $form
   */
  public function handle($formName, &$form) {
    if (!$this->shouldHandle($formName, CRM_Event_Form_ManageEvent_EventInfo::class)) {
      return;
    }
    $this->hideField($form);
    $this->buildForm($formName, $form);
  }

  private function buildForm($formName, &$form) {
   $this->setDefaults($form);
  }

  private function setDefaults(&$form){
    $defaults = [];
    $role = SettingsManager::SETTING_FIELDS['ROLES'];
    $roleDefault = SettingsManager::SETTING_FIELDS['ROLES_DEFAULT'];
    $settings = [$role, $roleDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$role] == 0){
      $defaults['payment_processor'] = $settingValues[$roleDefault];
    }
    $form->setDefaults($defaults);
  }

}

