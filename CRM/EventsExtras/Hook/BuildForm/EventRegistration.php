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
  *
  * @param \CRM_Event_Form_ManageEvent_Registration $form
  */
  public function buildForm(CRM_Event_Form_ManageEvent_Registration &$form) {
    $this->hideField($form);
  }

}