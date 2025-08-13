<?php
/**
 *
 * @author        RadiusTheme
 * @package    classified-listing/templates
 * @version     3.0.1
 *
 * @var Form $form
 * @var array $fields
 * @var int $listing_id
 */


use Rtcl\Helpers\Functions;
use Rtcl\Models\Form\Form;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

if ( !is_a( $form, Form::class ) ) {
	return;
}

$fields = $form->getArchiveViewAbleFields();

if ( count( $fields ) ) :
	$fields = FBHelper::reOrderCustomField( $fields );
	ob_start();
	foreach ( $fields as $fieldName => $field ) {
		$field = new FBField( $field );
		$value = $field->getFormattedCustomFieldValue( $listing_id );
		if ( empty( $value ) ) {
			continue;
		}
		$icon = $field->getIconData();
		?>
		<div class='rtcl-listable-item element-<?php echo esc_attr( $field->getElement() ) ?>'>
			<?php if ( ( !empty( $icon['type'] ) && 'class' === $icon['type'] && !empty( $icon['class'] ) ) || !empty( $field->getLabel() ) ) {
				?>
				<div class="listable-label-wrap">
					<?php
					if ( !empty( $icon['type'] ) && 'class' === $icon['type'] && !empty( $icon['class'] ) ) {
						?>
						<div class="rtcl-field-icon"><i class="<?php echo esc_attr( $icon['class'] ) ?>"></i></div>
					<?php }
					if ( !empty( $field->getLabel() ) ) { ?>
						<div class='listable-label'><?php echo esc_html( $field->getLabel() ) ?></div>
						<?php
					} ?>
				</div>
			<?php } ?>
			<div class='listable-value'>
				<?php if ( 'repeater' === $field->getElement() ) {
					$repeaterFields = $field->getData( 'fields', [] );
					if ( !empty( $repeaterFields ) && is_array( $value ) ) { ?>
						<div class="listable-repeater-items">
							<?php
							foreach ( $value as $rValueIndex => $rValues ) {
								?>
								<div class="listable-repeater-item">
									<?php
									foreach ( $repeaterFields as $repeaterField ) {
										$rField = new FBField( $repeaterField );
										$rValue = $rValues[$rField->getName()] ?? '';
										$rIcon = $rField->getIconData();
										?>
										<div class="listable-repeater-field">
											<?php if ( ( !empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && !empty( $rIcon['class'] ) ) || !empty( $rField->getLabel() ) ) {
												?>
												<div class="listable-label-wrap">
													<?php
													if ( !empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && !empty( $rIcon['class'] ) ) {
														?>
														<div class="rtcl-field-icon">
															<i class="<?php echo esc_attr( $rIcon['class'] ) ?>"></i>
														</div>
													<?php }
													if ( !empty( $rField->getLabel() ) ) { ?>
														<div class='listable-label'>
															<?php echo esc_html( $rField->getLabel() ) ?>
														</div>
														<?php
													} ?>
												</div>
											<?php } ?>
											<div class="listable-value">
												<?php Functions::print_html( FBHelper::getFormattedFieldHtml( $rValue, $rField ) ); ?>
											</div>
										</div>
										<?php
									} ?>
								</div>
								<?php
							} ?>
						</div>
						<?php
					}
				} else {
					Functions::print_html( FBHelper::getFormattedFieldHtml( $value, $field ) );
				} ?>
			</div>
		</div>
		<?php
	}

	$fields_html = ob_get_clean();
	if ( $fields_html ) {
		printf( '<div class="rtcl-listable">%s</div>', $fields_html );
	}
endif;
