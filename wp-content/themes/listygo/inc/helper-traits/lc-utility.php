<?php
/**
 * Check Radius Theme License
 *
 * @since 1.0.0
 */

namespace RTLC;

if ( defined( 'RT_DEBUG' ) && RT_DEBUG ) {
	return;
}

/**
 * Check Radius Theme License
 */
class Utility {
	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'admin_notices', [ $this, 'register_notice' ], 1 );
		add_action( 'admin_head', [ $this, 'style' ] );
		add_action( 'admin_footer', [ $this, 'script' ] );
	}

	/**
	 * Admin Notice.
	 *
	 * @return void
	 */
	public function register_notice() {
		// if licensed activated and license page return.
		if ( rtlc_is_valid()['success'] || ( isset( $_GET['page'] ) && 'rtlc' === $_GET['page'] ) ) {
			return;
		}

		$class = 'notice notice-error';

		if ( isset( rtlc_is_valid()['domain_match'] ) && ! rtlc_is_valid()['domain_match'] ) {
			$message = sprintf(
				'<b>%1$s <a href="%2$s">%3$s</a></b>',
				__( 'Your license key doesn\'t match your current domain. Please activate the license again for your current domain.', 'listygo' ),
				menu_page_url( 'rtlc', false ),
				__( 'Activate License', 'listygo' ),
			);
		} else {
			$message = sprintf(
				'<b>%1$s <a href="%2$s" target="_blank">%3$s</a> %4$s <a href="%5$s">%6$s</a></b>',
				__( 'Please activate your theme using Envato', 'listygo' ),
				esc_url( 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-' ),
				__( 'purchase code', 'listygo' ),
				__( 'to get full theme functionality and customer support.', 'listygo' ),
				menu_page_url( 'rtlc', false ),
				__( 'Activate License', 'listygo' ),
			);
		}

		printf(
			'<div class="%1$s"><p>%2$s</p></div>',
			esc_attr( $class ),
			wp_kses(
				$message,
				[
					'a' => [
						'href'   => [],
						'target' => [],
					],
					'b' => [],
				]
			)
		);
	}

	/**
	 * License page styles.
	 *
	 * @return void
	 */
	public function style() {
		if ( ! isset( $_GET['page'] ) || 'rtlc' !== $_GET['page'] ) {
			return;
		}
		?>
		<style>
			.rtlc-status-btn {
				color: white; padding: 5px 15px; border-radius: 5px;
			}
			.rtlc-unverified {
				background: #b32121;
			}
			.rtlc-verified {
				background: #498414;
			}
			.rtcl-note {
				color: red;
			}
			.dashicons.spin {
				animation: dashicons-spin 1s infinite;
				animation-timing-function: linear;
			} 
			@keyframes dashicons-spin {
				0% {
					transform: rotate( 0deg );
				}
				100% {
					transform: rotate( 360deg );
				}
			}
			.rtlc-loader {  
				margin-top: 10px;
				display: inline-block; 
				visibility: hidden;
			} 
			#rtlc_license_check:focus {
				box-shadow: none;
			}
			.rtcl-active-btn {
				padding: 5px 20px !important;
				font-size: 14px !important;
			}
			#setting-error-tgmpa {
				display: none;
			}
		</style>
		<?php
	}

	/**
	 * Ajax Action.
	 *
	 * @return void
	 */
	public function script() {
		if ( wp_script_is( 'jquery', 'done' ) && ( isset( $_GET['page'] ) && 'rtlc' === $_GET['page'] ) ) {
			?>
			<script type="text/javascript">
			jQuery("#rtlc_license_check").on("click", function() {
				let purchase_code = jQuery('#rt_purchase_code').val();

				if ( purchase_code ) {
					jQuery.ajax({
						type: "post",
						dataType: "json",
						url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
						data: {
							action: "rtlc_verification", 
							purchase_code,   
						},
						beforeSend: function() {     
							jQuery('.rtlc-loader').css("visibility", "inherit");  
						},
						success: function( resp ) {   
							if ( resp === 555 ) {
								alert('<?php esc_html_e( 'Purchase code already activated for one domain!!!', 'listygo' ); ?>'); 
							} else {
								if ( resp ) {
									jQuery('.rtlc-status-btn').html('<?php esc_html_e( 'Activated', 'listygo' ); ?>');
									jQuery('.rtlc-status-btn').removeClass('rtlc-unverified').addClass('rtlc-verified');
								} else {
									alert('<?php esc_html_e( 'Sorry!!! Purchase code does not match', 'listygo' ); ?>');
								}
							} 
							jQuery('.rtlc-loader').css("visibility", "hidden");
						}, 
					}); 
				} else {
					alert('<?php esc_html_e( 'Purchase code is required!!!', 'listygo' ); ?>');
				} 
			});
		</script> 
			<?php
		}

		if ( wp_script_is( 'jquery', 'done' ) && ! rtlc_is_valid()['success'] ) {
			if ( isset( $_GET['page'] ) && 'fw-backups-demo-content' === $_GET['page'] ) {
				?>
				<script type="text/javascript"> 
					jQuery("#fw-ext-backups-demo-list .theme-actions a").on("click", function(e) { 
						e.preventDefault();
						alert('<?php esc_html_e( 'Please activate your theme using Envato purchase code, to install demo data.', 'listygo' ); ?>');
						return false;
					});
				</script> 
				<?php
			}

			if ( isset( $_GET['page'] ) && 'listygo-install-plugins' === $_GET['page'] ) {
				?>
				<script type="text/javascript">  
					jQuery(".row-actions a").on("click", function(e) {  
						if ( jQuery(this).closest('td').next().has('span').length > 0 ) { //find pre packaged
							e.preventDefault();
							alert('<?php esc_html_e( 'Please activate your theme using Envato purchase code, to use this plugin.', 'listygo' ); ?>');
							return false;
						} 
					});

					jQuery(".check-column input").on("change", function(e) {
						if ( !jQuery(e.target).is(':checked') ) return;

						if ( jQuery(this).parent().hasClass('column-cb') ) { //all checked or not 
							jQuery('table.wp-list-table > tbody  > tr').each(function(index, tr) {   
								if ( jQuery(tr).find('.column-source').has('span').length > 0 ) { 
									jQuery(tr).find('.check-column input').prop('checked', false);
								}  
							});
						} else {
							if ( jQuery(this).closest('th').next().next().has('span').length > 0 ) { //find pre packaged 
								jQuery(this).prop('checked', false); 
								alert('<?php esc_html_e( 'Please activate your theme using Envato purchase code, to use this plugin.', 'listygo' ); ?>'); 
							} 
						} 
					});
				</script> 
				<?php
			}
		}
	}
}

if ( is_admin() ) {
	new Utility();
}

if ( ! function_exists( 'rtlc_is_valid' ) ) {
	/**
	 * License Validity Check.
	 *
	 * @return false[]|true[]
	 */
	function rtlc_is_valid() {
		$theme_info      = wp_get_theme();
		$theme_info      = ( $theme_info->parent() ) ? $theme_info->parent() : $theme_info;
		$listygo = $theme_info->get( 'Name' );

		$listygo = strtolower( trim( preg_replace( '/[^A-Za-z0-9-]+/', '-', $listygo ) ) );
		$get_option      = get_option( 'rt_licenses' );

		if ( isset( $get_option[ $listygo . '_license_key' ] ) ) {
			return [
				'success'      => true,
				'domain_match' => true,
			];
		} elseif ( isset( $get_option[ $listygo . '_license' ] ) && isset( $get_option[ $listygo . '_license' ]['key'] ) ) {
			$domain_name = rtlc_get_domain_name();
			$domain      = $get_option[ $listygo . '_license' ]['domain'];

			if ( $domain_name === rtlc_get_domain_name( $domain ) ) {
				return [
					'success'      => true,
					'domain_match' => true,
				];
			} else {
				return [
					'success'      => false,
					'domain_match' => false,
				];
			}
		} else {
			return [
				'success' => false,
			];
		}
	}
}

if ( ! function_exists( 'rtlc_get_domain_name' ) ) {
	/**
	 * Get Domain Name.
	 *
	 * @return string
	 */
	function rtlc_get_domain_name( $url = null ) {
		$protocols = [ 'http://', 'https://', 'http://www.', 'https://www.', 'www.' ];
		return str_replace( $protocols, '', esc_url( ! empty( $url ) ? $url : site_url() ) );
	}
}
