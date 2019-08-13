<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for Event TapHeader
 */
class CRM_EventsExtras_Hook_BuildForm_TabHeader {

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

  private function hideTab(&$form){
    $vars = $form->get_template_vars('tabHeader');

    $locationTabSetting = SettingsManager::getSettingValue(SettingsManager::SETTING_FIELDS['LOCATION_TAB']);
    $tellFriendTabSetting = SettingsManager::getSettingValue(SettingsManager::SETTING_FIELDS['TELL_FRIEND_TAB']);
    $pcpTabSetting = SettingsManager::getSettingValue(SettingsManager::SETTING_FIELDS['PCP_TAB']);
    if (reset($locationTabSetting) == 0){
      unset($vars['location']);
    }
    if (reset($tellFriendTabSetting) == 0){
      unset($vars['friend']);
    }
    if (reset($pcpTabSetting) == 0){
      unset($vars['pcp']);
    }
    $form->assign_by_ref('tabHeader', $vars);
  }
}
