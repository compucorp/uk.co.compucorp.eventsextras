<?php

use Civi\Core\Event\GenericHookEvent;
use CRM_EventsExtras_Service_ThankYouPageRedirect as Service;
use CRM_EventsExtras_Test_Fabricator_Event as EventFabricator;

require_once __DIR__ . '/../../../BaseHeadlessTest.php';

/**
 * @group headless
 */
class CRM_EventsExtras_Service_ThankYouPageRedirectTest extends BaseHeadlessTest {

  private const QFKEY_A = 'test_qfkey_aaa';
  private const QFKEY_B = 'test_qfkey_bbb';

  public function setUp(): void {
    $_GET = [];
    $_POST = [];
    $_REQUEST = [];
    Civi::cache('session')->clear();
  }

  public function testStoreEventIdPersistsMapping(): void {
    Service::storeEventId(self::QFKEY_A, 42);

    $this->assertSame(42, Civi::cache('session')->get(Service::CACHE_KEY_PREFIX . self::QFKEY_A));
  }

  public function testStoreEventIdNoOpForEmptyQfKey(): void {
    Service::storeEventId('', 42);

    $this->assertNull(Civi::cache('session')->get(Service::CACHE_KEY_PREFIX));
  }

  public function testStoreEventIdNoOpForZeroEventId(): void {
    Service::storeEventId(self::QFKEY_A, 0);

    $this->assertNull(Civi::cache('session')->get(Service::CACHE_KEY_PREFIX . self::QFKEY_A));
  }

  public function testRedirectIfNeededNoOpWhenPathIsNotEventRegister(): void {
    $event = EventFabricator::fabricate();
    Service::storeEventId(self::QFKEY_A, (int) $event['id']);
    $this->simulateThankYouRefresh(self::QFKEY_A);

    $this->assertNoRedirect(function () {
      Service::redirectIfNeeded($this->hookEvent(['civicrm', 'contribute', 'transact']));
    });
  }

  public function testRedirectIfNeededNoOpWhenThankYouDisplayMissing(): void {
    $event = EventFabricator::fabricate();
    Service::storeEventId(self::QFKEY_A, (int) $event['id']);
    $_REQUEST['qfKey'] = self::QFKEY_A;

    $this->assertNoRedirect(function () {
      Service::redirectIfNeeded($this->hookEvent(['civicrm', 'event', 'register']));
    });
  }

  public function testRedirectIfNeededNoOpWhenIdAlreadyInRequest(): void {
    $event = EventFabricator::fabricate();
    Service::storeEventId(self::QFKEY_A, (int) $event['id']);
    $this->simulateThankYouRefresh(self::QFKEY_A);
    $_REQUEST['id'] = (int) $event['id'];

    $this->assertNoRedirect(function () {
      Service::redirectIfNeeded($this->hookEvent(['civicrm', 'event', 'register']));
    });
  }

  public function testRedirectIfNeededNoOpWhenQfKeyMissing(): void {
    $event = EventFabricator::fabricate();
    Service::storeEventId(self::QFKEY_A, (int) $event['id']);
    $_REQUEST['_qf_ThankYou_display'] = 1;

    $this->assertNoRedirect(function () {
      Service::redirectIfNeeded($this->hookEvent(['civicrm', 'event', 'register']));
    });
  }

  public function testRedirectIfNeededNoOpWhenWizardSessionStillHoldsId(): void {
    $event = EventFabricator::fabricate();
    $eventId = (int) $event['id'];

    Service::storeEventId(self::QFKEY_A, $eventId);
    $this->simulateThankYouRefresh(self::QFKEY_A);
    Civi::cache('session')->set(
      Service::WIZARD_SESSION_KEY_PREFIX . self::QFKEY_A,
      ['id' => $eventId, 'qfKey' => self::QFKEY_A]
    );

    $this->assertNoRedirect(function () {
      Service::redirectIfNeeded($this->hookEvent(['civicrm', 'event', 'register']));
    });
  }

  public function testRedirectIfNeededNoOpWhenNoMappingStored(): void {
    $this->simulateThankYouRefresh(self::QFKEY_A);

    $this->assertNoRedirect(function () {
      Service::redirectIfNeeded($this->hookEvent(['civicrm', 'event', 'register']));
    });
  }

  public function testRedirectIfNeededClearsStaleMappingWhenEventIsDeleted(): void {
    Service::storeEventId(self::QFKEY_A, 99999999);
    $this->simulateThankYouRefresh(self::QFKEY_A);

    $this->assertNoRedirect(function () {
      Service::redirectIfNeeded($this->hookEvent(['civicrm', 'event', 'register']));
    });

    $this->assertNull(Civi::cache('session')->get(Service::CACHE_KEY_PREFIX . self::QFKEY_A));
  }

  public function testRedirectIfNeededRedirectsToEventRegisterPage(): void {
    $event = EventFabricator::fabricate();
    $eventId = (int) $event['id'];

    Service::storeEventId(self::QFKEY_A, $eventId);
    $this->simulateThankYouRefresh(self::QFKEY_A);

    try {
      Service::redirectIfNeeded($this->hookEvent(['civicrm', 'event', 'register']));
      $this->fail('Expected CRM_Core_Exception_PrematureExitException was not thrown.');
    }
    catch (CRM_Core_Exception_PrematureExitException $e) {
      $url = $e->errorData['url'] ?? '';
      $this->assertTrue(
        strpos($url, 'civicrm/event/register') !== FALSE,
        'Redirect URL should point to civicrm/event/register.'
      );
      $this->assertTrue(
        strpos($url, 'id=' . $eventId) !== FALSE,
        "Redirect URL should contain id={$eventId}."
      );
      $this->assertTrue(
        strpos($url, 'reset=1') !== FALSE,
        'Redirect URL should contain reset=1.'
      );
    }
  }

  public function testRedirectIfNeededResolvesPerQfKey(): void {
    $eventA = EventFabricator::fabricate(['title' => 'Event A']);
    $eventB = EventFabricator::fabricate(['title' => 'Event B']);
    $idA = (int) $eventA['id'];
    $idB = (int) $eventB['id'];

    Service::storeEventId(self::QFKEY_A, $idA);
    Service::storeEventId(self::QFKEY_B, $idB);

    $this->simulateThankYouRefresh(self::QFKEY_B);

    try {
      Service::redirectIfNeeded($this->hookEvent(['civicrm', 'event', 'register']));
      $this->fail('Expected CRM_Core_Exception_PrematureExitException was not thrown.');
    }
    catch (CRM_Core_Exception_PrematureExitException $e) {
      $url = $e->errorData['url'] ?? '';
      $this->assertTrue(
        strpos($url, 'id=' . $idB) !== FALSE,
        "Redirect URL should resolve to event B (id={$idB})."
      );
      $this->assertTrue(
        strpos($url, 'id=' . $idA) === FALSE,
        "Redirect URL must not contain event A's id ({$idA})."
      );
    }
  }

  private function simulateThankYouRefresh(string $qfKey): void {
    $_REQUEST['_qf_ThankYou_display'] = 1;
    $_REQUEST['qfKey'] = $qfKey;
    unset($_REQUEST['id']);
  }

  private function hookEvent(array $args): GenericHookEvent {
    return GenericHookEvent::create(['args' => $args]);
  }

  private function assertNoRedirect(callable $fn): void {
    try {
      $fn();
    }
    catch (CRM_Core_Exception_PrematureExitException $e) {
      $this->fail('Expected no redirect, but was redirected to ' . ($e->errorData['url'] ?? '?'));
    }
    $this->assertTrue(TRUE);
  }

}
