<?php

defined('ABSPATH') || exit();

/**
 * Plugin Name: Tolstoy Comments
 * Plugin URI: https://tolstoycomments.com/
 * Description: Replace the standard WordPress comment system on Tolstoy Comments.
 * Version: 2.0
 * Author: DEVLEVR
 * Author URI: https://tolstoycomments.com/
 * Text Domain: tolstoycomments
 * Domain Path: /languages
*/

define('TOLSTOYCOMMENTS_PATH', __DIR__);
define('TOLSTOYCOMMENTS_FILE', __FILE__);

require 'src/Admin.php';
require 'src/Export.php';
require 'src/Plugin.php';
require 'src/SettingsPage.php';
require 'src/SettingsFields.php';

use Gtxtymt\Plugins\Tolstoycomments\Admin;
use Gtxtymt\Plugins\Tolstoycomments\Export;
use Gtxtymt\Plugins\Tolstoycomments\Plugin;
use Gtxtymt\Plugins\Tolstoycomments\SettingsPage;

register_activation_hook(__FILE__, array(Plugin::class, 'activateHook'));
register_deactivation_hook(__FILE__, array(Plugin::class, 'deactivateHook'));

add_action('plugins_loaded', array(Plugin::class, 'textdomain'));
add_filter('plugin_action_links_'.plugin_basename(TOLSTOYCOMMENTS_FILE), array(Plugin::class, 'actionsLinks'));

add_action('admin_init', array(Plugin::class, 'redirectToPanel'));
add_action('admin_menu', array(Plugin::class, 'addPagesToAdminMenu'));

add_action('admin_enqueue_scripts', array(Admin::class, 'assets'));
add_action('wp_ajax_tolstoycomments_count', array(Admin::class, 'getCommentsCount'));
add_action('wp_ajax_tolstoycomments_export_start', array(Admin::class, 'startExportTask'));

add_action('admin_init', array(SettingsPage::class, 'fields'));

add_filter('comments_template', array(Plugin::class, 'commentsTemplate'));
add_filter('comments_number', array(Plugin::class, 'commentsCount'));
add_filter('wp_footer', array(Plugin::class, 'commentsMain'));

add_action('tolstoycomments_cron_task', array(Export::class, 'init'));
add_action('tolstoycomments_cron_task_queue', array(Export::class, 'init'));