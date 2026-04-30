<?php defined('ABSPATH') or die(); ?>
<?php if (empty($post) || $post->post_status !== 'publish') return; ?>

<p style="display: flex; align-items: center; margin-top: 12px; margin-bottom: 0;">
    <input readonly type="text" id="empl-copy-shortcode" value='[empl_map id="<?= $post->ID; ?>"]'> <button style="margin-left: 5px;" type="button" class="button" id="empl-copy-btn">Copy</button>
</p>
<script>
    let copyText = document.querySelector('#empl-copy-shortcode');
    let copyBtn = document.querySelector('#empl-copy-btn');
    copyBtn.addEventListener('click', function(e) {
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        if (navigator.clipboard) {
            navigator.clipboard.writeText(copyText.value);
        }
        copyText.setSelectionRange(0, 0);
    });
</script>