<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
$previous = get_previous_post();
$next = get_next_post();
if ( $previous || $next ):
?>
<ul class="post-view">
    <?php if ( $previous ): ?>
    <li><a href="<?php echo esc_url( get_permalink( $previous ) ); ?>"><i class="flaticon-back"></i><?php esc_html_e( 'Previous Article', 'listygo' ); ?></a></li>
    <?php endif; if ( $next ): ?>
    <li><a href="<?php echo esc_url( get_permalink( $next ) ); ?>"><?php esc_html_e( 'Next Article', 'listygo' ); ?><i class="flaticon-right-arrow"></i></a></li>
    <?php endif; ?>
</ul>
<?php endif; ?>