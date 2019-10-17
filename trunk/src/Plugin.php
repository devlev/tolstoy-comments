<?php

namespace Gtxtymt\Plugins\Tolstoycomments;

/**
 * Class Plugin
 * @package Gtxtymt\Plugins\Tolstoycomments
 */
class Plugin
{
	public static $pluginName = 'Tolstoy Comments';

	public static $pluginShortName = 'Tolstoy';

	public static $pluginSlug = 'tolstoycomments';

	public static $pluginUrl = 'https://tolstoycomments.com';

	/**
	 * On activate plugin hook
	 * @throws \Exception
	 */
	public static function activateHook()
	{
		wp_schedule_event(static::getTaskTime(), 'daily', 'tolstoycomments_cron_task');
	}

	/**
	 * On deactivate plugin hook
	 */
	public static function deactivateHook()
	{
		delete_option('tolstoycomments_active');
		delete_option('tolstoycomments_site_id');
		delete_option('tolstoycomments_key');
		delete_option('tolstoycomments_export');
		delete_option('tolstoycomments_binding');
		delete_option('tolstoycomments_index');
		delete_option('tolstoycomments_custom_settings');
		delete_option('tolstoycomments_export_latest_id');

		wp_clear_scheduled_hook('tolstoycomments_cron_task');
		wp_clear_scheduled_hook('tolstoycomments_cron_task_queue');
	}

	/**
	 * Load localization files
	 */
	public static function textdomain()
	{
		load_plugin_textdomain(static::$pluginSlug, false, static::$pluginSlug.'/languages/');
	}

	/**
	 * Redirect to Tolstoycomments from admin menu
	 */
	public static function redirectToPanel()
	{
		global $pagenow;

		if($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == static::$pluginSlug) {
			wp_redirect(static::$pluginUrl.'/admin');
			exit();
		}
	}

	/**
	 * Create pages in admin panel
	 */
	public static function addPagesToAdminMenu()
	{
		add_menu_page(static::$pluginName, sprintf('%s <span class="awaiting-mod" data-tolstoycomments>0</span>', static::$pluginShortName), 'moderate_comments', static::$pluginSlug, function() {}, 'dashicons-admin-comments', 76);
		add_submenu_page(self::$pluginSlug, self::$pluginName, __('Settings', 'tolstoycomments'), 'moderate_comments', self::$pluginSlug.'_settings', [SettingsPage::class, 'show']);
	}

	/**
	 * Change default comments view
	 * @param $template
	 * @return string
	 */
	public static function commentsTemplate($template)
	{
		if(get_option('tolstoycomments_active') == 1) {
			if(get_option('tolstoycomments_index') == 1 && file_exists($template)) {
				global $wp_query, $withcomments, $post, $wpdb, $id, $comment, $user_login, $user_ID, $user_identity;
				$comments_by_type = $wp_query->comments_by_type;

				echo '<div style="display:none;">';
				require $template;
				echo '</div>';
			}

			return TOLSTOYCOMMENTS_PATH.'/templates/comments.php';
		}

		return $template;
	}

	
	public static function commentsCount($comment_text)
	{
		if(get_option('tolstoycomments_active') == 1) {
			switch(get_option('tolstoycomments_binding')){
				case 1:
					return sprintf('<span class="tolstoycomments-cc" data-identity="%d"></span>', get_the_ID());
				case 2:
					return sprintf('<span class="tolstoycomments-cc" data-identity="%s"></span>', wp_get_shortlink());
				case 3:
					return sprintf('<span class="tolstoycomments-cc" data-identity="%s"></span>', get_the_permalink());
				default:
					return sprintf('<span class="tolstoycomments-cc" data-url="%s"></span>', get_the_permalink());
			}
		}else{
			return $comment_text;
		}
	}

	public static function commentsMain()
	{
		if(get_option('tolstoycomments_active') == 1) {
			require TOLSTOYCOMMENTS_PATH.'/templates/commentsMain.php';
		}
	}

	/**
	 * Add links to plugins list page
	 * @param array $links
	 * @return array
	 */
	public static function actionsLinks(array $links)
	{
		array_unshift($links, sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=tolstoycomments_settings'), __('Settings', 'tolstoycomments')));

		return $links;
	}

	/**
	 * Get timestamp for cron task (4:00 AM next day)
	 * @return int
	 * @throws \Exception
	 */
	public static function getTaskTime()
	{
		$dateTime = new \DateTime();
		$dateTime->setTimestamp(current_time('timestamp'));
		$dateTime->setTime(4, 0);
		$dateTime->add(new \DateInterval('P1D'));

		return $dateTime->getTimestamp();
	}
}