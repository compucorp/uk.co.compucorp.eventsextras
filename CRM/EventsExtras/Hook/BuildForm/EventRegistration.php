<?php
/**
 * Class for Event Online Registartion BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventRegistration extends CRM_EventsExtras_Hook_BuildForm {

  /**
  *
  * @param string $$eventTab
  */
  public function __construct($eventTab) {
    parent::__construct($eventTab);
  }

  /**
  *
  * @param \CRM_Event_Form_ManageEvent_Registration $form
  */
  public function buildForm(CRM_Event_Form_ManageEvent_Registration &$form) {
    $this->hideField($form);
  }

}