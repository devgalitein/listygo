<?php
/**
 *
 * @var array $fields
 * @var int   $listing_id
 * @version       1.0.0
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 */

use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\RDTListygo;
$show_custom_fields_label = RDTListygo::$options['show_custom_fields_label'];

$cfgc = 'label-disable';
if ($show_custom_fields_label) {
    $cfgc = 'label-enable';
}

if ( count( $fields ) ) :
	ob_start();
	foreach ( $fields as $field ) :

		$field = new RtclCFGField( $field->ID );
		$value = $field->getFormattedCustomFieldValue( $listing_id );

		if ( $value ) :
			?>
            <li>
                <?php if ( $show_custom_fields_label ) { ?>
    				<?php if ( $field->getIconClass() ): ?>
                        <i class='rtcl-icon rtcl-icon-<?php echo esc_html( $field->getIconClass() ); ?>'></i>
    				<?php else: ?>
                        <span class='listable-label'><?php echo esc_html( $field->getLabel() ); ?></span>
    				<?php endif; ?>
                <?php } ?>

                <span class='listable-value'>
                    <span class="value">
                    <?php
                    $value = ( ! is_array( $value ) && strlen( $value ) == 1 ) ? $value : $value;
                        echo stripslashes_deep( $value );
                    ?>
                    </span>
                </span>
            </li>
		<?php endif;
	endforeach;

	$fields_html = ob_get_clean();
	if ( $fields_html ) {
		printf( '<ul class="product-features %s">%s</ul>', $cfgc, $fields_html );
	}
endif;
