<?php

/**
 * Class CRM_MventsExtras_Test_Fabricator_Setting
 */
class CRM_EventsExtras_Test_Fabricator_Setting {

  /**
   * @param array $params
   */
  public static function fabricate($params = []) {
    foreach ($params as $key => $value) {
      \Civi::settings()->set($key, $value);
    }
  }

}
