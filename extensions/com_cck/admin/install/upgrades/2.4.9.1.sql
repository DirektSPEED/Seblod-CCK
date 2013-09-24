
ALTER TABLE `#__cck_core` ADD `store_id` INT( 11 ) NOT NULL AFTER `parent_id`;
ALTER TABLE `#__cck_core_types` ADD `alias` VARCHAR( 50 ) NOT NULL AFTER `name`;
ALTER TABLE `#__cck_core_searchs` ADD `alias` VARCHAR( 50 ) NOT NULL AFTER `name`;
ALTER TABLE `#__cck_core_searchs` ADD `storage_location` VARCHAR( 50 ) NOT NULL AFTER `options`;

INSERT IGNORE INTO `#__cck_core_fields` (`id`, `title`, `name`, `folder`, `type`, `description`, `published`, `label`, `selectlabel`, `display`, `required`, `validation`, `defaultvalue`, `options`, `options2`, `minlength`, `maxlength`, `size`, `cols`, `rows`, `ordering`, `sorting`, `divider`, `bool`, `location`, `extended`, `style`, `script`, `bool2`, `bool3`, `bool4`, `bool5`, `bool6`, `bool7`, `bool8`, `css`, `attributes`, `storage`, `storage_location`, `storage_table`, `storage_field`, `storage_field2`, `storage_params`, `storages`, `checked_out`, `checked_out_time`) VALUES
(282, 'Core Options Media Extensions', 'core_options_media_extensions', 3, '42', '', 0, '', ' ', 3, '', '', 'common', '', '{"preparecontent":"","prepareform":"$value  = ( $value != '''' ) ? $value : $field->defaultvalue;\\r\\nif ( $field->options ) {\\r\\n  $options = explode( ''||'', $field->options );\\r\\n} else {\\r\\n  $options = array( ''archive'', ''audio'', ''document'', ''image'', ''video'' );\\r\\n}\\r\\n$opts   = array();\\r\\n$opts[] = JHtml::_( ''select.option'', ''common'', JText::_ ( ''COM_CCK_MEDIA_TYPE_COMMON'' ), ''value'', ''text'' );\\r\\n$opts[]\\t= JHtml::_( ''select.option'', ''custom'', JText::_( ''COM_CCK_CUSTOM'' ) );\\r\\n$opts[]\\t= JHtml::_( ''select.option'', ''<OPTGROUP>'', JText::_( ''COM_CCK_MEDIA_TYPES'' ) );\\r\\nforeach ( $options AS $o ) {\\r\\n  $opts[] = JHtml::_( ''select.option'', $o, JText::_ ( ''COM_CCK_MEDIA_TYPE_''.$o ), ''value'', ''text'' );\\r\\n}\\r\\n$opts[] = JHtml::_( ''select.option'', ''<\\/OPTGROUP>'' );\\r\\n$opts[]\\t= JHtml::_( ''select.option'', ''<OPTGROUP>'', JText::_( ''COM_CCK_PRESETS'' ) );\\r\\nfor ( $i=1; $i <= 3; $i++ ) {\\r\\n  if ( JCck::getConfig_Param( ''media_preset''.$i.''_extensions'' ) ) {\\r\\n    $label  = JCck::getConfig_Param( ''media_preset''.$i.''_extensions_label'' );\\r\\n    $label  = $label ? $label : JText::_( ''COM_CCK_PRESET''.$i );\\r\\n    $opts[] = JHtml::_( ''select.option'', ''preset''.$i, $label );\\r\\n  }\\r\\n}\\r\\n$opts[] = JHtml::_( ''select.option'', ''<\\/OPTGROUP>'' );\\r\\n\\r\\n$form = JHtml::_( ''select.genericlist'', $opts, $name, ''class=\\"inputbox select\\" size=\\"1\\" ''.$field->attributes, ''value'', ''text'', $value, $id );","preparestore":""}', 0, 255, 32, 0, 0, 0, 0, '', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', 'style="width:90px;"', 'dev', '', '', 'json[options2][media_extensions]', '', '', '', 0, '0000-00-00 00:00:00'),
(283, 'Core Alias', 'core_alias', 3, 'text', '', 0, 'Alias Optional', ' ', 3, '', '', '', '', '', 0, 255, 28, 0, 0, 0, 0, '', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', 'dev', '', '', 'alias', '', '', '', 0, '0000-00-00 00:00:00'),
(448, 'FreeText Edit', 'freetext_edit', 3, 'freetext', '', 1, 'Edit', ' ', 3, '', '', '<p>\r\n  Edit</p>', '', '', 0, 255, 32, 0, 0, 0, 0, '', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', 'none', '', '', 'freetext_edit', '', '', '', 0, '0000-00-00 00:00:00'),
(449, 'FreeText Delete', 'freetext_delete', 3, 'freetext', '', 1, 'Delete', ' ', 3, '', '', '<p>\r\n Delete</p>', '', '', 0, 255, 32, 0, 0, 0, 0, '', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', 'none', '', '', 'freetext_delete', '', '', '', 0, '0000-00-00 00:00:00');

UPDATE `#__cck_core_fields` SET `selectlabel` = 'Use Global', `defaultvalue` = '', `options` = 'Joomla=optgroup||Use Native=0||SEBLOD=optgroup||SEF Mode Alias=23||SEF Mode Id=22||SEF Mode Id Alias=2||SEBLOD Advanced=optgroup||SEF Mode Parent Alias=43||SEF Mode Parent Id=42||SEF Mode Parent Id Alias=4||SEF Mode Type Alias=33||SEF Mode Type Id=32||SEF Mode Type Id Alias=3||SEBLOD Deprecated=optgroup||Optimized=1', `options2` = '{"options":[]}' WHERE `id` = 177;
UPDATE `#__cck_core_fields` SET `selectlabel` = 'Use Global', `defaultvalue` = '', `options` = 'Use Native=-1||5||10||15||20||25||30||50||100||All=0', `options2` = '{"options":[]}' WHERE `id` = 172;
UPDATE `#__cck_core_fields` SET `selectlabel` = 'Use Global', `defaultvalue` = '', `options` = 'No=0||Yes=optgroup||Yes for Everyone=1||Yes for Super Admin=2', `options2` = '{"options":[]}' WHERE `id` = 174;
UPDATE `#__cck_core_fields` SET `selectlabel` = 'Use Global', `defaultvalue` = '', `options2` = '{"options":[]}' WHERE `id` = 226;
UPDATE `#__cck_core_fields` SET `css` = 'btn-group', `storage_field` = 'json[configuration][offline]' WHERE `id` = 207;