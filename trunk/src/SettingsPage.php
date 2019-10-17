<?php

namespace Gtxtymt\Plugins\Tolstoycomments;

/**
 * Class SettingsPage
 * @package Gtxtymt\Plugins\Tolstoycomments
 */
class SettingsPage
{
	public static function show()
	{
		include TOLSTOYCOMMENTS_PATH.'/templates/settings.php';
	}

	public static function getField($name)
	{
		$value = get_option("tolstoycomments_$name");

		return $value ? $value : null;
	}

	public static function fields()
	{
		$fields = new SettingsFields();

		$group = Plugin::$pluginSlug.'_group';
		$section = Plugin::$pluginSlug.'_section';

		$sectionCode = Plugin::$pluginSlug.'_section_code';
		$sectionExport = Plugin::$pluginSlug.'_section_export';
		$sectionAdvanced = Plugin::$pluginSlug.'_section_advanced';

		register_setting($group, 'tolstoycomments');
		register_setting($group, 'tolstoycomments_site_id');
		register_setting($group, 'tolstoycomments_active');
		register_setting($group, 'tolstoycomments_binding');
		register_setting($group, 'tolstoycomments_key');
		register_setting($group, 'tolstoycomments_export');
		register_setting($group, 'tolstoycomments_index');
		// register_setting($group, 'tolstoycomments_custom_settings');

		add_settings_section($sectionCode, __('Insert code', 'tolstoycomments'), '', $section);

		add_settings_field('tolstoycomments_site_id', __('Site ID', 'tolstoycomments'), array($fields, 'siteId'), $section, $sectionCode, self::getField('site_id'));
		add_settings_field('tolstoycomments_active', __('Active', 'tolstoycomments'), array($fields, 'active'), $section, $sectionCode, self::getField('active'));
		add_settings_field('tolstoycomments_binding', __('Binding', 'tolstoycomments'), array($fields, 'binding'), $section, $sectionCode, self::getField('binding'));

		add_settings_section($sectionExport, __('Export', 'tolstoycomments'), '', $section);

		add_settings_field('tolstoycomments_key', __('API key', 'tolstoycomments'), array($fields, 'key'), $section, $sectionExport, self::getField('key'));
		add_settings_field('tolstoycomments_export', __('Enable export', 'tolstoycomments'), array($fields, 'export'), $section, $sectionExport, self::getField('export'));
		add_settings_field('tolstoycomments_index', __('Enable indexing', 'tolstoycomments'), array($fields, 'index'), $section, $sectionExport, self::getField('index'));

		// add_settings_section($sectionAdvanced, __('Advanced insert settings', 'tolstoycomments'), '', $section);

		// add_settings_field('tolstoycomments_custom_settings', __('Config', 'tolstoycomments'), array($fields, 'config'), $section, $sectionAdvanced, self::getField('custom_settings'));
	}
}