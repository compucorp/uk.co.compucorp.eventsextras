<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for Event Online Registartion BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventRegistration extends CRM_EventsExtras_Hook_BuildForm_BaseEvent {

  /**
   * CRM_EventsExtras_Hook_BuildForm_EventRegistration constructor.
   */
  public function __construct() {
    parent::__construct(SettingsManager::EVENT_REGISTRATION);
  }

  /**
   * Hides options on the Event Registration page
   *
   * @param string $formName
   * @param CRM_Event_Form_ManageEvent_Registration $form
   */
  public function handle($formName, &$form) {
    if (!$this->shouldHandle($formName, CRM_Event_Form_ManageEvent_Registration::class)) {
      return;
    }
    $this->buildForm($formName, $form);
  }

  private function buildForm($formName, &$form) {
    $this->setDefaults($form);
  }

  private function setDefaults(&$form) {
    $defaults = [];
    $fieldIdsToHide = [];

    $showPendingParticipantExpiration = SettingsManager::SETTING_FIELDS['PENDING_PARTICIPANT_EXPIRATION'];
    $pendingParticipantExpirationDefault = SettingsManager::SETTING_FIELDS['PENDING_PARTICIPANT_EXPIRATION_DEFAULT'];
    $settings = [$showPendingParticipantExpiration, $pendingParticipantExpirationDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showPendingParticipantExpiration] == 0) {
      $defaults['expiration_time'] = $settingValues[$pendingParticipantExpirationDefault];
      $fieldIdsToHide[] = 'expiration_time';
    }

    $showAllowSelfServiceAction = SettingsManager::SETTING_FIELDS['ALLOW_SELF_SERVICE'];
    $allowSelfServiceActionDefault = SettingsManager::SETTING_FIELDS['ALLOW_SELF_SERVICE_DEFAULT'];
    $timeLimit = SettingsManager::SETTING_FIELDS['ALLOW_SELF_SERVICE_DEFAULT_TIME_LIMIT'];
    $settings = [$showAllowSelfServiceAction, $allowSelfServiceActionDefault, $timeLimit];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showAllowSelfServiceAction] == 0) {
      $defaults['allow_selfcancelxfer'] = $settingValues[$allowSelfServiceActionDefault];
      $defaults['selfcancelxfer_time'] = $settingValues[$timeLimit];

      // @note allow_selfcancelxfer's parent tr in civicrm/templates/CRM/Event/Form/ManageEvent/Registration.tpl
      // has a missing 'allow' i.e. "crm-event-manage-registration-form-block-selfcancelxfer" (CiviCRM bug)
      $fieldIdsToHide[] = 'selfcancelxfer';
      $fieldIdsToHide[] = 'selfcancelxfer_time';
    }

    $showRegisterMultipleParticipants = SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS'];
    $registerMultipleParticipantsDefault = SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT'];
    $maximumParticipant = SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT_MAXIMUM_PARTICIPANT'];
    $settings = [$showRegisterMultipleParticipants, $registerMultipleParticipantsDefault, $maximumParticipant];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showRegisterMultipleParticipants] == 0) {
      $defaults['is_multiple_registrations'] = $settingValues[$registerMultipleParticipantsDefault];
      $defaults['max_additional_participants'] = $settingValues[$maximumParticipant];
      $fieldIdsToHide[] = 'is_multiple_registrations';

      // @note max_additional_participants's parent tr in civicrm/templates/CRM/Event/Form/ManageEvent/Registration.tpl
      // has a 'maximum' in its name instead of max i.e. "crm-event-manage-registration-form-block-maximum_additional_participants" (CiviCRM bug)
      $fieldIdsToHide[] = 'maximum_additional_participants';
    }

    $this->hideFields($fieldIdsToHide);
    $form->setDefaults($defaults);
  }

}
