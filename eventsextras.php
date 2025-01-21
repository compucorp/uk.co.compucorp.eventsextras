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
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function eventsextras_civicrm_install() {
  _eventsextras_civix_civicrm_install();
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
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function eventsextras_civicrm_navigationMenu(&$menu) {
  _eventsextras_civix_insert_navigation_menu($menu, 'Administer/CiviEvent', array(
    'label' => E::ts('Events Extras Settings'),
    'name' => 'events_extras_settings',
    'url' => 'civicrm/admin/setting/preferences/eventsextras',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _eventsextras_civix_navigationMenu($menu);
}

/**
 * Implements hook_civicrm_alterMailParams().
 */
function eventsextras_civicrm_alterMailParams(&$params, $context) {
  $hooks = [CRM_EventsExtras_Hook_AlterMailParams_EventRegistrationConfirmation::class];

  foreach ($hooks as $hook) {
    if ($hook::shouldHandle($params, $context)) {
      (new $hook())->handle($params, $context);
    }
  }
}
