<?php

use CRM_EventsExtras_Test_Fabricator_Setting as SettingFabricator;
use CRM_EventsExtras_SettingsManager as SettingsManager;

require_once __DIR__ . '/../../../../BaseHeadlessTest.php';

/**
 * Class CRM_EventsExtras_Hook_BuildForm_EventInfoTest
 *
 * @group headless
 */
class CRM_EventsExtras_Hook_BuildForm_EventInfoTest extends BaseHeadlessTest {

  /**
   * @var CRM_EventsExtras_Hook_BuildForm_EventInfo
   */
  private $eventInfoForm;

  public function testSetDefault() {
    $formController = new CRM_Core_Controller();
    $eventInfoForm = new CRM_Event_Form_ManageEvent_EventInfo();
    $eventInfoForm->controller = $formController;
    $eventInfoForm->buildForm();

    $this->assertNull($eventInfoForm->getElementValue('default_role_id')[0]);

    SettingFabricator::fabricate([
      SettingsManager::SETTING_FIELDS['ROLES'] => 0,
      SettingsManager::SETTING_FIELDS['ROLES_DEFAULT'] => 3,
    ]);

    $eventInfoFormHook = new CRM_EventsExtras_Hook_BuildForm_EventInfo();
    $eventInfoFormHook->handle('CRM_Event_Form_ManageEvent_EventInfo', $eventInfoForm);

    $this->assertEquals(3, $eventInfoForm->_defaultValues['default_role_id']);
  }

}
