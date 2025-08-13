<?php
/**
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */
?>
<form id="rtcl-contact-form" class="form-vertical">
	<div class="rtcl-form-group">
		<input type="text" name="name" class="rtcl-form-control" id="rtcl-contact-name"
			   value="<?php echo is_user_logged_in() ? esc_attr( wp_get_current_user()->user_login ) : '' ?>"
			   placeholder="<?php esc_attr_e( "Name *", "classified-listing" ) ?>"
			   required/>
	</div>
	<div class="rtcl-form-group">
		<input type="email" name="email" class="rtcl-form-control" id="rtcl-contact-email"
			   value="<?php echo is_user_logged_in() ? esc_attr( wp_get_current_user()->user_email ) : '' ?>"
			   placeholder="<?php esc_attr_e( "Email *", "classified-listing" ) ?>"
			   required/>
	</div>
	<div class="rtcl-form-group">
		<input type="text" name="phone" class="rtcl-form-control" id="rtcl-contact-phone"
			   placeholder="<?php esc_attr_e( "Phone", "classified-listing" ) ?>"/>
	</div>
	<div class="rtcl-form-group">
        <textarea class="rtcl-form-control" name="message" id="rtcl-contact-message" rows="3"
				  placeholder="<?php esc_attr_e( "Message*", "classified-listing" ) ?>"
				  required></textarea>
	</div>

	<div id="rtcl-contact-g-recaptcha"></div>
	<p id="rtcl-contact-message-display"></p>

	<button type="submit"
			class="rtcl-btn rtcl-btn-primary"><?php esc_html_e( "Submit", "classified-listing" ) ?>
	</button>
</form>
