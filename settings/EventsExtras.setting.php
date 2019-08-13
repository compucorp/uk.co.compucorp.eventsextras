<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;
/*
* Settings metadata file
*/
return [
  'eventsextras_location_tab' => [
    'name' => 'eventsextras_location_tab',
    'title' => ts('Location Tab'),
    'type' => 'Boolean',
    'quick_form_type' => 'YesNo',
    'default' => TRUE,
    'html_type' => 'radio',
    'add' => '1.0',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => ts('Show/Hide Location Tab'),
    'help_text' => '',
    'extra_attributes' => [
     'section' => SettingsManager::EVENT_TAB,
     'css_class' => '',
   ],
  ],
  'eventsextras_tell_friend_tab' => [
    'name' => 'eventsextras_tell_friend_tab',
    'title' => ts('Tell Friend Tab'),
    'type' => 'Boolean',
    'quick_form_type' => 'YesNo',
    'default' => TRUE,
    'html_type' => 'radio',
    'add' => '1.0',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => ts('Show/Hide Tell Friend Tab'),
    'help_text' => '',
    'extra_attributes' => [
     'section' => SettingsManager::EVENT_TAB,
     'css_class' => '',
   ],
  ],
  'eventsextras_pcp_tab' => [
    'name' => 'eventsextras_pcp_tab',
    'title' => ts('Personal Compaings Tab'),
    'type' => 'Boolean',
    'quick_form_type' => 'YesNo',
    'default' => TRUE,
    'html_type' => 'radio',
    'add' => '1.0',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => ts('Show/Hide Personal Campaigns Tab'),
    'help_text' => '',
    'extra_attributes' => [
     'section' => SettingsManager::EVENT_TAB,
     'css_class' => '',
   ],
  ],
 'eventsextras_roles' => [
   'name' => 'eventsextras_roles',
   'title' => ts('Default Role'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Default Role'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_INFO,
    'css_class' => 'crm-event-manage-eventinfo-form-block-default_role_id',
  ],
 ],
 'eventsextras_participant_listing' => [
   'name' => 'eventsextras_participant_listing',
   'title' => ts('Participant listing'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Participant listing'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_INFO,
    'css_class' => 'crm-event-manage-eventinfo-form-block-participant_listing_id',
  ],
 ],
 'eventsextras_event_summary' => [
   'name' => 'eventsextras_event_summary',
   'title' => ts('Events Summary'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Event Summary'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_INFO,
    'css_class' => 'crm-event-manage-eventinfo-form-block-summary',
  ],
 ],
 'eventsextras_event_description' => [
   'name' => 'eventsextras_event_description',
   'title' => ts('Complete Description'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Complete Description'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_INFO,
    'css_class' => 'crm-event-manage-eventinfo-form-block-description',
  ],
 ],
 'eventsextras_include_map_event_location' => [
   'name' => 'eventsextras_include_map_event_location',
   'title' => ts('Include Map to Event Location'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Include Map to Event Location'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_INFO,
    'css_class' => 'crm-event-manage-eventinfo-form-block-is_map',
  ],
 ],
 'eventsextras_public_event' => [
   'name' => 'eventsextras_public_event',
   'title' => ts('Public Event'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Public Event'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_INFO,
    'css_class' => 'crm-event-manage-eventinfo-form-block-is_public',
  ],
 ],
 'eventsextras_currency' => [
   'name' => 'eventsextras_currency',
   'title' => ts('Currency'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Currency'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_FEE,
    'css_class' => 'crm-event-manage-fee-form-block-currency',
  ],
 ],
 'eventsextras_enable_pay_later_option' => [
   'name' => 'eventsextras_enable_pay_later_option',
   'title' => ts('Enable pay later option'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Enable pay later option'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_FEE,
    'css_class' => 'crm-event-manage-fee-form-block-is_pay_later',
  ],
 ],
 'eventsextras_payment_processor_selection' => [
   'name' => 'eventsextras_payment_processor_selection',
   'title' => ts('Payment Processor Selection'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Payment Processor Selection'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_FEE,
    'css_class' => 'crm-event-manage-fee-form-block-payment_processor',
  ],
 ],
 'eventsextras_pending_participant_expiration' => [
   'name' => 'eventsextras_pending_participant_expiration',
   'title' => ts('Pending Participant Expiration'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Pending Participant Expiration'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_REGISTRATION,
    'css_class' => 'crm-event-manage-registration-form-block-expiration_time',
  ],
 ],
 'eventsextras_allow_self_service' => [
   'name' => 'eventsextras_allow_self_service',
   'title' => ts('Allow Self Service'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Allow self-service cancellation or transfer?'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_REGISTRATION,
    'css_class' => 'crm-event-manage-registration-form-block-selfcancelxfer',
  ],
 ],
 'eventsextras_register_multiple_participants' => [
   'name' => 'eventsextras_register_multiple_participants',
   'title' => ts('Register Multiple Participants'),
   'type' => 'Boolean',
   'quick_form_type' => 'YesNo',
   'default' => TRUE,
   'html_type' => 'radio',
   'add' => '1.0',
   'is_domain' => 1,
   'is_contact' => 0,
   'description' => ts('Show/Hide Register multiple participants?'),
   'help_text' => '',
   'extra_attributes' => [
    'section' => SettingsManager::EVENT_REGISTRATION,
    'css_class' => 'crm-event-manage-registration-form-block-is_multiple_registrations',
  ],
 ],
];

