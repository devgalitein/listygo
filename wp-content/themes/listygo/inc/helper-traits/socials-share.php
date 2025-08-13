<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTheme;

trait SocialShares {

  public static $sharers = [];
  public static $defaults = [];

  /**
   * generate all social share options
   * @return [type] [description]
   */
  public static function generate_defults() {
    $url   = urlencode( get_permalink() );
    $title = urlencode( get_the_title() );
    $defaults = [
      'facebook' => [
        'url'  => "http://www.facebook.com/sharer.php?u=$url",
        'icon' => 'fab fa-facebook-f',
      ],
      'twitter'  => [
        'url'  => "https://twitter.com/intent/tweet?source=$url&text=$title:$url",
        'icon' => 'fab fa-twitter',
      ],
      'pinterest'=> [
        'url'  => "http://pinterest.com/pin/create/button/?url=$url&description=$title",
        'icon' => 'fab fa-pinterest-p',
        'class' => 'pinterest',

      ],
      'linkedin' => [
        'url'  => "http://www.linkedin.com/shareArticle?mini=true&url=$url&title=$title",
        'icon' => 'fab fa-linkedin-in',
        'class' => 'linkedin',
      ],
    ];
    self::$defaults = $defaults;
  }
  

  public static function filter_defaults(){
    foreach ( self::$defaults as $key => $value ) {
      if ( !$value ) {
        unset( $defaults[$key] );
      }
    }
    self::$sharers = apply_filters( 'rdtheme_social_sharing_icons', self::$defaults );
  }

  public static function render(){
    self::generate_defults();
    self::filter_defaults();
  ?> 
    <ul class="inner-share">
      <?php foreach ( self::$sharers as $key => $sharer ): 
        $icon = $sharer['icon'];
        $a = explode(' ',$icon);
        $b = explode('-',$icon);  
      ?>
        <li class="<?php echo esc_attr( $b[1] ); ?>">
            <a href="<?php echo esc_attr( $sharer['url'] ); ?>">
                <i class="<?php echo esc_attr( $sharer['icon'] );?>"></i>
            </a>
        </li>
      <?php endforeach ?>
    </ul>
    <?php
  }
}




