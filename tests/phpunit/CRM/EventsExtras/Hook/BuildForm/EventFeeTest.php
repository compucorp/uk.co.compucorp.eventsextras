<?php


use CRM_EventsExtras_Test_Fabricator_Setting as SettingFabricator;
use CRM_EventsExtras_SettingsManager as SettingsManager;

require_once __DIR__ . '/../../../../BaseHeadlessTest.php';

/**
 * Class CRM_EventsExtras_Hook_BuildForm_EventFeeTest
 *
 * @group headless
 */
class CRM_EventsExtras_Hook_BuildForm_EventFeeTest extends BaseHeadlessTest {

  /**
   * @var CRM_Event_Form_ManageEvent_EventFee
   */
  private $eventFeeForm;

  public function setUp() {
    $formController = new CRM_Core_Controller();
    $this->eventFeeForm = new CRM_Event_Form_ManageEvent_Fee();
    $this->eventFeeForm->controller = $formController;
    $this->eventFeeForm->buildForm();
  }

  public function testSetDefault() {
    $this->assertNull($this->eventFeeForm->getElementValue('payment_processor')[0]);

    SettingFabricator::fabricate([
      SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION'] => 0,
      SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT'] => [3, 5],
    ]);

    $eventFeeFormHook = new CRM_EventsExtras_Hook_BuildForm_EventFee();
    $eventFeeFormHook->handle('CRM_Event_Form_ManageEvent_Fee', $this->eventFeeForm);

    $this->assertEquals(
      [3, 5],
      array_keys($this->eventFeeForm->_defaultValues['payment_processor'])
    );
  }

}
