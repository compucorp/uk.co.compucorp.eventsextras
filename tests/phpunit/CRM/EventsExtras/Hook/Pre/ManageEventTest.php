<?php

use CRM_EventsExtras_Test_Fabricator_Setting as SettingFabricator;
use CRM_EventsExtras_SettingsManager as SettingsManager;

require_once __DIR__ . '/../../../../BaseHeadlessTest.php';

/**
 * Class CRM_EventsExtras_Hook_Pre_ManageEventTest
 *
 * @group headless
 */
class CRM_EventsExtras_Hook_Pre_ManageEventTest extends BaseHeadlessTest {

  public function testEventInfo() {

    //fake params
    $params = [
      'event_type_id' => 2,
      'is_public' => NULL,
      'is_map' => NULL,
      'participant_listing_id' => NULL,
    ];

    //Fabricate settings if user select field to hide and set default value
    SettingFabricator::fabricate([
      SettingsManager::SETTING_FIELDS['INCLUDE_MAP_LOCATION_EVENT'] => 0,
      SettingsManager::SETTING_FIELDS['INCLUDE_MAP_LOCATION_EVENT_DEFAULT'] => 1,
      SettingsManager::SETTING_FIELDS['INCLUDE_MAP_PUBLIC_EVENT'] => 0,
      SettingsManager::SETTING_FIELDS['INCLUDE_MAP_PUBLIC_EVENT_DEFAULT'] => 1,
    ]);

    //Fabricate settings if user select to show field and default value was set.
    //In this case default should not be assigned to the param;
    SettingFabricator::fabricate([
      SettingsManager::SETTING_FIELDS['PARTICIPANT_LISTING'] => 1,
      SettingsManager::SETTING_FIELDS['PARTICIPANT_LISTING_DEFAULT'] => 1,
    ]);

    //make sure participant listing default is set
    $participantListingDefault = SettingsManager::getSettingValue(SettingsManager::SETTING_FIELDS['PARTICIPANT_LISTING_DEFAULT']);
    $this->assertEquals(1, $participantListingDefault[SettingsManager::SETTING_FIELDS['PARTICIPANT_LISTING_DEFAULT']]);

    $preManageEventHook = new CRM_EventsExtras_Hook_Pre_ManageEvent();
    $preManageEventHook->handle('create', 'Event', NULL, $params);

    $this->assertEquals(1, $params['is_public']);
    $this->assertEquals(1, $params['is_map']);

    $this->assertNull($params['participant_listing_id']);

  }

  public function testEventFee() {

    $params = [
      'is_monetary' => 1,
      'payment_processor' => NULL,
      'currency' => NULL,
      'is_pay_later' => NULL,
      'pay_later_text' => NULL,
      'pay_later_receipt' => NULL,
      'is_billing_required' => NULL,
    ];

    SettingFabricator::fabricate([
      SettingsManager::SETTING_FIELDS['CURRENCY'] => 0,
      SettingsManager::SETTING_FIELDS['CURRENCY_DEFAULT'] => 1,
      SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION'] => 0,
      SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT'] => [7, 8],
      SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION'] => 0,
      SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT'] => 1,
      SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT_LABEL'] => 'Test Label',
      SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT_LABEL_INSTRUCTION'] => 'Test Instruction',
      SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT_BILLING_ADDRESS'] => 1,
    ]);

    $preManageEventHook = new CRM_EventsExtras_Hook_Pre_ManageEvent();
    $preManageEventHook->handle('edit', 'Event', '1', $params);

    $this->assertEquals(1, $params['currency']);
    //payment processor should remain NULl as we are not set payment process on Pre Hook'
    $this->assertNull($params['payment_processor']);
    $this->assertEquals(1, $params['is_pay_later']);
    $this->assertEquals('Test Label', $params['pay_later_text']);
    $this->assertEquals('Test Instruction', $params['pay_later_receipt']);
    $this->assertEquals(1, $params['is_billing_required']);

  }

  public function testEventOnlineRegistration() {

    $params = [
      'is_online_registration' => 1,
      'expiration_time' => NULL,
      'allow_selfcancelxfer' => NULL,
      'selfcancelxfer_time' => NULL,
      'is_multiple_registrations' => NULL,
      'max_additional_participants' => NULL,
    ];

    SettingFabricator::fabricate([
      SettingsManager::SETTING_FIELDS['PENDING_PARTICIPANT_EXPIRATION'] => 0,
      SettingsManager::SETTING_FIELDS['PENDING_PARTICIPANT_EXPIRATION_DEFAULT'] => 24,
      SettingsManager::SETTING_FIELDS['ALLOW_SELF_SERVICE'] => 0,
      SettingsManager::SETTING_FIELDS['ALLOW_SELF_SERVICE_DEFAULT'] => 1,
      SettingsManager::SETTING_FIELDS['ALLOW_SELF_SERVICE_DEFAULT_TIME_LIMIT'] => 48,
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS'] => 0,
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT'] => 1,
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT_MAXIMUM_PARTICIPANT'] => 9,
    ]);

    $preManageEventHook = new CRM_EventsExtras_Hook_Pre_ManageEvent();
    $preManageEventHook->handle('edit', 'Event', '1', $params);

    $this->assertEquals(24, $params['expiration_time']);
    $this->assertEquals(1, $params['allow_selfcancelxfer']);
    $this->assertEquals(48, $params['selfcancelxfer_time']);
    $this->assertEquals(1, $params['is_multiple_registrations']);
    $this->assertEquals(9, $params['max_additional_participants']);

  }

}
