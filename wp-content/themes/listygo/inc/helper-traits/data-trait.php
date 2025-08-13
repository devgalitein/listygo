<?php
namespace radiustheme\listygo;
use Rtrs\Models\Review;
use Rtcl\Helpers\Functions;
use radiustheme\listygo\RDTListygo;
trait DataTrait {

  public static function listygo_get_post_meta( $post_id, $padmin, $pdate, $pcom, $pcats ) { 
      $post_meta_holder= "";

      $comments_number = get_comments_number($post_id);
      $comments_text   = sprintf( _n( '%s Comment', '%s Comments', $comments_number, 'listygo' ), number_format_i18n( $comments_number ) );

      $post_meta  = $padmin || $pdate || $pcom || $pcats ? true : false;

      if ( $post_meta ){ ?>
      <ul class="entry-meta"> 
        <?php if ( $padmin ){ ?>     
        <li class="entry-admin">
          <span class="meta-icon"><?php echo get_avatar( get_the_author_meta( 'ID' ), 25 ); ?></span>
          <span><?php esc_html_e( 'by', 'listygo' ); ?></span>
          <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
        </li>
        <?php } if ( $pdate ){ ?>
        <li class="entry-date">
          <span class="meta-icon"><i class="far fa-clock"></i></span><?php the_time( get_option( 'date_format' ) ); ?>
        </li>
        <?php } if ( $pcom ){ ?> 
        <li class="entry-comments">
          <span class="meta-icon"><i class="far fa-comments"></i></span><?php echo wp_kses_stripslashes( $comments_text ); ?>
        </li>
        <?php } if ( $pcats && has_category() ){ ?> 
        <li class="entry-cats"><span class="meta-icon"><i class="fas fa-tags"></i></span><?php the_category( ', ' ); ?></li>
        <?php } ?>
      </ul>
    <?php }
    return $post_meta_holder;
  }

  public static function listygo_get_attach_img( $img_id, $size ) {
    $attach_img = '';
    if (!empty( $img_id )) {
      $attach_img = wp_get_attachment_image( $img_id, $size );
    } else {
      $attach_img = '';
    }
    return $attach_img;
  }

  public static function socials(){
    $rdtheme_socials = array(
      'rt_facebook' => array(
        'class' => 'facebook',
        'icon' => 'fa-brands fa-facebook-f',
        'url'  => RDTListygo::$options['rt_facebook'],
      ),
      'rt_twitter' => array(
        'class' => 'twitter',
        'icon' => 'fa-brands fa-x-twitter',
        'url'  => RDTListygo::$options['rt_twitter'],
      ),
      'rt_linkedin' => array(
        'class' => 'linkedin',
        'icon' => 'fa-brands fa-linkedin-in',
        'url'  => RDTListygo::$options['rt_linkedin'],
      ),
      'rt_instagram' => array(
        'class' => 'instagram',
        'icon' => 'fa-brands fa-instagram',
        'url'  => RDTListygo::$options['rt_instagram'],
      ),
      'rt_pinterest' => array(
        'class' => 'pinterest',
        'icon' => 'fa-brands fa-pinterest-p',
        'url'  => RDTListygo::$options['rt_pinterest'],
      ),
      'rt_youtube' => array(
        'class' => 'youtube',
        'icon' => 'fa-brands fa-youtube',
        'url'  => RDTListygo::$options['rt_youtube'],
      ),
      'rt_tiktok' => array(
        'class' => 'tiktok',
        'icon' => 'fa-brands fa-tiktok',
        'url'  => RDTListygo::$options['rt_tiktok'],
      ),
    );
    return array_filter( $rdtheme_socials, array( __CLASS__ , 'filter_social' ) );
  }

  public static function filter_social( $args ){
    return ( $args['url'] != '' );
  }

  public static function cirkle_assets_imgs( $url ){
    $url = LISTYGO_THEME_ASSETS_URL.$url;
    return $url;
  }

  public static function rt_rating( $count ){ ?>
    <div class="item-rating">
      <?php 
        for ($i=0; $i <=4 ; $i++) {
          if ($i < $count) {
            $full = 'active';
          } else {
            $full = 'deactive';
          }
          echo "<i class=\"fa-regular fa-star $full\"></i>";
        }
      ?>
    </div>
    <?php 
  }

  public static function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);
    if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
    }
    $rgb = "$r, $g, $b";
    return $rgb;
  }

  public static function UserMeta(){
    $user_id = get_the_author_meta('ID');
    $f = get_user_meta( $user_id, 'facebook',true );
    $t = get_user_meta( $user_id, 'twitter',true );
    $l = get_user_meta( $user_id, 'linkdin',true );
    $p = get_user_meta( $user_id, 'pinterest',true );

    if(!empty( $f || $t || $l || $p )){  
    ?>

    <ul class="author-social">
      <?php if(!empty( $f )){ ?>
        <li>
            <a href="<?php echo esc_url ($f ); ?>"><i class="fab fa-facebook-f"></i></a>
        </li>
        <?php } if(!empty( $t )){ ?>
        <li>
            <a href="<?php echo esc_url ($t ); ?>"><i class="fab fa-twitter"></i></a>
        </li>
        <?php } if(!empty( $l )){ ?>
        <li>
            <a href="<?php echo esc_url ($l ); ?>"><i class="fab fa-linkedin-in"></i></a>
        </li>
        <?php } if(!empty( $p )){ ?>
        <li>
            <a href="<?php echo esc_url ($p ); ?>"><i class="fab fa-pinterest-p"></i></a>
        </li>
        <?php } ?>
    </ul>

    <?php } 
  }

  /**
   * Get Listing author image
   *
   * @param       $listing
   * @param  int  $size
   */
  static public function get_listing_author_image( $listing, $size = 40 ) {
    $owner_id   = $listing->get_owner_id();
    $pp_id      = absint( get_user_meta( $owner_id, '_rtcl_pp_id', true ) );
    if ($pp_id) {
    ?>
      <div class="directory-block__poster__thumb">
      <?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
        <a class="directory-block__poster__link--image" href="<?php echo esc_url( $listing->get_the_author_url() ); ?>">
          <?php echo wp_get_attachment_image( $pp_id, [ $size, $size ] ); ?>
        </a>
        <?php else:
          echo wp_get_attachment_image( $pp_id, [ $size, $size ] );
          endif;
        ?>
      </div>
    <?php } else { ?>
      <div class="directory-block__poster__thumb">
          <?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
            <a class="directory-block__poster__link--image" href="<?php echo esc_url( $listing->get_the_author_url() ); ?>">
              <?php echo get_avatar( $listing->get_author_id(), $size ); ?>
            </a>
          <?php else:
            echo get_avatar( $listing->get_author_id(), $size );
            endif;
          ?>
      </div>
    <?php }
  }

  static public function get_listing_author_info( $listing ) {
		if( class_exists( Review::class ) ){
      $average_rating = Review::getAvgRatings( get_the_ID() );
      $rating_count   = Review::getTotalRatings( get_the_ID() );
    } else {
      $average_rating = $listing->get_average_rating();
      $rating_count   = $listing->get_rating_count();
    }
    ?>
    <div class="directory-block__poster">
      <?php 
        if($listing->can_show_user()) {
          self::get_listing_author_image( $listing ); 
        }
      ?>
      <div class="directory-block__poster__info">
        <?php if($listing->can_show_user()) { ?>
        <span class="directory-block__poster__name">
            <?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
                <a class="author-link" href="<?php echo esc_url( $listing->get_the_author_url() ); ?>">
                  <?php echo esc_html( $listing->get_author_name() ); ?>
                </a>
            <?php else: ?>
                <?php echo wp_kses_post( $listing->get_author_name() ); ?>
            <?php endif; ?>
            <?php do_action('rtcl_after_author_meta', $listing->get_owner_id() ); ?>
        </span>
        <?php } if ( ! empty( $rating_count ) ): ?>
            <div class="directory-block__poster__ratings">
                <div class="product-rating">
                    <div class="item-icon">
                        <?php echo Functions::get_rating_html( $average_rating, $rating_count ); ?>
                    </div>
                    <div class="item-text"><?php echo apply_filters( 'listygo_rating_count_format', sprintf( __( '(<span>%s</span>)', 'listygo' ), esc_html( $rating_count ) ) ); ?></div>
                </div>
            </div>
        <?php endif; ?>
      </div>
    </div>
  <?php }

/**
	 * Number Shorten
	 *
	 * @param $number
	 * @param $precision
	 * @param $divisors
	 *
	 * @return mixed|string
	 */
	public static function rt_number_shorten( $number, $precision = 3, $divisors = null ) {
		if ( $number < 1000 ) {
			return $number;
		}

		$thousand    = _x( 'K', 'Thousand Shorthand', 'listygo' );
		$million     = _x( 'M', 'Million Shorthand', 'listygo' );
		$billion     = _x( 'B', 'Billion Shorthand', 'listygo' );
		$trillion    = _x( 'T', 'Trillion Shorthand', 'listygo' );
		$quadrillion = _x( 'Qa', 'Quadrillion Shorthand', 'listygo' );
		$quintillion = _x( 'Qi', 'Quintillion Shorthand', 'listygo' );

		$shorthand_label = apply_filters( 'homlisti_shorthand_price_label', [
			'thousand'    => $thousand,
			'million'     => $million,
			'billion'     => $billion,
			'trillion'    => $trillion,
			'quadrillion' => $quadrillion,
			'quintillion' => $quintillion
		] );

		// Setup default $divisors if not provided
		if ( ! isset( $divisors ) ) {
			$divisors = [
				pow( 1000, 0 ) => '', // 1000^0 == 1
				pow( 1000, 1 ) => isset( $shorthand_label['thousand'] ) ? $shorthand_label['thousand'] : $thousand,
				pow( 1000, 2 ) => isset( $shorthand_label['million'] ) ? $shorthand_label['million'] : $million,
				pow( 1000, 3 ) => isset( $shorthand_label['billion'] ) ? $shorthand_label['billion'] : $billion,
				pow( 1000, 4 ) => isset( $shorthand_label['trillion'] ) ? $shorthand_label['trillion'] : $trillion,
				pow( 1000, 5 ) => isset( $shorthand_label['quadrillion'] ) ? $shorthand_label['quadrillion'] : $quadrillion,
				pow( 1000, 6 ) => isset( $shorthand_label['quintillion'] ) ? $shorthand_label['quintillion'] : $quintillion,
			];
		}


		// Loop through each $divisor and find the
		// lowest amount that matches
		foreach ( $divisors as $divisor => $shorthand ) {
			if ( abs( $number ) < ( $divisor * 1000 ) ) {
				// We found a match!
				break;
			}
		}

		// We found our match, or there were no matches.
		// Either way, use the last defined value for $divisor.

		$shorthand_price = apply_filters( 'homlisti_shorthand_price', number_format( $number / $divisor, $precision ) );

		return self::rt_remove_unnecessary_zero( $shorthand_price ) . "<span class='price-shorthand'>{$shorthand}</span>";
	}

}

