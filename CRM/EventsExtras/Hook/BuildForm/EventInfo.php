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
      $form->getElement('default_role_id')->setSelected($settingValues[$roleDefault]);
    }

    $showParticipantListing = SettingsManager::SETTING_FIELDS['PARTICIPANT_LISTING'];
    $participantListingDefault = SettingsManager::SETTING_FIELDS['PARTICIPANT_LISTING_DEFAULT'];
    $settings = [$showParticipantListing, $participantListingDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showParticipantListing] == 0) {
      $defaults['participant_listing_id'] = $settingValues[$participantListingDefault];
      $fieldIdsToHide[] = 'participant_listing_id';
      $form->getElement('participant_listing_id')->setSelected($settingValues[$participantListingDefault]);
    }

    $showIncludeMap = SettingsManager::SETTING_FIELDS['INCLUDE_MAP_LOCATION_EVENT'];
    $includeMapDefault = SettingsManager::SETTING_FIELDS['INCLUDE_MAP_LOCATION_EVENT_DEFAULT'];
    $settings = [$showIncludeMap, $includeMapDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showIncludeMap] == 0) {
      $defaults['is_map'] = $settingValues[$includeMapDefault];
      $fieldIdsToHide[] = 'is_map';
    }

    $showPublicEvent = SettingsManager::SETTING_FIELDS['INCLUDE_MAP_PUBLIC_EVENT'];
    $publicEventDefault = SettingsManager::SETTING_FIELDS['INCLUDE_MAP_PUBLIC_EVENT_DEFAULT'];
    $settings = [$showPublicEvent, $publicEventDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showPublicEvent] == 0) {
      $defaults['is_public'] = $settingValues[$publicEventDefault];
      $fieldIdsToHide[] = 'is_public';
    }

    $showEventDescription = SettingsManager::SETTING_FIELDS['EVENT_DESCRIPTION'];
    $settingValues = SettingsManager::getSettingsValue($showEventDescription);
    if ($settingValues[$showEventDescription] == 0) {
      $fieldIdsToHide[] = 'description';
    }

    $showEventSummary = SettingsManager::SETTING_FIELDS['PARTICIPANT_SUMMARY'];
    $settingValues = SettingsManager::getSettingsValue($showEventSummary);
    if ($settingValues[$showEventSummary] == 0) {
      $fieldIdsToHide[] = 'summary';
    }

    $this->hideFields($fieldIdsToHide);
    $form->setDefaults($defaults);
  }

}
