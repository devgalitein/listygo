<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

class General_Setup {

	public function __construct() {
		add_action( 'after_setup_theme',                       array( $this, 'backend_admin_bar_hide' ) );
		add_action( 'after_setup_theme',                       array( $this, 'theme_setup' ) );
		add_action( 'widgets_init',                            array( $this, 'register_sidebars' ), 0 );
		add_filter( 'body_class',                              array( $this, 'body_classes' ) );
		add_filter( 'post_class',                              array( $this, 'post_classes' ) );
		add_filter( 'wp_list_categories',                      array( $this, 'listygo_cat_count_span' ) );
		add_filter( 'get_archives_link',                       array( $this, 'listygo_archive_cat_count_span' ) );
		add_action( 'wp_head',                                 array( $this, 'noscript_hide_preloader' ), 1 );
		add_action( 'wp_footer',                               array( $this, 'scroll_to_top_html' ), 1 );
		add_filter( 'get_search_form',                         array( $this, 'search_form' ) );	
		add_filter( 'elementor/widgets/wordpress/widget_args', array( $this, 'elementor_widget_args' ) );
		add_action( 'wp_head',                                 array( $this, 'listygo_pingback_header' ), 996 );
		add_action( 'site_prealoader',                         array( $this, 'listygo_preloader' ) );
		add_action( 'wp_kses_allowed_html',                    array( $this, 'listygo_kses_allowed_html' ), 10, 2 );
		add_action( 'template_redirect',                       array( $this, 'w3c_validator' ) );
    	add_filter( 'upload_mimes', 						   array( $this, 'cc_mime_types' ));
    	add_filter( 'intermediate_image_sizes', function( $sizes ){
		    return array_filter( $sizes, function( $val ){
		        return 'medium_large' !== $val; // Filter out 'medium_large'
		    } );
		} );
	}

	/**
	* Add a pingback url auto-discovery header for single posts, pages, or attachments.
	*/
	public function listygo_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	public function theme_setup() {
		$prefix = LISTYGO_THEME_PREFIX;		
		// Theme supports
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'wp-block' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_editor_style();
//		add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );
		// For gutenberg
		remove_theme_support( 'widgets-block-editor' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-color-palette', array(
			array(
				'name'  => esc_html__( 'Primary', 'listygo' ),
				'slug'  => 'listygo-primary',
				'color' => '#ff4a52',
			),
			array(
				'name'  => esc_html__('Secondary', 'listygo' ),
				'slug'  => 'listygo-secondary',
				'color' => '#CC1119',
			),
			array(
				'name'  => esc_html__('Light', 'listygo' ),
				'slug'  => 'listygo-light',
				'color' => '#ffffff',
			),
			array(
				'name'  => esc_html__('Black', 'listygo' ),
				'slug'  => 'listygo-black',
				'color'  => '#111111',
			),
			array(
				'name'  => esc_html__('Dark', 'listygo' ),
				'slug'  => 'listygo-dark',
				'color'  => '#646464',
			),
			
		) );

		add_theme_support( 'editor-font-sizes', array(
			array(
				'name' => esc_html__('Small', 'listygo' ),
				'size'  => 12,
				'slug'  => 'small'
			),
			array(
				'name'  => esc_html__('Normal', 'listygo' ),
				'size'  => 16,
				'slug'  => 'normal'
			),
			array(
				'name'  => esc_html__('Large', 'listygo' ),
				'size'  => 32,
				'slug'  => 'large'
			),
			array(
				'name'  => esc_html__('Huge', 'listygo' ),
				'size'  => 48,
				'slug'  => 'huge'
			)
		) );

		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles');		
	
		// Image sizes
		add_image_size( "listygo-size-1", 350, 270, true );  // Square Size
		add_image_size( "listygo-size-2", 330, 360, true );  // Height Size
		add_image_size( "listygo-size-3", 350, 420, true );  // Grid View Size
		add_image_size( "listygo-size-4", 580, 560, true );  // Banner Slider Size

		// Register menus
		register_nav_menus( array(
			'primary'  => esc_html__( 'Primary', 'listygo' ),
			'crmenu' => esc_html__( 'Footer Menu', 'listygo' ),
		) );

		// Custom Logo
		add_theme_support( 'custom-logo', array(
			'height'      => 65,
			'width'       => 245,
			'flex-height' => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support('custom-background', apply_filters('listygo_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));
    }

    public function backend_admin_bar_hide(){
        if (
            isset( RDTListygo::$options['restrict_admin_area'] )
            && RDTListygo::$options['restrict_admin_area']
            && ! current_user_can( 'administrator' )
        ) {
            // Hide admin bar everywhere
            add_filter( 'show_admin_bar', '__return_false' );

            // Block wp-admin access, but allow AJAX and login page
            add_action( 'init', function () {
                if ( is_admin() && ! wp_doing_ajax() && ! defined('DOING_CRON') && ! in_array($GLOBALS['pagenow'], ['admin-ajax.php', 'async-upload.php', 'wp-login.php']) ) {
                    wp_redirect( home_url() );
                    exit;
                }
            } );
        }
    }

	public function register_sidebars() {
		$footer_widget_titles1 = array(
			'1' => esc_html__( 'Footer (Style 1) 1', 'listygo' ),
			'2' => esc_html__( 'Footer (Style 1) 2', 'listygo' ),
			'3' => esc_html__( 'Footer (Style 1) 3', 'listygo' ),
			'4' => esc_html__( 'Footer (Style 1) 4', 'listygo' ),
		);	
		$footer_widget_titles2 = array(
			'1' => esc_html__( 'Footer (Style 2) 1', 'listygo' ),
			'2' => esc_html__( 'Footer (Style 2) 2', 'listygo' ),
			'3' => esc_html__( 'Footer (Style 2) 3', 'listygo' ),
			'4' => esc_html__( 'Footer (Style 2) 4', 'listygo' ),
		);
		$widgets_items1 = RDTListygo::$options['f1_widgets_area']; 
		$widgets_items2 = RDTListygo::$options['f1_widgets_area'];

		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar Widgets', 'listygo' ),
			'id'            => 'sidebar',
			'description'   => esc_html__('Sidebar widgets area', 'listygo'),
			'before_widget' => '<div id="%1$s" class="widget %2$s sidebar-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		for ( $i = 1; $i <= $widgets_items1; $i++ ) {
			register_sidebar( array(
				'name'          => $footer_widget_titles1[$i],
				'id'            => 'footer-widget-1-'. $i,
				'before_widget' => '<div id="%1$s" class="widget footer-widgets'. RDTListygo::$footer_style .' %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
		}
		if( class_exists( 'Listygo_Core' ) ){
			for ( $i = 1; $i <= $widgets_items2; $i++ ) {
				register_sidebar( array(
					'name'          => $footer_widget_titles2[$i],
					'id'            => 'footer-widget-2-'. $i,
					'before_widget' => '<div id="%1$s" class="widget footer-widgets'. RDTListygo::$footer_style .' %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				) );
			}
		}
	}

	public function body_classes( $classes ) {
		$classes[] = 'mobile-menu-wrapper';
		if ( RDTListygo::$options['sticky_header'] == 1 ) {
			$classes[] = 'sticky-header-enable';
		}

		$classes[] = 'header-style-'. RDTListygo::$header_style;
		
        // Sidebar
		$classes[] = ( RDTListygo::$layout == 'full-width' ) ? 'no-sidebar' : 'has-sidebar';

		if ( is_page_template( 'templates/box-layout.php' ) ) { 
			$classes[] = 'full-page-background';
		}

		if ( RDTListygo::$tr_header == 1 || RDTListygo::$tr_header === "on" ){
			$classes[] = 'transparent-header';
		}

		if ( RDTListygo::$header_top == '1' || RDTListygo::$header_top === "on" ) {
		    $classes[] = 'top-bar-enable';
		} else {
			$classes[] = 'top-bar-disable';
		}

		$htp = RDTListygo::$options['header_mobile_topbar_phone'] ? '' : 'htop-phone-disable';
		$hte = RDTListygo::$options['header_mobile_topbar_email'] ? '' : 'htop-email-disable';
		$hts = RDTListygo::$options['header_mobile_topbar_social'] ? '' : 'htop-social-disable';
		$responsive = $htp.' '.$hte.' '.$hts;
		$classes[] = $responsive;

		return $classes;
	}

	public function post_classes( $classes ) {
		$post_thumb = '';
		if ( has_post_thumbnail() ){
			$classes[] = 'have-post-thumb';
		}
		return $classes;
	}

    /*----------------------------------------------------------------------------------------*/
    /* Categories/Archive List count wrap by span
    /*----------------------------------------------------------------------------------------*/
    public function listygo_cat_count_span($links) {        
        $links = str_replace('(', '<span class="float-right">(', $links);
        $links = str_replace(')', ')</span>', $links);
        return $links;
    }

    public function listygo_archive_cat_count_span($links) {        
        $links = str_replace('(', '<span class="float-right">(', $links);
        $links = str_replace(')', ')</span>', $links);
        return $links;
    }

	public function noscript_hide_preloader(){
		// Hide preloader if js is disabled
		echo '<noscript><style>#preloader{display:none;}</style></noscript>';
	}

	public function scroll_to_top_html(){
		// Back-to-top link 
		if ( RDTListygo::$options['page_scrolltop'] == '1' ){
		echo '<a href="#wrapper" data-type="section-switch" class="scrollup">
			<i class="fa-solid fa-arrow-up"></i>
		</a>';
		}
	}

	public function search_form(){
		$output = '
		<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
			<div class="input-group stylish-input-group">		   
		    	<input type="text" class="form-control" placeholder="' . esc_attr__( 'Search ...', 'listygo' ) . '" value="' . get_search_query() . '" name="s" />
		    	<span class="input-group-addon">
			        <button type="submit">
			            '.Helper::search_icon2().'
			        </button>
		        </span>
			</div>
		</form>
		';
		return $output;
	}

	public function move_textarea_to_bottom( $fields ) {
		$temp = $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $temp;
		return $fields;
	}

	public function excerpt_more() {
		return esc_html__( '...', 'listygo' ) ;
	}
	
	public function elementor_widget_args( $args ) {
		$args['before_widget'] = '<div class="widget single-sidebar padding-bottom1">';
		$args['after_widget']  = '</div>';
		$args['before_title']  = '<h3>';
		$args['after_title']   = '</h3>';
		return $args;
	}

	public function cc_mime_types($mimes){
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	public function listygo_preloader() {
        ?>
        <div id="pageoverlay" class="pageoverlay">
                <div class="overlayDoor"></div>
                <div class="overlayContent">
					<?php if (!empty(RDTListygo::$options['preloader_gif'])) {
						echo wp_get_attachment_image( RDTListygo::$options['preloader_gif'], 'full' );
					} else { ?>
						<div class="pageloader">
							<div class="inner"></div>
						</div>
					<?php } ?>
                </div>
        </div>
    	<?php
	}

	public function listygo_kses_allowed_html($tags, $context) {
		switch($context) {
			case 'social':
			$tags = array(
				'a' => array('href' => array()),
				'b' => array()
			);
			return $tags;
			case 'alltext_allow':
			$tags = array(
				'a' => array(
					'class' => array(),
					'href'  => array(),
					'rel'   => array(),
					'title' => array(),
					'target' => array(),
				),
				'abbr' => array(
					'title' => array(),
				),
				'b' => array(),
				'br' => array(),
				'blockquote' => array(
					'cite'  => array(),
				),
				'cite' => array(
					'title' => array(),
				),
				'code' => array(),
				'del' => array(
					'datetime' => array(),
					'title' => array(),
				),
				'dd' => array(),
				'div' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
					'id' => array(),
				),
				'dl' => array(),
				'dt' => array(),
				'em' => array(),
				'h1' => array(),
				'h2' => array(),
				'h3' => array(),
				'h4' => array(),
				'h5' => array(),
				'h6' => array(),
				'i' => array(),
				'img' => array(
					'alt'    => array(),
					'class'  => array(),
					'height' => array(),
					'src'    => array(),
					'srcset' => array(),
					'width'  => array(),
				),
				'li' => array(
					'class' => array(),
				),
				'ol' => array(
					'class' => array(),
				),
				'p' => array(
					'class' => array(),
				),
				'q' => array(
					'cite' => array(),
					'title' => array(),
				),
				'span' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'strike' => array(),
				'strong' => array(),
				'ul' => array(
					'class' => array(),
				),
			);
			return $tags;
			default:
			return $tags;
		}
	}

	/* - Listygo Post and taxonomy slug change
	--------------------------------------------------------*/
	public function update_listygo_custom_post_slug( $args, $post_type ) {
		$prefix = LISTYGO_THEME_PREFIX;
		$theme = wp_get_theme(); // gets the current theme
		if ( 'Listygo' == $theme->name || 'Listygo' == $theme->parent_theme ) {
			$portfolio_slug = RDTListygo::$options['single_portfolio_slug'];
			$service_slug = RDTListygo::$options['single_service_slug'];
			$team_slug = RDTListygo::$options['single_team_slug'];
		    if ( $prefix.'_portfolio' === $post_type ) {
		        $portfolio = array(
		            'rewrite' => array( 'slug' => $portfolio_slug, 'with_front' => false )
		        );
		        return array_merge( $args, $portfolio );
		    }
		    if ( $prefix.'_service' === $post_type ) {
		        $service = array(
		            'rewrite' => array( 'slug' => $service_slug, 'with_front' => false )
		        );
		        return array_merge( $args, $service );
		    }
		    if ( $prefix.'_team' === $post_type ) {
		        $team = array(
		            'rewrite' => array( 'slug' => $team_slug, 'with_front' => false )
		        );
		        return array_merge( $args, $team );
		    }
		}
		return $args;
	}

	public function listygo_change_taxonomies_slug( $args, $taxonomy ) {
		$prefix = LISTYGO_THEME_PREFIX;
		$portfolio_cat_slug = RDTListygo::$options['portfolio_cat_slug'];
		$service_cat_slug = RDTListygo::$options['service_cat_slug'];
	    /*item and event pro locations*/
	    if ( $prefix.'_portfolio_category' === $taxonomy ) {
	    	$args['rewrite']['slug'] = $portfolio_cat_slug;
	    }
	    if ( $prefix.'_service_category' === $taxonomy ) {
	    	$args['rewrite']['slug'] = $service_cat_slug;
	    }
		return $args;
	}

	public function listygo_user_contactmethods($user_contactmethods){

		$user_contactmethods['facebook'] = 'Facebook Link';
		$user_contactmethods['twitter'] = 'Twitter Link';
		$user_contactmethods['linkdin'] = 'Linkdin Link';
		$user_contactmethods['pinterest'] = 'Pinterest Link';
		
		return $user_contactmethods;
	}

	public function w3c_validator() {
		/*----------------------------------------------------------------------------------------------------*/
		/*  W3C validator passing code
		/*----------------------------------------------------------------------------------------------------*/
	    ob_start( function( $buffer ){
	        $buffer = str_replace( array( '<script type="text/javascript">', "<script type='text/javascript'>" ), '<script>', $buffer );
	        return $buffer;
	    });
	    ob_start( function( $buffer2 ){
	        $buffer2 = str_replace( array( "<script type='text/javascript' src" ), '<script src', $buffer2 );
	        return $buffer2;
	    });
	    ob_start( function( $buffer3 ){
	        $buffer3 = str_replace( array( 'type="text/css"', "type='text/css'", 'type="text/css"', ), '', $buffer3 );
	        return $buffer3;
	    });
	    ob_start( function( $buffer4 ){
	        $buffer4 = str_replace( array( '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"', ), '<iframe', $buffer4 );
	        return $buffer4;
	    });
		ob_start( function( $buffer5 ){
	        $buffer5 = str_replace( array( 'aria-required="true"', ), '', $buffer5 );
	        return $buffer5;
	    });
	}
}
new General_Setup;