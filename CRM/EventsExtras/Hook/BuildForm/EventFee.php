<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for Event Fee BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventFee extends CRM_EventsExtras_Hook_BuildForm_BaseEvent {

  /**
   * CRM_EventsExtras_Hook_BuildForm_EventFee constructor.
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
    $this->buildForm($formName, $form);
  }

  private function buildForm($formName, &$form) {
    $this->setDefaults($form);
  }

  private function setDefaults(&$form) {
    $defaults = [];
    $fieldIdsToHide = [];

    $showPaymentProcessor = SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION'];
    $paymentProcessorDefault = SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT'];
    $settings = [$showPaymentProcessor, $paymentProcessorDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showPaymentProcessor] == 0) {
      $defaultSettingString = implode(CRM_Core_DAO::VALUE_SEPARATOR, $settingValues[$paymentProcessorDefault]);
      $paymentProcessorDefaultValue = (array_fill_keys(explode(CRM_Core_DAO::VALUE_SEPARATOR, $defaultSettingString), '1'));
      $defaults['payment_processor'] = $paymentProcessorDefaultValue;
      $fieldIdsToHide[] = 'payment_processor';
    }

    $showCurrency = SettingsManager::SETTING_FIELDS['CURRENCY'];
    $currencyDefault = SettingsManager::SETTING_FIELDS['CURRENCY_DEFAULT'];
    $settings = [$showCurrency, $currencyDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showCurrency] == 0) {
      $defaults['currency'] = $settingValues[$currencyDefault];
      $fieldIdsToHide[] = 'currency';
    }

    $showPayLater = SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION'];
    $payLaterDefault = SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT'];
    $payLaterLabel = SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT_LABEL'];
    $payLaterInstruction = SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT_LABEL_INSTRUCTION'];
    $payLaterBillingAddress = SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION_DEFAULT_BILLING_ADDRESS'];
    $settings = [$showPayLater, $payLaterDefault, $payLaterLabel, $payLaterInstruction, $payLaterBillingAddress];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showPayLater] == 0) {
      $defaults['is_pay_later'] = $settingValues[$payLaterDefault];
      $defaults['pay_later_text'] = $settingValues[$payLaterLabel];
      $defaults['pay_later_receipt'] = $settingValues[$payLaterInstruction];
      $defaults['is_billing_required'] = $settingValues[$payLaterBillingAddress];
      $fieldIdsToHide[] = 'is_pay_later';

      $this->hideElements('#payLaterOptions');
    }

    $this->hideFields($fieldIdsToHide);
    $form->setDefaults($defaults);
  }

}
