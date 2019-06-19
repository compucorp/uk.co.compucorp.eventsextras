<?php
/**
 * Class for EventInfo Form BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventInfo extends CRM_EventsExtras_Hook_BuildForm {

 /**
  *
  * @param string $$eventTab
  */
  public function __construct($eventTab) {
    parent::__construct($eventTab);
  }

  /**
  *
  * @param \CRM_Event_Form_ManageEvent_EventInfo $form
  */
  public function buildForm(CRM_Event_Form_ManageEvent_EventInfo &$form) {
    $this->hideField($form);
  }

}