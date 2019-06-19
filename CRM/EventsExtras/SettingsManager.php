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
    'eventsextras_participant_listing',
    'eventsextras_event_summary',
    'eventsextras_completed_description',
    'eventsextras_include_map_event_location',
    'eventsextras_public_event',
    'eventsextras_currency',
    'eventsextras_enable_pay_later_option',
    'eventsextras_payment_processor_selection',
    'eventsextras_pending_participant_expiration',
    'eventsextras_allow_self_service',
    'eventsextras_register_multiple_participants',
  ];

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
  public static function getConfigFields() {
    $configFields = self::fetchSettingFields();
    if (!isset($configFields) || empty($configFields)) {
      $result = civicrm_api3('System', 'flush');
      if ($result['is_error'] == 0){
        $configFields =  self::fetchSettingFields();
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