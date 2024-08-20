<?php

/**
 * Collection of upgrade steps.
 */
class CRM_EventsExtras_Upgrader extends CRM_Extension_Upgrader_Base {

  /**
   * Run an external SQL script when the module is uninstalled.
   */
  public function uninstall() {
    $this->executeSqlFile('sql/auto_uninstall.sql');
  }

}
