<?php

require_once 'eventsextras.civix.php';
use CRM_EventsExtras_ExtensionUtil as E;

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link hhttps://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function eventsextras_civicrm_buildForm($formName, &$form) {
  $listeners = [
    new CRM_EventsExtras_Hook_BuildForm_EventTabHeader(),
    new CRM_EventsExtras_Hook_BuildForm_EventInfo(),
    new CRM_EventsExtras_Hook_BuildForm_EventFee(),
    new CRM_EventsExtras_Hook_BuildForm_EventRegistration(),
  ];
  foreach ($listeners as $currentListener) {
    $currentListener->handle($formName, $form);
  }
}

/**
 * Implements hook_civicrm_pre().
 *
 * @link hhttps://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pre/
 */
function eventsextras_civicrm_pre($op, $objectName, $id, &$params) {
  $listeners = [
    new CRM_EventsExtras_Hook_Pre_ManageEvent(),
  ];
  foreach ($listeners as $currentListener) {
    $currentListener->handle($op, $objectName, $id, $params);
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function eventsextras_civicrm_config(&$config) {
  _eventsextras_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function eventsextras_civicrm_xmlMenu(&$files) {
  _eventsextras_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function eventsextras_civicrm_install() {
  _eventsextras_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function eventsextras_civicrm_postInstall() {
  _eventsextras_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function eventsextras_civicrm_uninstall() {
  _eventsextras_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function eventsextras_civicrm_enable() {
  _eventsextras_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function eventsextras_civicrm_disable() {
  _eventsextras_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function eventsextras_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _eventsextras_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function eventsextras_civicrm_managed(&$entities) {
  _eventsextras_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function eventsextras_civicrm_caseTypes(&$caseTypes) {
  _eventsextras_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function eventsextras_civicrm_angularModules(&$angularModules) {
  _eventsextras_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function eventsextras_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _eventsextras_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function eventsextras_civicrm_entityTypes(&$entityTypes) {
  _eventsextras_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function eventsextras_civicrm_navigationMenu(&$menu) {
  _eventsextras_civix_insert_navigation_menu($menu, 'Administer/', array(
    'label' => E::ts('Events Extras Settings'),
    'name' => 'events_extras_settings',
    'url' => 'civicrm/admin/setting/preferences/eventsextras',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _eventsextras_civix_navigationMenu($menu);
}
