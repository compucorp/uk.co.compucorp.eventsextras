<?php

/**
 * Helps manage settings for the extension.
 */
class CRM_EventsExtras_SettingsManager {

  /**
   * Constants for setting sections
   */
  const EVENT_TAB = 'event_tab';
  const EVENT_INFO = 'event_info';
  const EVENT_FEE = 'event_fee';
  const EVENT_REGISTRATION = 'event_online_registration';


  const SETTING_FIELDS = [
   'LOCATION_TAB' => 'eventsextras_location_tab',
   'TELL_FRIEND_TAB' => 'eventsextras_tell_friend_tab',
   'PCP_TAB' => 'eventsextras_pcp_tab',
   'ROLES' => 'eventsextras_roles',
   'ROLES_DEFAULT' => 'eventsextras_roles_default',
   'PARTICIPANT_LISTING' => 'eventsextras_participant_listing',
   'PARTICIPANT_LISTING_DEFAULT' => 'eventsextras_participant_listing_default',
   'PARTICIPANT_SUMMARY' => 'eventsextras_event_summary',
   'EVENT_DESCRTIPTION' => 'eventsextras_event_description',
   'INCLUDE_MAP_LOCATION_EVENT' => 'eventsextras_include_map_event_location',
   'INCLUDE_MAP_LOCATION_EVENT_DEFAULT' => 'eventsextras_include_map_event_location_default',
   'INCLUDE_MAP_PUBLIC_EVENT' => 'eventsextras_public_event',
   'INCLUDE_MAP_PUBLIC_EVENT_DEFAULT' => 'eventsextras_public_event_default',
   'CURRENCY' => 'eventsextras_currency',
   'CURRENCY_DEFAULT' => 'eventsextras_currency_default',
   'PAYMENT_PROCESSOR_SELECTION' => 'eventsextras_payment_processor_selection',
   'PAYMENT_PROCESSOR_SELECTION_DEFAULT' => 'eventsextras_payment_processor_selection_default',
   'PAY_LATER_OPTION' => 'eventsextras_enable_pay_later_option',
   'PAY_LATER_OPTION_DEFAULT' => 'eventsextras_enable_pay_later_option_default',
   'PAY_LATER_OPTION_DEFAULT_LABEL' => 'eventsextras_enable_pay_later_option_default_label',
   'PAY_LATER_OPTION_DEFAULT_LABEL_INSTRUCTION' => 'eventsextras_enable_pay_later_option_default_instruction',
   'PAY_LATER_OPTION_DEFAULT_BILLING_ADDRESS' => 'eventsextras_enable_pay_later_option_default_billing_address_required',
   'PENDING_PARTICIPANT_EXPIRATION' => 'eventsextras_pending_participant_expiration',
   'PENDING_PARTICIPANT_EXPIRATION_DEFAULT' => 'eventsextras_pending_participant_expiration_default',
   'ALLOW_SELF_SERIVCE' => 'eventsextras_allow_self_service',
   'ALLOW_SELF_SERVICE_DEFAULT' => 'eventsextras_allow_self_service_default',
   'ALLOW_SELF_SERVICE_DEFAULT_TIME_LIMIT' => 'eventsextras_allow_self_service_default_time_limit',
   'REGISTER_MULTIPLE_PARTICIPANTS' => 'eventsextras_register_multiple_participants',
   'REGISTER_MULTIPLE_PARTICIPANTS_DEFULT' => 'eventsextras_register_multiple_participants_default',
   'REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT_MAXIMUM_PARTICIPANT' => 'eventsextras_register_multiple_participants_default_maximum_participant'
  ];

   /**
   * Gets a single setting value
   *
   * @return array
  */
  public static function getSettingValue($setting){
    return civicrm_api3('setting', 'get', [
      'return' => $setting,
      'sequential' => 1,
    ])['values'][0];
  }

   /**
   * Gets multiple settings values
   *
   * @return array
  */
  public static function getSettingsValue($settings = NULL){
    if ($settings == NULL){
      $settings = array_values(self::SETTING_FIELDS);
    }
    return civicrm_api3('setting', 'get', [
      'return' => $settings,
      'sequential' => 1,
    ])['values'][0];
  }

  /**
   * Gets the extension configuration fields
   *
   * @return array
   */
  public static function getConfigFields($section = NULL) {
    $configFields = self::fetchSettingFields();
    if (!isset($configFields) || empty($configFields)) {
      $result = civicrm_api3('System', 'flush');
      if ($result['is_error'] == 0){
        $configFields =  self::fetchSettingFields();
      }
    }
    if ($section != NULL){ //if section is passed, only return settings in section
      foreach ($configFields as $field){
        if ($field['extra_attributes']['section'] != $section) {
          unset($configFields[$field['name']]);
        }
      }
    }
    return $configFields;

  }

  /**
   * Fetches setting fields
   *
   * @return array
   */
  private static function fetchSettingFields() {
    $settingFields = [];
    foreach (array_values(self::SETTING_FIELDS)  as $name) {
        $settingFields[$name] = civicrm_api3('setting', 'getfields',[
            'filters' => [ 'name' => $name],
        ])['values'][$name];
    }
    return $settingFields;
  }

}

