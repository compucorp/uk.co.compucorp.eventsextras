<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for Event Online Registartion BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventRegistration extends CRM_EventsExtras_Hook_BuildForm_BaseEvent {

  /**
  *
  * @param string $eventTab
  */
  public function __construct() {
    parent::__construct(SettingsManager::EVENT_REGISTRATION);
  }

  /**
   * Hides options on the Event Registration page
   *
   * @param string $formName
   * @param CRM_Event_Form_ManageEvent_Registration $form
   */
  public function handle($formName, &$form) {
    if (!$this->shouldHandle($formName, CRM_Event_Form_ManageEvent_Registration::class)) {
      return;
    }
    $this->hideField($form);
  }

}