<?php
/**
 * Class for Event Fee BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventFee extends CRM_EventsExtras_Hook_BuildForm  {
  
  /**
  *
  * @param string $$eventTab
  */
  public function __construct($eventTab) {
    parent::__construct($eventTab);
  }

  /**
  *
  * @param \CRM_Event_Form_ManageEvent_Fee $form
  */
  public function buildForm(CRM_Event_Form_ManageEvent_Fee &$form) {
    $this->hideField($form);
  }

}