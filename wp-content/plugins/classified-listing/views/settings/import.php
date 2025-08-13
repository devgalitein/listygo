<div class="rtcl-import-export rtcl">
	<div class="rtcl-ie-wrap" id="rtcl-import-wrap">
		<div class="import-location-categories">
			<h5><?php esc_html_e( 'Import Location, Categories & Settings', 'classified-listing' ); ?></h5>
			<form class="form" id="rtcl-import-form">
				<div class="rtcl-form-group rtcl-row">
					<label for="rtcl-import-file"
						   class="rtcl-col-sm-2 rtcl-field-label"><?php esc_html_e( 'Select JSON File', 'classified-listing' ); ?></label>
					<div class="rtcl-col-sm-10">
						<div class="rtcl-col-sm-10 custom-file" style="width: 250px;">
							<input type="file" class="custom-file-input rtcl-import-file" name="import-file"
								   id="rtcl-import-file" required>
							<label class="custom-file-label"
								   for="rtcl-import-file"><?php esc_html_e( 'Choose JSON file...', 'classified-listing' ); ?></label>
						</div>
					</div>
				</div>

				<button class="rtcl-btn rtcl-btn-primary" type="submit"
						id="rtcl-import-btn"><?php esc_html_e( 'Import', 'classified-listing' ); ?></button>
				<p class="description"><?php esc_html_e( 'Sample data', 'classified-listing' ); ?>
					<a href="https://gist.github.com/radiustheme/7a15605eac0a6a952d90e5853f5e9c39" target="_blank">
						<?php esc_html_e( 'click here', 'classified-listing' ); ?>
					</a>
				</p>
			</form>
		</div>
		<div class="import-listings">
			<h5><?php esc_html_e( 'Import Listings', 'classified-listing' ); ?></h5>
			<form method="post" name="rtcl-listings-import" enctype="multipart/form-data" action="">
				<div class="rtcl-form-group rtcl-row">
					<label for="rtcl-import-listing-file"
						   class="rtcl-col-sm-2 rtcl-field-label"><?php esc_html_e( 'Select CSV File', 'classified-listing' ); ?></label>
					<div class="rtcl-col-sm-10">
						<div class="rtcl-col-sm-10 custom-file" style="width: 250px;">
							<input type="file" class="custom-file-input rtcl-import-listing-file" name="rtcl-import-listing-file"
								   id="rtcl-import-listing-file" required>
							<label class="custom-file-label"
								   for="rtcl-import-listing-file"><?php esc_html_e( 'Choose CSV file...', 'classified-listing' ); ?></label>
						</div>
					</div>
				</div>
				<button class="rtcl-btn rtcl-btn-primary" type="submit"
						id="rtcl-import-listing-btn"><?php esc_html_e( 'Import', 'classified-listing' ); ?></button>
				<p class="description">
					<span><?php esc_html_e( 'PHP memory limit: 512M (Recommended)', 'classified-listing' ); ?></span>
					<span><?php esc_html_e( 'PHP max input variables: 3000 (Recommended)', 'classified-listing' ); ?></span>
				</p>
			</form>
		</div>
		<div id="import-response" class=""></div>
	</div>
</div>
<div class="rtcl rtcl-import-notice">
	<h6>Please don't refresh the page or don't click the back button.</h6>
	<p>The import process is still in progress. Refreshing or navigating away may cause errors or data loss. Kindly wait until the process is complete.</p>
</div>
<div class="rtcl rtcl-listings-import-mapping-wrapper"></div>