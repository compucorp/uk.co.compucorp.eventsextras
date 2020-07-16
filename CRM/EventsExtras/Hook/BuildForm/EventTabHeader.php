<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for Event TabHeader
 */
class CRM_EventsExtras_Hook_BuildForm_EventTabHeader {

  /**
   * Hides Tabs based on settings
   *
   * @param string $formName
   * @param object $form
   */
  public function handle($formName, &$form) {
    if (!$this->shouldHandle($formName, $form)) {
      return;
    }
    $this->hideTab($form);
  }

  /**
   * Checks if the hook should be handled.
   *
   * @param string $formName
   * @param object $form
   *
   * @return bool
   */
  protected function shouldHandle($formName, &$form) {
    if ($form instanceof CRM_Event_Form_ManageEvent) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * This function is to hide Event tabs based on settings.
   *
   * @param object $form
   *
   */
  private function hideTab(&$form) {
    $vars = $form->get_template_vars('tabHeader');
    $locationTabSetting = SettingsManager::SETTING_FIELDS['LOCATION_TAB'];
    $pcpTabSetting = SettingsManager::SETTING_FIELDS['PCP_TAB'];
    $tellFriendTabSetting = SettingsManager::SETTING_FIELDS['TELL_FRIEND_TAB'];
    $settingsValue = SettingsManager::getSettingsValue([
      $locationTabSetting,
      $pcpTabSetting,
      $tellFriendTabSetting,
    ]);
    foreach ($settingsValue as $setting => $value) {
      if ($setting == $locationTabSetting && $value == 0) {
        unset($vars['location']);
      }
      if ($setting == $tellFriendTabSetting && $value == 0) {
        unset($vars['friend']);
      }
      if ($setting == $pcpTabSetting && $value == 0) {
        unset($vars['pcp']);
      }
    }
    $form->assign_by_ref('tabHeader', $vars);
  }

}
