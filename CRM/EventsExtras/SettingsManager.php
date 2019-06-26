<?php

/**
 * Helps manage settings for the extension.
 */
class CRM_EventsExtras_SettingsManager {

  /**
   * Constants for setting sections
   */
  const EVENT_INFO = 'event_info';
  const EVENT_FEE = 'event_fee';
  const EVENT_REGISTRATION = 'event_online_registration';

    /**
    * Constants value for settings name
    */
   const SETTING_FIELDS = [
    'eventsextras_roles',
    'eventsextras_roles_default',
    'eventsextras_participant_listing',
    'eventsextras_participant_listing_default',
    'eventsextras_event_summary',
    'eventsextras_event_description',
    'eventsextras_include_map_event_location',
    'eventsextras_include_map_event_location_default',
    'eventsextras_public_event',
    'eventsextras_public_event_default',
    'eventsextras_currency',
    'eventsextras_currency_default',
    'eventsextras_payment_processor_selection',
    'eventsextras_payment_processor_selection_default',
    'eventsextras_enable_pay_later_option',
    'eventsextras_enable_pay_later_option_default',
    'eventsextras_enable_pay_later_option_default_label',
    'eventsextras_enable_pay_later_option_default_instruction',
    'eventsextras_enable_pay_later_option_default_billing_address_required',
    'eventsextras_pending_participant_expiration',
    'eventsextras_pending_participant_expiration_default',
    'eventsextras_allow_self_service',
    'eventsextras_allow_self_service_default',
    'eventsextras_allow_self_service_default_time_limit',
    'eventsextras_register_multiple_participants',
    'eventsextras_register_multiple_participants_default_maxinum_participant'
  ];

   /**
   * Gets the settings Value
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
   * Gets the settings Value
   *
   * @return array
  */
  public static function getSettingsValue(){
    return civicrm_api3('setting', 'get', [
      'return' => self::SETTING_FIELDS,
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
    foreach (self::SETTING_FIELDS  as $name) {
        $settingFields[$name] = civicrm_api3('setting', 'getfields',[
            'filters' => [ 'name' => $name],
        ])['values'][$name];
    }
    return $settingFields;
  }
}

