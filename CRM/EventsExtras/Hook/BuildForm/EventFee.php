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
   * Hides options on the Event Fee page
   *
   * @param string $formName
   * @param CRM_Event_Form_ManageEvent_Fee $form
   */
  public function handle($formName, &$form) {
    if (!$this->shouldHandle($formName, CRM_Event_Form_ManageEvent_Fee::class)) {
      return;
    }
    $this->hideField($form);
  }

}
