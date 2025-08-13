<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/accordion/view.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0.19
 */

namespace radiustheme\Evacon_Core;
use Elementor\Icons_Manager;


global $evacon_id;
$evacon_id = empty($evacon_id) ? 1 : $evacon_id + 1;
$accordian_id = 'rtaccordion-'.$evacon_id;
if ( $data['icon_display']  == 'yes' ) {
    $icon = $data['icon_position'];
} else {
    $icon = '';
}
?>
<div class="faq-box">
    <div class="panel-group" id="<?php echo esc_attr( $accordian_id ) ?>">
        <?php $i = 1;
            foreach ( $data['accordion_repeat'] as $accordion ) {
            $show =  $i == 1 ? 'show' : '';
            $t = $accordion['title'];
            $uid = strtolower(str_replace(array(':', '\\', '/', '*', '?', '.', ',', ';', ' '), '', $t));
        ?>
        <div class="panel panel-default">
            <div class="panel-heading" id="heading<?php echo esc_attr($uid); ?>">
                <button class="accordion-button collapsed <?php echo esc_attr( $icon ); ?>" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse<?php echo esc_attr($uid); ?>" aria-expanded="true" aria-controls="collapse<?php echo esc_attr($uid); ?>">
                    <?php echo wp_kses_post( $accordion['title'] ); ?>
                    <?php if ( $data['icon_display']  == 'yes' ) { ?>
                        <span class="rtin-accordion-icon">
                            <span class="rtin-icon rt-icon-closed">
                                <?php if( !empty($data['icon']) ) { ?>
                                    <?php Icons_Manager::render_icon( $data['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php } ?>
                            </span>
                        
                            <span class="rtin-icon rt-icon-opened">
                                <?php if( !empty($data['active_icon']) ) { ?>
                                    <?php Icons_Manager::render_icon( $data['active_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php } ?>
                            </span>
                        </span>
                    <?php } ?>
                </button>
            </div>
            <div id="collapse<?php echo esc_attr($uid); ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo esc_attr($uid); ?>"
                data-bs-parent="#<?php echo esc_attr( $accordian_id ) ?>">
                <div class="panel-body">
                    <?php echo wp_kses_post( $accordion['accodion_text'] ) ?>
                </div>
            </div>
        </div>
        <?php $i++; } ?>
    </div>
</div>