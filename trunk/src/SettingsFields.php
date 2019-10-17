<?php

namespace Gtxtymt\Plugins\Tolstoycomments;

/**
 * Class SettingsFields
 * @package Gtxtymt\Plugins\Tolstoycomments
 */
class SettingsFields
{
	public function siteId($value)
	{
		?>
		<input type="number" class="regular-text" name="tolstoycomments_site_id" value="<?=$value;?>" />
		<?php
	}

	public function active($value)
	{
		?>
		<input type="checkbox" name="tolstoycomments_active" value="1" <?=$value ? 'checked' : '';?> />
		<?php
	}

	public function binding($value)
	{
		?>
		<div>
			<label>
				<div style="width: 30px; float: left;">
					<input type="radio" name="tolstoycomments_binding" value="0" <?=!$value ? 'checked="checked"':'';?> />
				</div>
				<div style="margin-left: 30px;">
					<?=__('by URL', 'tolstoycomments');?><br/>
					<?=__('example:', 'tolstoycomments');?> <i><?=get_site_url()?>/your_url_post</i>
				</div>
			</label>
		</div>
		<div>
			<label>
				<div style="width: 30px; float: left;">
					<input type="radio" name="tolstoycomments_binding" value="1" <?=$value == 1 ? 'checked="checked"':'';?> />
				</div>
				<div style="margin-left: 30px;">
					<?=__('by Identity as post ID', 'tolstoycomments');?><br/>
					<?=__('example:', 'tolstoycomments');?> <i>123</i>
				</div>
			</label>
		</div>
		<div>
			<label>
				<div style="width: 30px; float: left;">
					<input type="radio" name="tolstoycomments_binding" value="2" <?=$value == 2 ? 'checked="checked"':'';?> />
				</div>
				<div style="margin-left: 30px;">
					<?=__('by Identity as short link', 'tolstoycomments');?><br/>
					<?=__('example:', 'tolstoycomments');?> <i><?=get_site_url()?>/?p=123</i>
				</div>
			</label>
		</div>
		<div>
			<label>
				<div style="width: 30px; float: left;">
					<input type="radio" name="tolstoycomments_binding" value="3" <?=$value == 3 ? 'checked="checked"':'';?> />
				</div>
				<div style="margin-left: 30px;">
					<?=__('by Identity as full link', 'tolstoycomments');?><br/>
					<?=__('example:', 'tolstoycomments');?> <i><?=get_site_url()?>/your_url_post</i>
				</div>
			</label>
		</div>
		<?php
	}

	public function key($value)
	{
		?>
		<input type="text" class="regular-text" name="tolstoycomments_key" value="<?=$value;?>" />
		<?php
	}

	public function export($value)
    {
	    ?>
        <input type="checkbox" name="tolstoycomments_export" value="1" <?=$value ? 'checked' : '';?> />
	    <?php
    }

	public function index($value)
	{
		?>
		<input type="checkbox" name="tolstoycomments_index" value="1" <?=$value ? 'checked' : '';?> />
		<?php
	}

	/**
	 * @param string $name
	 *
	 * @return mixed|void|null
	 */
	private function getField(string $name)
	{
		$value = get_option("tolstoycomments_$name");

		return $value ? $value : null;
	}
}