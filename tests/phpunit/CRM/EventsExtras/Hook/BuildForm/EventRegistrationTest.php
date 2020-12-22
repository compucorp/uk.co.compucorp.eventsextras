<?php


use CRM_EventsExtras_Test_Fabricator_Event as EventFabricator;
use CRM_EventsExtras_Test_Fabricator_Setting as SettingFabricator;
use CRM_EventsExtras_SettingsManager as SettingsManager;

require_once __DIR__ . '/../../../../BaseHeadlessTest.php';

/**
 * Class CRM_EventsExtras_Hook_BuildForm_EventRegistrationTest
 *
 * @group headless
 */
class CRM_EventsExtras_Hook_BuildForm_EventRegistrationTest extends BaseHeadlessTest {

  /**
   * @var CRM_Event_Form_ManageEvent_EventRegistration
   */
  private $eventRegistrationForm;

  public function setUp() {
    $event = EventFabricator::fabricate();

    $formController = new CRM_Core_Controller();
    $this->eventRegistrationForm = new CRM_Event_Form_ManageEvent_Registration();
    $this->eventRegistrationForm->controller = $formController;
    $this->eventRegistrationForm->set('id', $event['id']);
    $this->eventRegistrationForm->buildForm();
  }

  public function testSetDefault() {
    SettingFabricator::fabricate([
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS'] => 0,
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT'] => 1,
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT_MAXIMUM_PARTICIPANT'] => 5,
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_ALLOW_SAME_PARTICIPANT_EMAILS_DEFAULT'] => 1,
    ]);

    $eventRegistrationFormHook = new CRM_EventsExtras_Hook_BuildForm_EventRegistration();
    $eventRegistrationFormHook->handle('CRM_Event_Form_ManageEvent_Registration', $this->eventRegistrationForm);

    $this->assertEquals(1, $this->eventRegistrationForm->_defaultValues['is_multiple_registrations']);
    $this->assertEquals(5, $this->eventRegistrationForm->_defaultValues['max_additional_participants']);

    $this->assertEquals(1, $this->eventRegistrationForm->_defaultValues['allow_same_participant_emails']);
  }

}
