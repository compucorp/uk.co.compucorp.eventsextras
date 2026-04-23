<?php

use CRM_EventsExtras_Service_ThankYouPageRedirect as Service;
use CRM_EventsExtras_Test_Fabricator_Event as EventFabricator;

require_once __DIR__ . '/../../../../BaseHeadlessTest.php';

/**
 * @group headless
 */
class CRM_EventsExtras_Hook_BuildForm_EventRegistrationThankYouTest extends BaseHeadlessTest {

  private const QFKEY = 'test_hook_qfkey_xyz';
  private const REGISTER_FORM_NAME = 'CRM_Event_Form_Registration_Register';

  public function setUp(): void {
    $_GET = [];
    $_POST = [];
    $_REQUEST = [];
    Civi::cache('session')->clear();
  }

  public function testHandleStoresMappingForRegisterForm(): void {
    $event = EventFabricator::fabricate();
    $eventId = (int) $event['id'];
    $form = $this->fakeForm($eventId, self::QFKEY);

    (new CRM_EventsExtras_Hook_BuildForm_EventRegistrationThankYou())
      ->handle(self::REGISTER_FORM_NAME, $form);

    $this->assertSame($eventId, Civi::cache('session')->get(Service::CACHE_KEY_PREFIX . self::QFKEY));
  }

  public function testHandleIsNoOpForUnrelatedForm(): void {
    $event = EventFabricator::fabricate();
    $eventId = (int) $event['id'];
    $form = $this->fakeForm($eventId, self::QFKEY);

    (new CRM_EventsExtras_Hook_BuildForm_EventRegistrationThankYou())
      ->handle('CRM_Event_Form_Registration_Confirm', $form);

    $this->assertNull(Civi::cache('session')->get(Service::CACHE_KEY_PREFIX . self::QFKEY));
  }

  public function testHandleFallsBackToEventIdPropertyWhenGetEventIDThrows(): void {
    $event = EventFabricator::fabricate();
    $eventId = (int) $event['id'];
    $form = $this->fakeForm($eventId, self::QFKEY, TRUE);

    (new CRM_EventsExtras_Hook_BuildForm_EventRegistrationThankYou())
      ->handle(self::REGISTER_FORM_NAME, $form);

    $this->assertSame($eventId, Civi::cache('session')->get(Service::CACHE_KEY_PREFIX . self::QFKEY));
  }

  public function testHandleIsNoOpWhenEventIdCannotBeResolved(): void {
    $form = $this->fakeForm(NULL, self::QFKEY);

    (new CRM_EventsExtras_Hook_BuildForm_EventRegistrationThankYou())
      ->handle(self::REGISTER_FORM_NAME, $form);

    $this->assertNull(Civi::cache('session')->get(Service::CACHE_KEY_PREFIX . self::QFKEY));
  }

  public function testHandleIsNoOpWhenQfKeyCannotBeResolved(): void {
    $event = EventFabricator::fabricate();
    $eventId = (int) $event['id'];
    $form = $this->fakeForm($eventId, NULL);

    (new CRM_EventsExtras_Hook_BuildForm_EventRegistrationThankYou())
      ->handle(self::REGISTER_FORM_NAME, $form);

    $this->assertNull(Civi::cache('session')->get(Service::CACHE_KEY_PREFIX . self::QFKEY));
  }

  public function testHandleFallsBackToRequestQfKey(): void {
    $event = EventFabricator::fabricate();
    $eventId = (int) $event['id'];
    $_REQUEST['qfKey'] = self::QFKEY;
    $form = $this->fakeForm($eventId, NULL);

    (new CRM_EventsExtras_Hook_BuildForm_EventRegistrationThankYou())
      ->handle(self::REGISTER_FORM_NAME, $form);

    $this->assertSame($eventId, Civi::cache('session')->get(Service::CACHE_KEY_PREFIX . self::QFKEY));
  }

  private function fakeForm(?int $eventId, ?string $qfKey, bool $throwsOnGetEventID = FALSE): CRM_Event_Form_Registration_Register {
    $form = new EventsExtras_TestDouble_RegistrationRegisterForm();
    $form->_eventId = $eventId;
    $form->throwOnGetEventID = $throwsOnGetEventID;
    if ($qfKey !== NULL) {
      $form->controller = (object) ['_key' => $qfKey];
    }
    return $form;
  }

}

/**
 * Thin subclass that skips the parent constructor and lets the test control
 * what getEventID() returns or throws while still satisfying the
 * CRM_Core_Form / CRM_Event_Form_Registration_Register type hints on the
 * hook under test.
 */
class EventsExtras_TestDouble_RegistrationRegisterForm extends CRM_Event_Form_Registration_Register {

  public bool $throwOnGetEventID = FALSE;

  public function __construct() {
  }

  public function getEventID(): int {
    if ($this->throwOnGetEventID) {
      throw new RuntimeException('getEventID failure in test');
    }
    return (int) $this->_eventId;
  }

}
