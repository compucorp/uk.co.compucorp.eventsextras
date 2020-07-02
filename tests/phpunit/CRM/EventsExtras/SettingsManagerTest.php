<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

require_once __DIR__ . '/../../BaseHeadlessTest.php';

/**
 * Runs tests on SettingsManager.
 *
 * @group headless
 */
class CRM_ManualDirectDebit_Common_SettingsManagerTest extends BaseHeadlessTest {

  /**
   * Tests getSettingValue
   */
  public function testGetSettingValue() {
    $settingsManager = new CRM_EventsExtras_SettingsManager();
    $locationTab = $settingsManager->getSettingValue(CRM_EventsExtras_SettingsManager::SETTING_FIELDS['LOCATION_TAB']);
    $this->assertNotEmpty($locationTab);
  }


  /**
   * Tests getSettingsValue
   */
  public function testGetSettingsValue() {
    $settingsManager = new CRM_EventsExtras_SettingsManager();
    $settings = $settingsManager->getSettingsValue();
    $this->assertArrayHasKey(SettingsManager::SETTING_FIELDS['LOCATION_TAB'], $settings);
    $this->assertArrayHasKey(SettingsManager::SETTING_FIELDS['TELL_FRIEND_TAB'], $settings);
    $this->assertArrayHasKey(SettingsManager::SETTING_FIELDS['PCP_TAB'], $settings);
    $this->assertArrayHasKey(SettingsManager::SETTING_FIELDS['ROLES'], $settings);
    $this->assertArrayHasKey(SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT'], $settings);

  }

  /**
   * Tests getSettingsValue with Params
   */
  public function getSettingsValueWithParams() {
    $settingsManager = new CRM_EventsExtras_SettingsManager();
    $params = [
      SettingsManager::SETTING_FIELDS['LOCATION_TAB'],
      SettingsManager::SETTING_FIELDS['TELL_FRIEND_TAB'],
    ];
    $settings = $settingsManager->getSettingsValue($params);
    $this->assertEquals(2, count($settings));
    $this->assertArrayHasKey(SettingsManager::SETTING_FIELDS['LOCATION_TAB'], $settings);
    $this->assertArrayHasKey(SettingsManager::SETTING_FIELDS['TELL_FRIEND_TAB'], $settings);
  }

  /**
   * Tests getConfigFields
   */
  public function testGetConfigFields() {
    $settingsManager = new CRM_EventsExtras_SettingsManager();
    $configFields = $settingsManager->getConfigFields();
    $this->assertNotEmpty($configFields);
    $configFields = $settingsManager->getConfigFields(SettingsManager::EVENT_TAB);
    $this->assertNotEmpty($configFields);
    $this->assertEquals(3, count($configFields));
    $configFields = $settingsManager->getConfigFields(SettingsManager::EVENT_INFO);
    $this->assertNotEmpty($configFields);
    $this->assertEquals(10, count($configFields));
    $configFields = $settingsManager->getConfigFields(SettingsManager::EVENT_FEE);
    $this->assertNotEmpty($configFields);
    $this->assertEquals(9, count($configFields));
    $configFields = $settingsManager->getConfigFields(SettingsManager::EVENT_REGISTRATION);
    $this->assertNotEmpty($configFields);
    $this->assertEquals(8, count($configFields));
  }


}
