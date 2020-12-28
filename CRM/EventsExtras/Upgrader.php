<?php

/**
 * Collection of upgrade steps.
 */
class CRM_EventsExtras_Upgrader extends CRM_EventsExtras_Upgrader_Base {

  /**
   * Run an external SQL script when the module is uninstalled.
   */
  public function uninstall() {
    $this->executeSqlFile('sql/auto_uninstall.sql');
  }

  public function upgrade_0001() {
    $this->resetMenuCache();

    return TRUE;
  }

  /**
   * After modifying the navigation menu we should reset the menu cache
   */
  private function resetMenuCache() {
    $params = [];
    civicrm_api3('System', 'flush', $params);
  }

}
