<?php
/**
 * View switcher
 *
 * @version     1.5.5
 *
 * @var array  $views
 * @var string $current_view
 * @var string $default_view
 */

if (!defined('ABSPATH')) {
    exit;
}
if (empty($views)) {
    return;
}

use radiustheme\listygo\Helper;

?>
<div class="rtcl-view-switcher">
    <?php
    foreach ($views as $value => $label) {
        $active = $current_view === $value ? ' active' : '';
        $thIcon = $value === 'grid' ? "large" : $value;
        ?>
        <a class="rtcl-view-trigger<?php echo esc_attr($active); ?>" data-type="<?php echo esc_attr($value); ?>" href="<?php echo add_query_arg('view', $value) ?>">
            <img src="<?php echo Helper::get_img('theme/icon-'.$thIcon.'.svg'); ?>" alt="<?php esc_attr_e( 'Icon Image', 'listygo' ) ?>">
        </a>
    <?php } ?>
</div>
