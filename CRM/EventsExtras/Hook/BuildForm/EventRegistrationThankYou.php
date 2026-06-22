<?php

/**
 * BuildForm hook listener for the public Event Registration wizard.
 */
class CRM_EventsExtras_Hook_BuildForm_EventRegistrationThankYou {

  /**
   * Hook entry point.
   */
  public function handle(string $formName, CRM_Core_Form &$form): void {
    if ($formName !== CRM_Event_Form_Registration_Register::class) {
      return;
    }

    $this->storeEventIdForFlow($form);
  }

  /**
   * Extract the event id and qfKey from the form and hand them to the service for caching.
   */
  private function storeEventIdForFlow(CRM_Event_Form_Registration_Register &$form): void {
    $eventId = $this->resolveEventId($form);
    if (!$eventId) {
      return;
    }

    $qfKey = $this->extractQfKey($form);
    if (!$qfKey) {
      return;
    }

    CRM_EventsExtras_Service_ThankYouPageRedirect::storeEventId($qfKey, $eventId);
  }

  /**
   * Resolve the event id the form is attached to.
   */
  private function resolveEventId(CRM_Event_Form_Registration_Register $form): ?int {
    try {
      if (method_exists($form, 'getEventID')) {
        $eventId = (int) $form->getEventID();
        if ($eventId > 0) {
          return $eventId;
        }
      }
    }
    catch (Throwable $e) {
      // getEventID() may send its own "Missing Event ID" response in edge
      // cases - swallow so this hook can never be the cause of a broken form.
    }

    if (!empty($form->_eventId) && is_numeric($form->_eventId)) {
      return (int) $form->_eventId;
    }

    return NULL;
  }

  /**
   * Prefer the controller's qfKey; fall back to the request parameters
   */
  private function extractQfKey(CRM_Event_Form_Registration_Register $form): ?string {
    $key = NULL;
    if (isset($form->controller) && is_object($form->controller) && !empty($form->controller->_key)) {
      $key = $form->controller->_key;
    }

    if (!$key) {
      $key = $_POST['qfKey'] ?? $_GET['qfKey'] ?? $_REQUEST['qfKey'] ?? NULL;
    }

    return (is_string($key) && $key !== '') ? $key : NULL;
  }

}
