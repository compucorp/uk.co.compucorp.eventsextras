<?php
use CRM_EventsExtras_Test_Fabricator_Base as BaseFabricator;

/**
 * Class CRM_EventsExtras_Test_Fabricator_Event.
 */
class CRM_EventsExtras_Test_Fabricator_Event extends BaseFabricator {

  /**
   * Entity's name.
   *
   * @var string
   */
  protected static $entityName = 'Event';

  /**
   * Array if default parameters to be used to create an event.
   *
   * @var array
   */
  protected static $defaultParams = [
    'title'  => 'Event Sample' ,
  ];

  /**
   * Fabricates an event  with the given parameters.
   *
   * @param array $params
   *
   * @return array
   * @throws \CiviCRM_API3_Exception
   */
  public static function fabricate(array $params = []) {
    $startDate = new DateTime();

    $eventType = self::createEventType();
    $eventTypeId = $eventType['value'];

    $defaultParams = array_merge(static::$defaultParams, [
      'start_date' => $startDate->format('Ymd'),
      'event_type_id'  => $eventTypeId,
    ]);

    $params = array_merge($defaultParams, $params);

    return parent::fabricate($params);
  }

  private static function createEventType() {
    $result = civicrm_api3('OptionValue', 'create', [
      'option_group_id' => 'event_type',
      'name' => 'Conference',
    ]);
    $eventType = array_shift($result['values']);

    return $eventType;
  }

}
