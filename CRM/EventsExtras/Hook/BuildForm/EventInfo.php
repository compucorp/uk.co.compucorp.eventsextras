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
  *
  * @param \CRM_Event_Form_ManageEvent_EventInfo $form
  */
  public function buildForm(CRM_Event_Form_ManageEvent_EventInfo &$form) {
    $this->hideField($form);
  }

}