<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.1
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;
use Rtcl\Helpers\Functions;
use \WP_Query;
use Elementor\Plugin;

class Scripts {
	public $listygo  = LISTYGO_THEME_PREFIX;
	public $version = LISTYGO_THEME_VERSION;	
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 12 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15 );
		add_action( 'wp_enqueue_scripts', array( $this, 'dynamic_style' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_conditional_scripts' ), 1 );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'rt_custom_animations' ], 99999 );
	}

	public function fonts_url() {
		$fonts_url = '';
	    $subsets = 'latin';
	    $bodyFont = 'Outfit';
	    $bodyFW = '400';
	    $menuFont = 'Outfit';
	    $menuFontW = '500';
	    $offmenuFont = 'Outfit';
	    $offmenuFontW = '400';
	    $hFont = 'Outfit';
	    $hFontW = '600';
	    $h1Font = '';
	    $h2Font = '';
	    $h3Font = '';
	    $h4Font = '';
	    $h5Font = '';
	    $h6Font = '';

	    // Body Font
	    $body_font  = json_decode( RDTListygo::$options['typo_body'], true );
	    if ($body_font['font'] == 'Inherit') {
	    	$bodyFont = 'Outfit';
	    } else {
	    	$bodyFont = $body_font['font'];
	    }
	    $bodyFontW = $body_font['regularweight'];

	    // Menu Font
	    $menu_font  = json_decode( RDTListygo::$options['typo_menu'], true );
	    if ($menu_font['font'] == 'Inherit') {
		    $menuFont = 'Outfit';
		} else {
	    	$menuFont = $menu_font['font'];
		}
	    $menuFontW = $menu_font['regularweight'];

	    // Heading Font Settings
	    $h_font  = json_decode( RDTListygo::$options['typo_heading'], true );
	    if ($h_font['font'] == 'Inherit') {
		    $hFont = 'Outfit';
		} else {
			$hFont = $h_font['font'];
		}
	    $hFontW = $h_font['regularweight'];
	    $h1_font  = json_decode( RDTListygo::$options['typo_h1'], true );
	    $h2_font  = json_decode( RDTListygo::$options['typo_h2'], true );
	    $h3_font  = json_decode( RDTListygo::$options['typo_h3'], true );
	    $h4_font  = json_decode( RDTListygo::$options['typo_h4'], true );
	    $h5_font  = json_decode( RDTListygo::$options['typo_h5'], true );
	    $h6_font  = json_decode( RDTListygo::$options['typo_h6'], true );

	    if ( 'off' !== _x( 'on', 'Google font: on or off', 'listygo' ) ) {

		    if (!empty($h1_font['font'])) {
		        if ($h1_font['font'] == 'Inherit') {
				    $h1Font = $hFont;
				    $h1FontW = $hFontW;
				} else {
					$h1Font = $h2_font['font'];
		        	$h1FontW = $h1_font['regularweight'];
				}
		    } if (!empty($h2_font['font'])) {
		    	if ($h2_font['font'] == 'Inherit') {
				    $h2Font = $hFont;
				    $h2FontW = $hFontW;
				} else {
					$h2Font = $h2_font['font'];
		        	$h2FontW = $h2_font['regularweight'];
				}
		    } if (!empty($h3_font['font'])) {
		        if ($h3_font['font'] == 'Inherit') {
				    $h3Font = $hFont;
				    $h3FontW = $hFontW;
				} else {
					$h3Font = $h3_font['font'];
		        	$h3FontW = $h3_font['regularweight'];
				}
		    } if (!empty($h4_font['font'])) {
		        if ($h4_font['font'] == 'Inherit') {
				    $h4Font = $hFont;
				    $h4FontW = $hFontW;
				} else {
					$h4Font = $h4_font['font'];
		        	$h4FontW = $h4_font['regularweight'];
				}
		    } if (!empty($h5_font['font'])) {
		        if ($h5_font['font'] == 'Inherit') {
				    $h5Font = $hFont;
				    $h5FontW = $hFontW;
				} else {
					$h5Font = $h5_font['font'];
		        	$h5FontW = $h5_font['regularweight'];
				}
		    } if (!empty($h6_font['font'])) {
		    	 if ($h6_font['font'] == 'Inherit') {
				    $h6Font = $hFont;
				    $h6FontW = $hFontW;
				} else {
					$h6Font = $h6_font['font'];
		        	$h6FontW = $h6_font['regularweight'];
				}
		    }

		    $check_families = array();
		    $font_families = array();

			// Body Font
			if ( 'off' !== $bodyFont )
				$font_families[] = $bodyFont.':300,'.$bodyFW.',500,600,700';
				$check_families[] = $bodyFont;

			// Menu Font
			if ( 'off' !== $menuFont )
				if ( !in_array( $menuFont, $check_families ) ) {
					$font_families[] = $menuFont.':'.$menuFontW.',600,700';
					$check_families[] = $menuFont;
				}

			// Offcanvas Menu Font
			if ( 'off' !== $offmenuFont )
				if ( !in_array( $offmenuFont, $check_families ) ) {
					$font_families[] = $offmenuFont.':'.$offmenuFontW;
					$check_families[] = $offmenuFont;
				}

			// Heading Font
			if ( 'off' !== $hFont )
				if (!in_array( $hFont, $check_families )) {
					$font_families[] = $hFont.':'.$hFontW;
					$check_families[] = $hFont;
				}

			// Heading 1 Font
			if (!empty($h1_font['font'])) {
				if ( 'off' !== $h1Font )
					if (!in_array( $h1Font, $check_families )) {
						$font_families[] = $h1Font.':'.$h1FontW;
						$check_families[] = $h1Font;
					}
			}
			// Heading 2 Font
			if (!empty($h2_font['font'])) {
				if ( 'off' !== $h2Font )
					if (!in_array( $h2Font, $check_families )) {
						$font_families[] = $h2Font.':'.$h2FontW;
						$check_families[] = $h2Font;
					}
			}
			// Heading 3 Font
			if (!empty($h3_font['font'])) {
				if ( 'off' !== $h3Font )
					if (!in_array( $h3Font, $check_families )) {
						$font_families[] = $h3Font.':'.$h3FontW;
						$check_families[] = $h3Font;
					}
			}
			// Heading 4 Font
			if (!empty($h4_font['font'])) {
				if ( 'off' !== $h4Font )
					if (!in_array( $h4Font, $check_families )) {
						$font_families[] = $h4Font.':'.$h4FontW;
						$check_families[] = $h4Font;
					}
			}

			// Heading 5 Font
			if (!empty($h5_font['font'])) {
				if ( 'off' !== $h5Font )
					if (!in_array( $h5Font, $check_families )) {
						$font_families[] = $h5Font.':'.$h5FontW;
						$check_families[] = $h5Font;
					}
			}
			// Heading 6 Font
			if (!empty($h6_font['font'])) {
				if ( 'off' !== $h6Font )
					if (!in_array( $h6Font, $check_families )) {
						$font_families[] = $h6Font.':'.$h6FontW;
						$check_families[] = $h6Font;
					}
			}
		    $final_fonts = array_unique( $font_families );
		    $query_args = array(
		        'family' => urlencode( implode( '|', $final_fonts ) ),
		        // 'subset' => urlencode( $subsets ),
		        'display' => urlencode( 'fallback' ),
		    );

		    $fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
		}
		
    	return esc_url_raw( $fonts_url );
	}	

	public function rt_custom_animations() {
		wp_register_style( 'rt-animation', Helper::get_css( 'rt-animation' ), [], $this->version );
		wp_enqueue_style( 'rt-animation' );
	}

	public function register_scripts(){

		if ( is_archive( 'rtcl_listing' ) && Helper::is_map_enabled() ) {
			wp_enqueue_script( 'rtcl-map' );
		}

		/* = CSS
		===========================================================*/
		// Bootstrap css
		wp_register_style( 'bootstrap',        Helper::get_vendor_css( 'bootstrap/bootstrap.min' ), array(), $this->version );
		// FontAwesome css
		wp_register_style( 'fontawesome',   Helper::get_nomin_css( 'all.min' ), array(), $this->version );
		// Custom Icon Fonts css
		wp_register_style( 'flaticon',         Helper::get_nomin_css( 'flaticon' ), array(), $this->version );
		wp_register_style( 'fontello',         Helper::get_nomin_css( 'fontello' ), array(), $this->version );
		wp_register_style( 'custom-fonts',     Helper::get_vendor_css( 'fonts/custom-fonts' ), array(), $this->version );
		// Magnific			
		wp_register_style( 'magnific-popup',   Helper::get_vendor_css( 'magnific-popup/magnific-popup' ), array(), $this->version );
		// Swiper
		wp_register_style( 'swiper',           Helper::get_vendor_css( 'swiper/swiper.min' ), array(), $this->version );
		//Gallery Lighbox
		wp_register_style( 'photoswipe', 	   Helper::get_vendor_css( 'photoswipe/photoswipe' ), [], $this->version );
		wp_register_style( 'photoswipe-default-skin', Helper::get_vendor_css( 'photoswipe/default-skin/default-skin' ), [], $this->version );
		// Rangle Slider
		wp_register_style( 'rangeSlider', 	   Helper::get_vendor_css( 'rangeSlider/ion.rangeSlider.min' ), [], $this->version );
		// Animate css
		wp_register_style( 'animate',          Helper::get_css( 'animate' ), array(), $this->version );
		// Main Theme Style
		wp_register_style( 'listygo-style',    Helper::get_css( 'style' ), array(), $this->version );
		// Main Theme Style
		wp_register_style( 'listygo-rtl', 	   Helper::get_rtl_css( 'listygo-rtl' ), [], $this->version );

		
		/* = JS 
		======================================================================*/
		// Bootstrap	
		wp_register_script( 'popper', Helper::get_vendor_js( 'bootstrap/popper.min' ), array( 'jquery' ), $this->version, true );
		wp_register_script( 'bootstrap', Helper::get_vendor_js( 'bootstrap/bootstrap.min' ), array( 'jquery' ), $this->version, true );
		
		// WoW Animation js
		wp_register_script( 'appear',      Helper::get_js( 'appear.min' ), array( 'jquery' ), $this->version, true );
		wp_register_script( 'wow',         Helper::get_js( 'wow.min' ), array( 'jquery' ), $this->version, true );
		// Swiper Slider
		wp_register_script( 'swiper',      Helper::get_vendor_js( 'swiper/swiper.min' ), array( 'jquery' ), $this->version, true );
		wp_register_script( 'slider-func', Helper::get_vendor_js( 'swiper/slider-func' ), array( 'jquery' ), $this->version, true );
		// Isotope Filtering
		wp_register_script( 'isotope', Helper::get_vendor_js( 'isotope/isotope.pkgd.min' ), array( 'jquery' ), $this->version, true );
		// Magnific Popup
		wp_register_script( 'jquery-magnific-popup', Helper::get_vendor_js( 'magnific-popup/magnific-popup.min' ), array( 'jquery' ), $this->version, true );
		wp_register_script( 'popup-func', Helper::get_vendor_js( 'magnific-popup/popup-func' ), array( 'jquery' ), $this->version, true );
		// CountDown
		wp_register_script( 'jquery-countdown', Helper::get_vendor_js( 'countdown/jquery.countdown.min' ), array( 'jquery' ), $this->version, true );
		// PhotoSwipe js
		wp_register_script( 'photoswipe', Helper::get_vendor_js( 'photoswipe/photoswipe.min' ), [ 'jquery' ], $this->version, true );
		wp_register_script( 'photoswipe-ui-default', Helper::get_vendor_js( 'photoswipe/photoswipe-ui-default.min' ), [ 'jquery' ], $this->version, true );
		wp_register_script( 'rangeSlider', Helper::get_vendor_js( 'rangeSlider/ion.rangeSlider.min' ), [ 'jquery' ], $this->version, true );
		wp_register_script( 'theia-sticky-sidebar', Helper::get_js( 'theia-sticky-sidebar.min' ), array( 'jquery' ), $this->version, true );
		wp_register_script( 'rt-bg-parallax', Helper::get_js( 'rt-parallax' ), [ 'jquery' ], $this->version, true );

		// Food Menu js
		wp_register_script( 'rt-food-menu', Helper::get_js( 'food-menu' ), [ 'jquery' ], $this->version, true );
		// Doctor Chamber js
		wp_register_script( 'rt-doctor-chamber', Helper::get_js( 'doctor-chamber' ), [ 'jquery' ], $this->version, true );
		// Main js
		wp_register_script( 'listygo-main', Helper::get_js( 'main' ), array( 'jquery' ), $this->version, true );
	}  
    
	public function enqueue_scripts() {
		/*CSS*/
		wp_enqueue_style( 'listygo-gfonts', $this->fonts_url(), array(), $this->version );
		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'fontawesome' );
		wp_enqueue_style( 'flaticon' );
		wp_enqueue_style( 'fontello' );
		wp_enqueue_style( 'animate' );
		wp_enqueue_style( 'rangeSlider' );
		wp_enqueue_style( 'photoswipe' );
		wp_enqueue_style( 'photoswipe-default-skin' );
		wp_enqueue_style( 'rttheme-select2-css', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/select2.min.css', array(), '4.0.6', 'all' );
		$this->conditional_scripts();
		wp_enqueue_style( 'listygo-style' );
		if ( is_rtl() ) {
			wp_enqueue_style( 'listygo-rtl' );
		}
		
		/*JS*/
		wp_enqueue_script( 'popper' );
		wp_enqueue_script( 'bootstrap' );
		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'appear' );
		wp_enqueue_script( 'wow' );
		wp_enqueue_script( 'isotope' );
		wp_enqueue_script( 'photoswipe' );
		wp_enqueue_script( 'photoswipe-ui-default' );
		wp_enqueue_script( 'rangeSlider' );
		wp_enqueue_script( 'rttheme-select2-js', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/typography/assets/select2.min.js', array( 'jquery' ), '4.0.6', true );
		wp_enqueue_script( 'theia-sticky-sidebar' );
		wp_enqueue_script( 'rt-bg-parallax' );
		wp_enqueue_script( 'jquery-countdown' );
		$this->localized_scripts();
		wp_enqueue_script( 'rt-food-menu' );
		wp_enqueue_script( 'rt-doctor-chamber' );
		wp_enqueue_script( 'listygo-main' );

		$this->elementor_scripts();
	}

	public function elementor_scripts() {
		if ( !did_action( 'elementor/loaded' ) ) {
			return;
		}
		if ( Plugin::$instance->preview->is_preview_mode() ) {
			wp_enqueue_style( 'custom-fonts' );			
			// Swiper
			wp_enqueue_style( 'swiper' );
			wp_enqueue_script( 'swiper' );
			wp_enqueue_script( 'slider-func' );
			// Magnific
			wp_enqueue_style( 'magnific-popup' );
			wp_enqueue_script( 'jquery-magnific-popup' );
			wp_enqueue_script( 'popup-func' );
		}
		if (is_page_template('template/listing-map.php')) { 
			wp_enqueue_style( 'rttheme-select2-css' );
			wp_enqueue_script( 'rttheme-select2-js' );
		}
	}

	private function conditional_scripts(){
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	
	public function admin_conditional_scripts(){
		//Css
		wp_enqueue_style( 'flaticon',              Helper::get_nomin_css( 'flaticon' ), array(), $this->version );	
		wp_enqueue_style( 'fontello',              Helper::get_nomin_css( 'fontello' ), array(), $this->version );	
		wp_enqueue_style( 'custom-fonts',          Helper::get_vendor_css( 'fonts/custom-fonts' ), array(), $this->version );
		wp_enqueue_style( 'listygo-gfonts',        $this->fonts_url(), array(), $this->version );
		wp_enqueue_style( 'fontawesome',           Helper::get_nomin_css( 'all.min' ), array(), $this->version );
		wp_enqueue_style( 'customizer-controls',   Helper::get_nomin_css( 'customizer' ), array(), $this->version );
		wp_enqueue_style( 'select2', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/select2.min.css', array(), '4.0.6', 'all' );
		// Js
		wp_enqueue_script( 'select2', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/typography/assets/select2.min.js', array( 'jquery' ), '4.0.6', true );
		wp_enqueue_script( 'listygo-admin',		   Helper::get_js( 'listygo-admin' ), array( 'jquery' ), $this->version, true );
	}

	private function localized_scripts(){
		$adminajax 	   = esc_url( admin_url('admin-ajax.php') );
		$localize_data = array(
			'ajaxurl'	  => $adminajax,
			// Countdown Translate Text
			'day'	         => esc_html__('Days' , 'listygo'),
			'hour'	         => esc_html__('Hrs' , 'listygo'),
			'minute'         => esc_html__('Mins' , 'listygo'),
			'second'         => esc_html__('Secs' , 'listygo'),
			// Restaurant Translate Text
			'gtitle'        => esc_html__('Group Title' , 'listygo'),
			'title'         => esc_html__('Title' , 'listygo'),
			'price'         => esc_html__('Price' , 'listygo'),
			'description'   => esc_html__('Description' , 'listygo'),
			'addfood'       => esc_html__('Add Food' , 'listygo'),
			// Doctor Translate Text
			'cname'        => esc_html__('Chamber Name' , 'listygo'),
			'location'     => esc_html__('Location' , 'listygo'),
			'time'     => esc_html__('Time' , 'listygo'),
			'phone'     => esc_html__('Phone' , 'listygo'),
		);
		wp_localize_script( 'listygo-main', 'ListygoObj', $localize_data );
	}

	public function dynamic_style(){
		$dynamic_css  = $this->template_style();
		ob_start();
		Helper::requires( 'dynamic-style.php' );
		$dynamic_css .= ob_get_clean();
		$dynamic_css  = $this->minified_css( $dynamic_css );
		wp_register_style( $this->listygo . '-dynamic', false );
		wp_enqueue_style( $this->listygo . '-dynamic' );
		wp_add_inline_style( $this->listygo . '-dynamic', $dynamic_css );
	}

	private function minified_css( $css ) {
		/* remove comments */
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		/* remove tabs, spaces, newlines, etc. */
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), ' ', $css );
		return $css;
	}

	private function template_style(){
		$style = '';
		if ( is_single() ) {
			if ( !empty(RDTListygo::$bgimg ) ) {
				$opacity = RDTListygo::$opacity/100;
				$style .= '.single .breadcrumbs-banner { background-image: url(' . RDTListygo::$bgimg . ')}';
				$style .= '.single .breadcrumbs-banner:before { background-color: ' . RDTListygo::$bgcolor . '}';
				$style .= '.single .breadcrumbs-banner:before { opacity: ' . $opacity . '}';
			}
			else {
				$opacity = RDTListygo::$opacity/100;
				$style .= '.single .breadcrumbs-banner:before { background-color: ' . RDTListygo::$bgcolor . '}';
				$style .= '.breadcrumbs-banner:before { opacity: ' . $opacity . '}';
			}
		} else {
			if ( !empty( RDTListygo::$bgimg ) ) {
				$opacity = RDTListygo::$opacity/100;
				$style .= '.breadcrumbs-banner { background-image: url(' . RDTListygo::$bgimg . ')}';
				$style .= '.breadcrumbs-banner:before { background-color: ' . RDTListygo::$bgcolor . '}';
				$style .= '.breadcrumbs-banner:before { opacity: ' . $opacity . '}';
			}
			else {
				$opacity = RDTListygo::$opacity/100;
				$style .= '.breadcrumbs-banner:before { background-color: ' . RDTListygo::$bgcolor . '}';
				$style .= '.breadcrumbs-banner:before { opacity: ' . $opacity . '}';
			}
		}

		if ( RDTListygo::$padding_top) {
			$style .= '.breadcrumbs-banner { padding-top:'. RDTListygo::$padding_top . 'px;}';
		}
		if ( RDTListygo::$padding_bottom) {
			$style .= '.breadcrumbs-banner { padding-bottom:'. RDTListygo::$padding_bottom . 'px;}';
		}
		
		if ( !empty(RDTListygo::$pagebgimg ) ) {
			$style .= 'body { background-image: url(' . RDTListygo::$pagebgimg[0] . ')!important}';
		}
		if ( !empty(RDTListygo::$pagebgcolor ) ) {
			$style .= 'body { background-color: ' . RDTListygo::$pagebgcolor . '!important}';
		}

		/* = Listing bg
        =======================================================*/
		if( is_singular( 'rtcl_listing' ) || is_post_type_archive( "rtcl_listing" ) || is_tax( "rtcl_category" ) ) {
			if ( !empty(RDTListygo::$pagebgimg ) ) {
				$style .= 'body { background-image: url(' . RDTListygo::$pagebgimg[0] . ')!important}';
				$style .= '.listing-archvie-page.bg--accent { background-image: url(' . RDTListygo::$pagebgimg[0] . ')!important}';
			}
			if ( !empty(RDTListygo::$pagebgcolor ) ) {
				$style .= 'body { background-color: ' . RDTListygo::$pagebgcolor . '!important}';
				$style .= '.listing-archvie-page.bg--accent { background-color: ' . RDTListygo::$pagebgcolor . '!important}';
			}
		}
		/* = Page bg
        =======================================================*/
		if ( has_post_thumbnail()) {
			$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
			$style .= '.full-page-background { background-image: url(' . $large_image_url[0] . ')}';
		}
		
		return $style;
	}	
	
}
new Scripts;