<?php

/**
 * Alter mail params hook for event registration confirmation.
 */
class CRM_EventsExtras_Hook_AlterMailParams_EventRegistrationConfirmation {

  public static function shouldHandle(array $params, $context): bool {
    return !empty($params['tplParams']['event']) && !empty($params['tplParams']['custom_pre_id']) && !empty($params['tplParams']['contactID'])
      && $context === 'messageTemplate' && isset($params['tplParams']['customPre'][0]) && count(array_filter($params['tplParams']['customPre'][0])) === 0;
  }

  public function handle(array &$params): void {
    $fields = CRM_Core_BAO_UFGroup::getFields($params['tplParams']['custom_pre_id'], FALSE, CRM_Core_Action::VIEW,
      NULL, NULL, FALSE, NULL,
      FALSE, NULL, CRM_Core_Permission::CREATE,
      'field_name', TRUE
    );

    $values = [];
    CRM_Core_BAO_UFGroup::getValues($params['tplParams']['contactID'], $fields, $values, FALSE);

    $params['tplParams']['customPre'][0] = $values;
  }

}
