<?php

/**
 * Service that handles the "refresh the Event Registration Thank You page" edge case.
 */
class CRM_EventsExtras_Service_ThankYouPageRedirect {

  /**
   * CiviCRM cache bucket used to persist qfKey -> event id mappings.
   */
  const CACHE_BUCKET = 'session';

  /**
   * Prefix applied to every cache key so our data is easy to namespace.
   */
  const CACHE_KEY_PREFIX = 'eventsextras_register_qfkey_';

  /**
   * Fallback TTL (seconds) used when "secure_cache_timeout_minutes" is empty or 0.
   */
  const DEFAULT_TTL_SECONDS = 20 * 60;

  /**
   * CiviCRM route handled by this redirect.
   */
  const EVENT_REGISTER_PATH = 'civicrm/event/register';

  /**
   * Prefix of the CiviCRM session cache key under which event registration controller persists its state.
   */
  const WIZARD_SESSION_KEY_PREFIX = 'CiviCRM_CRM_Event_Controller_Registration_';

  /**
   * Persist the qfKey to event id mapping used to recover the event id if the Thank You page is refreshed.
   */
  public static function storeEventId(string $qfKey, int $eventId): void {
    if (!is_string($qfKey) || $qfKey === '' || !$eventId) {
      return;
    }

    Civi::cache(self::CACHE_BUCKET)->set(self::cacheKey($qfKey), $eventId, self::getTtlSeconds());
  }

  /**
   * Symfony listener for "civi.invoke.auth".
   */
  public static function redirectIfNeeded(\Civi\Core\Event\GenericHookEvent $event) {
    if (!self::isThankYouRefreshWithoutId($event)) {
      return;
    }

    $qfKey = self::getRequestQfKey();
    if (!$qfKey) {
      return;
    }

    if (!self::isWizardSessionFlushed($qfKey)) {
      return;
    }

    $eventId = self::cacheGet($qfKey);
    if (!$eventId) {
      return;
    }

    // Guard against redirecting to an event that has since been deleted,
    // which would just bounce the user into a second error page.
    $eventExists = CRM_Core_DAO::getFieldValue('CRM_Event_DAO_Event', $eventId, 'id');
    if (!$eventExists) {
      self::cacheDelete($qfKey);
      return;
    }

    // We deliberately do NOT delete the mapping here: repeat refreshes /
    // back button presses within the TTL should all resolve to the same
    // event rather than silently failing after the first redirect. The
    // mapping expires naturally when the underlying session TTL lapses.

    $url = CRM_Utils_System::url(self::EVENT_REGISTER_PATH, ['id' => $eventId, 'reset' => 1], TRUE, NULL, FALSE);
    CRM_Utils_System::redirect($url);
  }

  /**
   * Is the current request a Thank You page display that has lost its event id?
   */
  private static function isThankYouRefreshWithoutId(\Civi\Core\Event\GenericHookEvent $event): bool {
    $args = (isset($event->args) && is_array($event->args)) ? $event->args : [];
    if (implode('/', $args) !== self::EVENT_REGISTER_PATH) {
      return FALSE;
    }

    if (empty($_REQUEST['_qf_ThankYou_display'])) {
      return FALSE;
    }

    // If an id is already in the request we leave it alone - either CiviCRM
    // can use it, or its own validation should surface a more precise error.
    if (!empty($_REQUEST['id'])) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Whether CiviCRM's own wizard session cache no longer holds an event id for the given qfKey.
   */
  private static function isWizardSessionFlushed(string $qfKey): bool {
    $sessionData = Civi::cache(self::CACHE_BUCKET)->get(self::WIZARD_SESSION_KEY_PREFIX . $qfKey);

    if (!is_array($sessionData)) {
      return TRUE;
    }

    return empty($sessionData['id']);
  }

  /**
   * Read the qfKey from the current request.
   */
  private static function getRequestQfKey(): ?string {
    $key = $_POST['qfKey'] ?? $_GET['qfKey'] ?? $_REQUEST['qfKey'] ?? NULL;
    return (is_string($key) && $key !== '') ? $key : NULL;
  }

  /**
   * Build a fully-prefixed cache key for a qfKey.
   */
  private static function cacheKey(string $qfKey): string {
    return self::CACHE_KEY_PREFIX . $qfKey;
  }

  /**
   * Look up a qfKey -> event id mapping.
   */
  private static function cacheGet(string $qfKey): ?int {
    $value = Civi::cache(self::CACHE_BUCKET)->get(self::cacheKey($qfKey));

    if (!is_numeric($value)) {
      return NULL;
    }
    $eventId = (int) $value;

    return $eventId > 0 ? $eventId : NULL;
  }

  /**
   * Remove a qfKey -> event id mapping.
   */
  private static function cacheDelete(string $qfKey): void {
    Civi::cache(self::CACHE_BUCKET)->delete(self::cacheKey($qfKey));
  }

  /**
   * TTL (seconds) for stored event ids. Mirrors CiviCRM's own wizard session cache TTL.
   */
  private static function getTtlSeconds(): int {
    $minutes = (int) Civi::settings()->get('secure_cache_timeout_minutes');
    if (!$minutes || $minutes <= 0) {
      return self::DEFAULT_TTL_SECONDS;
    }

    return $minutes * 60;
  }

}
