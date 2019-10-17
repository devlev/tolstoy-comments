<?php

namespace Gtxtymt\Plugins\Tolstoycomments;

/**
 * Class Export
 * @package Gtxtymt\Plugins\Tolstoycomments
 */
class Export
{
	protected $apiKey;

	protected $siteId;

	/**
	 * Export constructor.
	 */
	public function __construct()
	{
		$this->apiKey = get_option('tolstoycomments_key');
		$this->siteId = get_option('tolstoycomments_site_id');
	}

	/**
	 * Initialize import process
	 * @return bool|Export
	 */
	public static function init()
	{
		$active = get_option('tolstoycomments_export');

		if($active != 1) {
			return false;
		}

		$lastId = get_option('tolstoycomments_export_latest_id');

		$export = new static();
		$export->handle($lastId);

		return $export;
	}

	/**
	 * Main method. Export comments from Tolstoycomments if process enabled
	 * @param $lastId
	 * @return bool
	 * @throws \Exception
	 */
	public function handle($lastId)
	{
		global $wpdb;

		if(!$this->apiKey || !$this->siteId) {
			$this->resetProcess();
			return false;
		}

		$lastId = is_numeric($lastId) && $lastId > 0 ? $lastId : '';

		$url = "https://api.tolstoycomments.com/api/export/$this->apiKey/site/$this->siteId/comment/$lastId";
		$result = file_get_contents($url);
		$result = json_decode($result);

		if(!is_object($result) || !isset($result->data) || count($result->data->comments) == 0) {
			$this->resetProcess();
			return false;
		}

		foreach($result->data->comments as $i) {
			preg_match('/^(http|https):\/\/(.*)\/?p=(\d+)$/', $i->chat->url, $m);

			if(!isset($m[3])) {
				continue;
			}

			$comment = [
				'comment_content' => $i->message,
				'comment_approved' => (int) $i->visible,
				'comment_author' => $i->user->name,
				'comment_author_email' => $i->user->email,
				'comment_author_IP' => $i->ip,
				'comment_date' => $i->datеtime,
				'comment_karma' => $i->rating,
				'comment_post_ID' => $m[3],
				'comment_subscribe' => 'N',
				'comment_parent' => 0
			];

			if($id = $wpdb->get_var($wpdb->prepare("select `comment_id` from $wpdb->commentmeta where `meta_key` = '_tolstoycomments_id' and `meta_value` = %d limit 1", $i->id))) {
				$comment['comment_ID'] = $id;
				wp_update_comment($comment);
				update_comment_meta($id, '_tolstoycomments_updated', time());
			}
			elseif($wpdb->get_var($wpdb->prepare("select 1 from $wpdb->posts where `ID` = %d limit 1", $m[3]))) {
				$id = wp_insert_comment(wp_slash($comment));

				if($id === false) {
					continue;
				}

				add_comment_meta($id, '_tolstoycomments_id', $i->id);
				add_comment_meta($id, '_tolstoycomments_updated', time());
			}
		}

		$this->cleanDeletedComments();
		is_numeric($result->data->comment_last_id) ? $this->setNextTask($result->data->comment_last_id) : $this->resetProcess();

		return true;
	}

	/**
	 * Delete deleted from Tolstoycomments comments in database
	 */
	private function cleanDeletedComments()
	{
		global $wpdb;

		$ids = $wpdb->get_col($wpdb->prepare(
			"select `comments`.`comment_ID` from $wpdb->comments as comments
			left join $wpdb->commentmeta as meta on (`comments`.`comment_ID` = `meta`.`comment_id`)
			where `meta`.`meta_key` = `_tolstoycomments_updated` and `meta`.`meta_value` is not null and `meta`.`meta_value` < %d
			limit 50",
			time() - 604800));

		foreach($ids as $i) {
			wp_delete_comment($i, true);
		}
	}

	/**
	 * Set next task
	 * @param int $latestId
	 */
	private function setNextTask(int $latestId)
	{
		update_option('tolstoycomments_export_latest_id', $latestId, false);

		wp_clear_scheduled_hook('tolstoycomments_cron_task_queue');
		wp_schedule_event(time() + 60 * 5, 'daily', 'tolstoycomments_cron_task_queue');
	}

	/**
	 * Reset task if get error or export ended
	 * @throws \Exception
	 */
	private function resetProcess()
	{
		delete_option('tolstoycomments_export_latest_id');

		wp_clear_scheduled_hook('tolstoycomments_cron_task_queue');
		wp_clear_scheduled_hook('tolstoycomments_cron_task');
		wp_schedule_event(Plugin::getTaskTime(), 'daily', 'tolstoycomments_cron_task');
	}
}