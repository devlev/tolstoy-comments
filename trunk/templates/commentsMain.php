<?php
defined('ABSPATH') || exit();

?>
<!-- Tolstoy Comments Init -->
<script type="text/javascript">!(function(w,d,s,l,x){w[l]=w[l]||[];w[l].t=w[l].t||new Date().getTime();var f=d.getElementsByTagName(s)[0],j=d.createElement(s);j.async=!0;j.src='//web.tolstoycomments.com/sitejs/app.js?i='+l+'&x='+x+'&t='+w[l].t;f.parentNode.insertBefore(j,f);})(window,document,'script','tolstoycomments','<?=get_option('tolstoycomments_site_id');?>');</script>
<!-- /Tolstoy Comments Init -->

<!-- Tolstoy Comments Widget -->
<div class="tolstoycomments-feed"></div>
<script type="text/javascript">
	window['tolstoycomments'] = window['tolstoycomments'] || [];
	window['tolstoycomments'].push({
		action: 'init',
		values: {
			<? 
				switch(get_option('tolstoycomments_binding')){
					case 1:
						echo "identity: '".get_the_ID()."',\n";
						break;
					case 2:
						echo "identity: '".wp_get_shortlink()."',\n";
						break;
					case 3:
						echo "identity: '".get_the_permalink()."',\n";
						break;
				}
			?>
			url: '<?=get_the_permalink();?>',
			title: '<?php the_title(); ?>',
			desktop_class: 'tolstoycomments-feed',
			comment_class: 'tolstoycomments-cc',
			comment_render: function (val) {
				this.innerHTML = '<i class="fa fa-comment"></i> ' + val + " комментари" + GetNumEnding(val, "й", "я", "ев");
			}
		}
	});
	var GetNumEnding = function (n, a, b, c) {
	    var v = parseInt(n % 100);
	    if (v >= 11 && v <= 19) {
	        return c;
	    } else {
	        v = parseInt(n % 10);
	        if (v == 1) {
	            return a;
	        } else {
	            if (v >= 2 && v <= 4) {
	                return b;
	            } else {
	                return c;
	            }
	        }
	    }
	}
</script>
<!-- /Tolstoy Comments Widget -->