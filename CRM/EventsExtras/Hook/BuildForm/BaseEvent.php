<?php

use CRM_EventsExtras_ExtensionUtil as E;
use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Abstract class for BuildForm Hook
 */
abstract class CRM_EventsExtras_Hook_BuildForm_BaseEvent {

  /**
   * Event tab to display on the form 
   */
  protected $eventTab;

  /**
   * Constractor for BuildForm class
   * 
   * @param string $eventTab
   * 
   */
  protected function __construct($eventTab){
    $this->eventTab = $eventTab;
    $this->addEventTabTemplate();
  }

  /**
   * fuction to hide fields based on settings
   *
   * @param array $form
   *
   */
  protected function hideField(CRM_Event_Form_ManageEvent &$form){
    $configFields = SettingsManager::getConfigFields();
    $settingsValue = SettingsManager::getSettingsValue();
    $hiddenFields = [];
    foreach ($configFields as $config) {
     if ($settingsValue[$config['name']] == 0){
        if  ($config['extra_attributes']['section'] == $this->eventTab){
           $hiddenFields[] = $config['extra_attributes']['css_class'];
        }
      }
    }
    $form->assign('hiddenCssClasses', $hiddenFields);
  }

  private function addEventTabTemplate(){
    $templatePath = E::path() . '/templates/CRM/EventsExtras/Form/EventTabs.tpl';
    CRM_Core_Region::instance('page-body')->add([
      'template' => "{$templatePath}"
    ]);
  }
}