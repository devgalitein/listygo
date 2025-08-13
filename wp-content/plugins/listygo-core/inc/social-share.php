<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

$url   = urlencode( get_permalink() );
$title = urlencode( get_the_title() );

$defaults = array(
	'facebook' => array(
		'url'  => "http://www.facebook.com/sharer.php?u=$url",
		'icon' => 'fab fa-facebook-f',
		'class' => 'bg-fb'
	),
	'twitter'  => array(
		'url'  => "https://twitter.com/intent/tweet?source=$url&text=$title:$url",
		'icon' => 'fab fa-twitter',
		'class' => 'bg-twitter'
	),
	'linkedin' => array(
		'url'  => "http://www.linkedin.com/shareArticle?mini=true&url=$url&title=$title",
		'icon' => 'fab fa-linkedin-in',
		'class' => 'bg-linked'
	),
	'pinterest'=> array( 
		'url'  => "http://pinterest.com/pin/create/button/?url=$url&description=$title",
		'icon' => 'fab fa-pinterest-square',
		'class' => 'bg-pinterst'
	),
);

foreach ( $sharer as $key => $value ) {
	if ( !$value ) {
		unset( $defaults[$key] );
	}
}

$sharers = apply_filters( 'rdtheme_social_sharing_icons', $defaults );

?>
<div class="post-share-btn">
	<h5 class="item-label"><?php esc_html_e( 'Share:', 'listygo-core' );?></h5>
	<div class="post-social-sharing">
		<ul class="item-social">
			<?php foreach ( $sharers as $key => $sharer ): ?>
            <li>
            	<a href="<?php echo esc_url( $sharer['url'] );?>" class="<?php echo esc_attr( $sharer['class'] );?>">
            		<i class="<?php echo esc_attr( $sharer['icon'] );?>"></i>
            	</a>
            </li>
            <?php endforeach; ?>
        </ul>
	</div>
</div>