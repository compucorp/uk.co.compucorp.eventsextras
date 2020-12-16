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
    'event_type_id'  => 3 ,
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
    // start_date should always be the future
    $start_date = new DateTime('tomorrow');

    $defaultParams = array_merge(static::$defaultParams, [
      'start_date' => $start_date->format('Ymd'),
    ]);
    $params = array_merge($defaultParams, $params);

    return parent::fabricate($params);
  }

}
