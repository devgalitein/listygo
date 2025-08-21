<?php

/**
 * @var string  $address
 * @var string  $phone
 * @var string  $whatsapp_number
 * @var string  $email
 * @var string  $website
 * @var array   $phone_options
 * @var bool    $has_contact_form
 * @var string  $email_to_seller_form
 * @var Listing $listing
 * @var array   $locations
 * @var int     $listing_id Listing id
 * @author       RadiusTheme
 * @package      classified-listing/templates
 * @version      1.0.0
 *
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Link;
use Rtcl\Helpers\Text;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;

use radiustheme\listygo\Helper;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Listing_Functions;

global $listing;

$generalSettings = Functions::get_option('rtcl_general_settings');
$appointment_label = !empty($generalSettings['listygo_doctor_appointment_label']) ? $generalSettings['listygo_doctor_appointment_label'] : '';

$location_type = Functions::location_type();
$address = get_post_meta($listing->get_id(), 'address', true);
$geo_address = get_post_meta($listing->get_id(), '_rtcl_geo_address', true);
$phone = get_post_meta($listing->get_id(), 'phone', true);
$phone_url = str_replace(' ', '', $phone);
$whatsapp = get_post_meta($listing->get_id(), '_rtcl_whatsapp_number', true);
$email = get_post_meta($listing->get_id(), 'email', true);

$pattern = array("(^https?://)", "(^http?://)");
$website_url = preg_replace($pattern, "", $website);

$event_group_id = isset(RDTListygo::$options['custom_events_fields_types']) ? RDTListygo::$options['custom_events_fields_types'] : [];

$date = '';
if ($event_group_id) {
	$field_ids   = Functions::get_cf_ids_by_cfg_id($event_group_id);
}
if (!empty($field_ids)) {
	foreach ($field_ids as $single_field) {
		$field = new RtclCFGField($single_field);
		$value = $field->getFormattedCustomFieldValue($listing->get_id());
		if (!$value) {
			continue;
		}
		$date = $value ? $value : '';
	}
}
?>

<?php
$cats_ids = $listing->get_category_ids();
foreach ($cats_ids as $key => $value) {
	$category_id = $value;
}

$doctor_cat_id = Listing_Functions::get_listing_doctor_category_id();
$has_doctor = Listing_Functions::listygo_selected_category_fields($category_id, $doctor_cat_id);
if ($has_doctor && Listing_Functions::is_enable_doctor_listing()) {
	if (!empty($phone)) { ?>
		<div class="rtcl-listing-user-info call-for-appointment">
			<a href="tel:<?php echo esc_attr($phone_url); ?>" class="listingDetails-header__tag">
				<i class="flaticon-phone-call"></i>
				<?php echo esc_html($appointment_label); ?>
			</a>
		</div>
<?php }
}
?>

<?php
if (Fns::is_enable_chat() && ((is_user_logged_in() && $listing->get_author_id() !== get_current_user_id()) || !is_user_logged_in())) :
	$chat_btn_class = ['rtcl-chat-link'];
	$chat_url = Link::get_my_account_page_link();
	$chat_label = esc_html__("Quick Chat", 'listygo');
	$chant_enable_class = "rtcl-contact-seller";
	if (is_user_logged_in()) {
		$chat_url = '#';
		array_push($chat_btn_class, 'rtcl-contact-seller');
	} else {
		array_push($chat_btn_class, 'rtcl-no-contact-seller');
		$chat_label = esc_html__("Please login for Chat", 'listygo');
		$chant_enable_class = 'need-to-logedin';
	}
?>
	<div class="rtcl-listing-user-info chat-form">
		<div class=<?php echo esc_attr($chant_enable_class); ?>>
			<a class="<?php echo esc_attr(implode(' ', $chat_btn_class)) ?>" href="<?php echo esc_url($chat_url) ?>" data-listing_id="<?php the_ID() ?>">
				<i class="fa-brands fa-rocketchat"></i>
				<?php echo esc_html($chat_label); ?>
			</a>
		</div>
	</div>
<?php endif; ?>

<?php if ($has_contact_form && $email) : ?>
	<div class="rtcl-listing-user-info contact-form">
		<div class='rtcl-do-email list-group-item'>
			<div class='media'>
				<span class='rtcl-icon rtcl-icon-mail'></span>
				<div class='media-body'>
					<a class="rtcl-do-email-link" href='#'>
						<?php echo Text::get_single_listing_email_button_text(); ?>
					</a>
				</div>
			</div>
			<?php Functions::print_html($email_to_seller_form, true); ?>
		</div>
	</div>
<?php endif; ?>

<?php if (!empty($date) && Listing_Functions::is_enable_event_listing()) { ?>
	<div class="entry-wrapper-countdown">
		<div class="rtcl-listing-side-title">
			<h3> <?php esc_html_e('Upcoming Event', 'listygo'); ?> </h3>
		</div>
		<ul>
			<?php Helper::get_custom_listing_template('cfg-event'); ?>
		</ul>
	</div>
<?php } ?>

<?php do_action('rtcl_after_single_listing_sidebar', $listing->get_id()); // This hook for booking ?>

<div class="rtcl-listing-user-info">
	<?php if (Fns::registered_user_only('listing_seller_information') && !is_user_logged_in()) { ?>
		<p class="login-message"><?php echo wp_kses(sprintf(__("Please <a href='%s'>login</a> to view the seller information.", "listygo"), esc_url(Link::get_my_account_page_link())), ['a' => ['href' => []]]); ?></p>
	<?php } else { ?>
		<div class="rtcl-listing-side-title">
			<h3><?php esc_html_e("Posted By", 'listygo'); ?></h3>
		</div>
		<div class="list-group">
			<?php if ($listing->can_show_user()) { ?>
				<div class="listing-author">
					<div class="author-logo-wrapper">
						<?php Helper::get_listing_author_image($listing, '80'); ?>
					</div>
					<h4 class="author-name">
						<?php if ($listing->can_add_user_link() && !is_author()) : ?>
							<a class="author-link" href="<?php echo esc_url($listing->get_the_author_url()); ?>">
								<?php echo esc_html($listing->get_author_name()); ?>
							</a>
						<?php else : ?>
							<?php echo wp_kses_post($listing->get_author_name()); ?>
						<?php endif; ?>
						<?php do_action('rtcl_after_author_meta', $listing->get_owner_id()); ?>
						<?php
						$status = apply_filters('rtcl_user_offline_text', esc_html__('Offline Now', 'listygo'));
                        $class = 'offline now';
						if (Fns::is_online($listing->get_owner_id())) {
							$class = 'online now';
							$status = apply_filters('rtcl_user_online_text', esc_html__('Online Now', 'listygo'));
						}
						?>
						<div class="rtin-box-item rtcl-user-status <?php echo esc_attr($class); ?>">
							<span><?php echo esc_html($status); ?></span>
						</div>
					</h4>
				</div>
			<?php } ?>
			<ul class="info-list">
				<?php if (current($listing->user_contact_location_at_single())) : ?>
					<li>
						<?php echo Helper::map_icon(); ?>
						<?php
						if ($location_type == 'geo' && !empty($geo_address)) {
							echo esc_html($geo_address);
						} else {
							echo esc_html($address);
						}
						?>
					</li>
				<?php endif; ?>
				<?php if ($phone) : ?>
					<li>
						<a class="rtcl-phone-link" href="tel:<?php echo esc_attr($phone); ?>" target="_blank">
							<?php echo Helper::phone_icon(); ?>
							<?php echo esc_html($phone); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if ($whatsapp) : ?>
					<li>
						<a class="rtcl-whatsapp-link" href="https://wa.me/<?php echo esc_attr($whatsapp); ?>" target="_blank">
							<i class="fab fa-whatsapp"></i>
							<?php echo esc_html($whatsapp); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if ($website) : ?>
					<li>
						<a class="rtcl-website-link" href="<?php echo esc_url($website); ?>" target="_blank" <?php echo Functions::is_external($website) ? ' rel="nofollow"' : ''; ?>>
							<?php echo Helper::globe_icon(); ?>
							<?php echo esc_html($website_url); ?>
						</a>
					</li>
				<?php endif; ?>
			</ul>
			<?php
			if (RDTListygo::$options['listing_list_socials']) {
				do_action('rtcl_single_listing_social_profiles');
			}
			?>
		</div>
	<?php } ?>
</div>
