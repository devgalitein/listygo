<?php
/**
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\Listygo_Core;
use radiustheme\listygo\Helper;
extract( $data );
$align = $data['align'];
$heading_tag_html = sprintf( '<%1$s %2$s class="heading-title">%3$s</%1$s>', $data['heading_tag'], $this->get_render_attribute_string( 'title' ), $data['title'] );

$attr = '';
if ( !empty( $url['url'] ) ) {
	$attr  = 'href="' . $data['url']['url'] . '"';
	$attr .= !empty( $data['url']['is_external'] ) ? ' target="_blank"' : '';
	$attr .= !empty( $data['url']['nofollow'] ) ? ' rel="nofollow"' : '';
}

?>
<div class="section-heading">
	<?php if ( !empty( $data['subtitle'] ) ){ ?>
        <div class="heading-subtitle"><?php echo $data['subtitle']; ?></div>
    <?php } echo $heading_tag_html; 
        if ($line_enable) {
    ?>
        <div class="sce-head-icon"><?php echo Helper::line_icon(); ?></div>
    <?php } echo $data['desc']; ?>
    <?php if ( !empty( $url['url'] ) ) { ?>
        <div class="btn-wrap btn-v2">
            <a <?php echo $attr; ?> class="item-btn">
                <span class="btn__icon">
                    <?php echo Helper::btn_right_icon(); ?>
                </span>
                <?php echo esc_html( $data['btntext'] ); ?>
                <span class="btn__icon">
                    <?php echo Helper::btn_right_icon(); ?>
                </span>
            </a>
        </div>
	<?php } ?>
</div>