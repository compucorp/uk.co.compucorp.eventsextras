<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for for Pre Hook
 */
class CRM_EventsExtras_Hook_Pre_ManageEvent {

  /**
   * Handle Hook Pre Event
   *
   * @param string $formName
   * @param string $objectName
   * @param int $id
   * @param array $params
   */
  public function handle($op, $objectName, $id, &$params) {
    if (!$this->shouldHandle($op, $objectName)) {
      return;
    }
    $this->preSetData($op, $id, $params);
  }

  /**
   * Checks whether the hook should be handled or not.
   *
   * @param string $op
   * @param string $objectName
   *
   * @return bool
   */
  private function shouldHandle($op, $objectName) {
    return $objectName == 'Event';
  }

   /**
   * Set event parameter piror setting to database
   *
   * @param string $op
   * @param int $id
   * @param array $params
   *
   */
  private function preSetData($op, $id, &$params ){
    if ($op == 'delete' || $op == 'view') {
      return;
    }
    if (array_key_exists('event_type_id', $params)) {
      $this->preProcessData(SettingsManager::EVENT_INFO, $params);
    } elseif (array_key_exists('is_monetary', $params) && $params['is_monetary'] == 1) {
      $this->preProcessData(SettingsManager::EVENT_FEE, $params);
    } elseif (array_key_exists('is_online_registration', $params) && $params['is_online_registration'] == 1) {
      $this->preProcessData(SettingsManager::EVENT_REGISTRATION, $params);
    }
  }

  private function preProcessData($section, &$params){
    $fields = SettingsManager::getConfigFields($section);
    $settingsToProcess = [];
    $fieldToProcess = [];
    foreach ($fields as $field){
      $settingName = $field['name'];
      $settingValue = SettingsManager::getSettingValue($settingName);
      if (isset($settingValue[$settingName])){
        $settingValue = $settingValue[$settingName];
        if (!array_key_exists('parent_setting', $field['extra_attributes'])){ //handle parent setting
          if ($settingValue == 0) {
            $settingsToProcess[$settingName] = $settingName;
          }
        } else { //handle child setting and get element name
          if (in_array($field['extra_attributes']['parent_setting'], $settingsToProcess)){
            $formName = $field['extra_attributes']['event_form_element_name'];
            $fieldToProcess[$formName] = $settingValue;
          }
        }
      }
    }
    foreach ($fieldToProcess as $field => $value){
      if ($section != SettingsManager::EVENT_FEE && $field != 'payment_processor'){
        $params[$field] = $value;
      }
    }
  }
}

