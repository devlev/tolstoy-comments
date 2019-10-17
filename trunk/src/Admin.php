<?php

namespace Gtxtymt\Plugins\Tolstoycomments;

/**
 * Class Admin
 * @package Gtxtymt\Plugins\Tolstoycomments
 */
class Admin
{
	/**
	 * Adding assets to admin panel
	 */
	public static function assets()
	{
		wp_enqueue_script(Plugin::$pluginSlug, plugin_dir_url(TOLSTOYCOMMENTS_FILE).'/assets/admin.js');
	}

	/**
	 * Getting unread comments count for display in admin menu
	 */
	public static function getCommentsCount()
	{
		$apiKey = get_option('tolstoycomments_key');
		$siteId = get_option('tolstoycomments_site_id');

		if(!$apiKey || !$siteId) {
			wp_send_json_error();
		}

		$url = "https://api.tolstoycomments.com/api/export/$apiKey/site/$siteId/notification";
		$response = file_get_contents($url);
		$response = json_decode($response);

		if(!is_object($response) || !isset($response->data->value)) {
			wp_send_json_error();
		}

		wp_send_json_success($response->data);
	}

	/**
	 * Starting export task without awaiting cron
	 */
	public static function startExportTask()
	{
		if(wp_next_scheduled('tolstoycomments_cron_task_queue')) {
			wp_die(__('The task is already running.', 'tolstoycomments'));
		}

		wp_schedule_event(time() + 10, 'daily', 'tolstoycomments_cron_task_queue');

		wp_redirect(admin_url('admin.php?page=tolstoycomments_settings'));
	}
}