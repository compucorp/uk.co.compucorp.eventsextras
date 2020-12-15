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
    'start_date'  => 20201201 ,
  ];

}
