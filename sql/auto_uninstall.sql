-- /*******************************************************
-- * Delete event extras settings
-- *******************************************************/


DELETE FROM civicrm_setting WHERE `name` LIKE 'eventsextras_%';
