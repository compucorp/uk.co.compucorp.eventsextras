<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for Event Fee BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventFee extends CRM_EventsExtras_Hook_BuildForm_BaseEvent {
  
  /**
  *
  * @param string $eventTab
  */
  public function __construct() {
    parent::__construct(SettingsManager::EVENT_FEE);
  }

  /**
  *
  * @param \CRM_Event_Form_ManageEvent_Fee $form
  */
  public function buildForm(CRM_Event_Form_ManageEvent_Fee &$form) {
    $this->hideField($form);
  }

}