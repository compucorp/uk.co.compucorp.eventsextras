<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for Event Fee BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventFee extends CRM_EventsExtras_Hook_BuildForm_BaseEvent {

  /**
  *
  * @param string $eventTab
  */
  public function __construct() {
    parent::__construct(SettingsManager::EVENT_FEE);
  }

  /**
   * Hides options on the Event Fee page
   *
   * @param string $formName
   * @param CRM_Event_Form_ManageEvent_Fee $form
   */
  public function handle($formName, &$form) {
    if (!$this->shouldHandle($formName, CRM_Event_Form_ManageEvent_Fee::class)) {
      return;
    }
    $this->hideField($form);
    $this->buildForm($formName, $form);
  }

  private function buildForm($formName, &$form) {
    $this->setDefaults($form);
   }

  private function setDefaults(&$form){
     $defaults = [];
     $paymentProcessor = SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION'];
     $paymentProcessorDefault = SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT'];
     $settings = [$paymentProcessor, $paymentProcessorDefault];
     $settingValues = SettingsManager::getSettingsValue($settings);
     if ($settingValues[$paymentProcessor] == 0){
      $defaultSettingString = implode(CRM_Core_DAO::VALUE_SEPARATOR, $settingValues[$paymentProcessorDefault]);
      $paymentProcessorDefaultValue = (array_fill_keys(explode(CRM_Core_DAO::VALUE_SEPARATOR, $defaultSettingString), '1'));
      $defaults['payment_processor'] = $paymentProcessorDefaultValue;
     }
     $form->setDefaults($defaults);
   }

}

