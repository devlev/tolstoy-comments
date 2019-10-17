<?php
defined('ABSPATH') || exit();

if(!comments_open()) {
    return;
}

?>
<!-- Tolstoy Comments Widget -->
<div class="tolstoycomments-feed"></div>
<script type="text/javascript">
	window['tolstoycomments'] = window['tolstoycomments'] || [];
	window['tolstoycomments'].push({
		action: 'init',
		values: {
			visible: true
		}
	});
</script>
<!-- /Tolstoy Comments Widget -->