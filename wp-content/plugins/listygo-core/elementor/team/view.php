<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/price/view.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use Elementor\Icons_Manager;
extract( $data );

?>

<div class="team-block">
    <div class="team-block__image">
        <?php if(!empty($data['name_link'])){ ?>
            <a href="<?php echo esc_url( $name_link ); ?>">
                <?php echo wp_get_attachment_image( $picture['id'], 'full' ); ?>
            </a>
        <?php } else {
            echo wp_get_attachment_image( $picture['id'], 'full' );
        } ?>
    </div>
    <div class="team-block__content">
        <h3 class="team-block__heading">
            <?php if(!empty($data['name_link'])){ ?>
            <a href="<?php echo esc_url( $name_link ); ?>"><?php echo esc_html( $name ); ?></a>
            <?php } else {
                echo esc_html( $name );
            } ?>
        </h3>
        <span class="team-block__title"><?php echo esc_html( $designation ); ?></span>
        <?php if(is_array($data['social_list'])){ ?>
            <div class="team-block__social social">
                <ul>
                    <?php foreach ( $data['social_list'] as $value ) { 
                        $string = $value['icon']['value'];
                        $iconClass = '';
                        $class = explode(' ', $string);
                        if(isset($class[1])) {
                            $mainClass = explode('-', $class[1]);
                            if(isset($mainClass[1])) {
                                $iconClass = $mainClass[1];
                            }
                        }
                    ?>
                    <li class="header-top__social_list">
                        <a class="<?php echo esc_attr( $iconClass ); ?>" href="<?php echo esc_url( $value['link'] ); ?>">
                            <?php Icons_Manager::render_icon( $value['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>