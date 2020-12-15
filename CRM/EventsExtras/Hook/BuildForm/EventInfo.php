<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for EventInfo Form BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventInfo extends CRM_EventsExtras_Hook_BuildForm_BaseEvent {

  /**
   * CRM_EventsExtras_Hook_BuildForm_EventInfo constructor.
   */
  public function __construct() {
    parent::__construct(SettingsManager::EVENT_INFO);
  }

  /**
   * Hides options on the Event Info page
   *
   * @param string $formName
   * @param CRM_Event_Form_ManageEvent_EventInfo $form
   */
  public function handle($formName, &$form) {
    if (!$this->shouldHandle($formName, CRM_Event_Form_ManageEvent_EventInfo::class)) {
      return;
    }
    $this->buildForm($formName, $form);
  }

  private function buildForm($formName, &$form) {
    $this->setDefaults($form);
  }

  /**
   * Set defaults for form.
   *
   * @param array $form
   *
   */
  private function setDefaults(&$form) {
    $defaults = [];
    $fieldIdsToHide = [];

    $showRoles = SettingsManager::SETTING_FIELDS['ROLES'];
    $roleDefault = SettingsManager::SETTING_FIELDS['ROLES_DEFAULT'];
    $settings = [$showRoles, $roleDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showRoles] == 0) {
      $defaults['default_role_id'] = $settingValues[$roleDefault];
      $fieldIdsToHide[] = 'default_role_id';
    }

    $showParticipantListing = SettingsManager::SETTING_FIELDS['PARTICIPANT_LISTING'];
    $settings = [$showParticipantListing];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showParticipantListing] == 0) {
      $fieldIdsToHide[] = 'participant_listing_id';
    }

    $showIncludeMap = SettingsManager::SETTING_FIELDS['INCLUDE_MAP_LOCATION_EVENT'];
    $settings = [$showIncludeMap];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showIncludeMap] == 0) {
      $fieldIdsToHide[] = 'is_map';
    }

    $showPublicEvent = SettingsManager::SETTING_FIELDS['INCLUDE_MAP_PUBLIC_EVENT'];
    $settings = [$showPublicEvent];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showPublicEvent] == 0) {
      $fieldIdsToHide[] = 'is_public';
    }

    $this->hideFields($fieldIdsToHide);
    $form->setDefaults($defaults);
  }

}
