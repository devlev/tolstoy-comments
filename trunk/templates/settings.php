<?php
defined('ABSPATH') || exit();

use Gtxtymt\Plugins\Tolstoycomments\Plugin;
?>
<div class="wrap">
	<h2><?=Plugin::$pluginName;?></h2>

	<form action="<?=admin_url('options.php');?>" method="post">
		<?php
		settings_fields(Plugin::$pluginSlug.'_group');
		do_settings_sections(Plugin::$pluginSlug.'_section');
		?>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?=__('Save changes', 'tolstoycomments');?>"> <a href="<?=admin_url('admin-ajax.php');?>?action=tolstoycomments_export_start" class="button button-secondary"><?=__('Start export now', 'tolstoycomments');?></a>
	</form>
</div>
