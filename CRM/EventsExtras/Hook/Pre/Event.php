<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for Event Fee Pre Hook
 */
class CRM_EventsExtras_Hook_Pre_Event {


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

  private function preSetData($op, $id, &$params ){
    if ($op == 'delete' || $op == 'view') {
      return;
    }
    if (array_key_exists('event_type_id', $params)) {
      $this->preProcessData(SettingsManager::EVENT_INFO, $params);
    } elseif (array_key_exists('is_monetary', $params)) {
      $this->preProcessData(SettingsManager::EVENT_FEE, $params);
    } elseif (array_key_exists('is_online_registration', $params)) {
      $this->preProcessData(SettingsManager::EVENT_REGISTRATION, $params);
    }
  }

  private function preProcessData($section, &$params){
    $fields = SettingsManager::getConfigFields($section);
    $settingsToProcess = [];
    $fieldToProcess = [];
    foreach ($fields as $field){
      $settingName = $field['name'];
      $settingValue = SettingsManager::getSettingValue($settingName)[$settingName];
      if(!array_key_exists('parent_setting', $field['extra_attributes'])){ //handle parent setting
        if ($settingValue == 0){
          $settingsToProcess[$settingName] = $settingName;
        }else {
          unset($fields[$settingName]);
        }
      } else {
        if (in_array($field['extra_attributes']['parent_setting'], $settingsToProcess)){
          $formName = $field['extra_attributes']['event_form_element_name'];
          $fieldToProcess[$formName] = $settingValue;
        }
      }
    }
    foreach ($fieldToProcess as $field => $value){
      $params[$field] = $value;
    }
  }
}

