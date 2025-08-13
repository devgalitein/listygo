<?php

namespace RtclStore\Controllers\Admin;

use Rtcl\Helpers\Functions;

class StoreCategoryMetaHook {

	public static function init() {
		add_action( 'store_category_add_form_fields', array( __CLASS__, 'add_order_meta' ) );
		add_action( 'store_category_edit_form_fields', array( __CLASS__, 'edit_order_meta' ) );
		add_action( 'edited_store_category', [ __CLASS__, 'save_order_meta' ] );
		add_action( 'create_store_category', [ __CLASS__, 'save_order_meta' ] );
		add_filter( 'manage_store_category_custom_column', array( __CLASS__, 'add_order_column_value' ), 10, 3 );
		add_filter( 'manage_edit-store_category_columns', array( __CLASS__, 'add_order_columns' ), 10, 1 );
	}

	public static function add_order_meta() {
		?>
        <div class="form-field rtcl-term-group-wrap">
            <label for="tag-rtcl-order"><?php _e( 'Order', 'classified-listing-store' ); ?></label>
            <input type="number" name="_rtcl_order" id="tag-rtcl-order" value="">
            <p class="description"><?php _e( 'Enter an integer value for this order', 'classified-listing-store' ); ?></p>
        </div>
		<?php
	}

	public static function edit_order_meta( $term ) {
		$t_id      = $term->term_id;
		$term_meta = esc_attr( absint( get_term_meta( $t_id, "_rtcl_order", true ) ) );
		?>
        <tr class="form-field rtcl-term-group-wrap">
            <th scope="row" valign="top"><label
                        for="tag-rtcl-order"><?php _e( 'Order', 'classified-listing-store' ); ?></label></th>
            <td>
                <input type="number" name="_rtcl_order" id="tag-rtcl-order"
                       value="<?php echo $term_meta ? $term_meta : 0; ?>">
                <p class="description"><?php _e( 'Enter an integer value for this order', 'classified-listing-store' ); ?></p>
            </td>
        </tr>
		<?php
	}

	static function save_order_meta( $term_id ) {
		$oldOrder = absint( get_term_meta( $term_id, '_rtcl_order', true ) );
		if ( Functions::is_ajax() ) {
			$newOrder = isset( $_POST['_rtcl_order'] ) ? absint( $_POST['_rtcl_order'] ) : $oldOrder;
		} else {
			$newOrder = ! empty( $_POST['_rtcl_order'] ) ? esc_attr( absint( $_POST['_rtcl_order'] ) ) : 0;
		}
		update_term_meta( $term_id, '_rtcl_order', $newOrder );
	}

	public static function add_order_column_value( $content, $column_name, $term_id ) {
		if ( $column_name == '_rtcl_order' ) {
			$content = absint( get_term_meta( $term_id, '_rtcl_order', true ) );
		}

		return $content;
	}

	public static function add_order_columns( $columns ) {
		$order = array( '_rtcl_order' => __( 'Order', 'classified-listing-store' ) );

		return array_slice( $columns, 0, 2, true ) + $order + array_slice( $columns, 1, null, true );
	}
}