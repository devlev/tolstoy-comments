jQuery(document)
    .ready(($) => {
        let $link = $('.wp-menu-name [data-tolstoycomments]'),
            $block = $link.parents('.toplevel_page_tolstoycomments');

        if(!$link.length) {
            return false;
        }

        $block.find('.wp-first-item').remove();

        $block
            .on('click', 'a.toplevel_page_tolstoycomments', (e) => {
                e.preventDefault();

                let $this = $(e.currentTarget);

                window.open($this.attr('href'), '_blank');
            });

        $.get(ajaxurl + '?action=tolstoycomments_count', (response) => {
            if(response.success) {
                $link.text(response.data.value);
            }
        });
    });