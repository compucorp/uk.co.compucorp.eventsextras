<?php

use CRM_EventsExtras_ExtensionUtil as E;
use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Abstract class for BuildForm Hook
 */
abstract class CRM_EventsExtras_Hook_BuildForm_BaseEvent {

  /**
   * Event tab to display on the form
   * @var eventTab
   */
  protected $eventTab;

  /**
   * Event tab and class map
   * @var eventTabAndClassMap
   */
  protected $eventTabAndClassMap = [
    SettingsManager::EVENT_INFO => 'crm-event-manage-eventinfo',
    SettingsManager::EVENT_FEE => 'crm-event-manage-fee',
    SettingsManager::EVENT_REGISTRATION => 'crm-event-manage-registration',
  ];

  /**
   * Constractor for BuildForm class
   *
   * @param string $eventTab
   *
   */
  protected function __construct($eventTab) {
    $this->eventTab = $eventTab;
  }

  /**
   * Hides options on the page
   *
   * @param string $formName
   * @param CRM_Core_Form $form
   */
  abstract public function handle($formName, &$form);

  /**
   * Checks if the hook should be handled.
   *
   * @param string $formName
   * @param class $formClass
   *
   * @return bool
   */
  protected function shouldHandle($formName, $formClass) {
    if ($formName === $formClass) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Hide fields on the Event Forms
   *
   * @param array $fieldIds
   *
   */
  protected function hideFields($fieldIds) {
    $selectors = [];
    foreach ($fieldIds as $fieldId) {
      $class = $this->eventTabAndClassMap[$this->eventTab] . '-form-block-' . $fieldId;
      $selectors[] = "tr[class={$class}]";
    }
    $selectors = implode(', ', $selectors);

    $this->hideElements($selectors);
  }

  /**
   * Hide elements by CSS selector
   *
   * @param string $selector
   *
   */
  protected function hideElements($selector) {
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $('{$selector}').hide();
      });
    ");
  }

}
