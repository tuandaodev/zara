<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<?php if( $edit ) : ?>

    <tr class="form-field form-required">
        <th scope="row" valign="top">
            <label for="attribute_public"><?php _e( 'Description', 'gs-variation' ); ?></label>
        </th>
        <td>
            <textarea name="gs_attribute_description" id="gs_attribute_description"><?php if( $value ) echo $value ?></textarea>
            <p class="description"><?php _e( 'Description for product attributes.', 'gs-variation' ); ?></p>
        </td>
    </tr>

<?php else: ?>

    <div class="form-field">
        <label for="gs_attribute_description"><?php _e( 'Description', 'gs-variation' ); ?></label>
        <textarea name="gs_attribute_description" id="gs_attribute_description"><?php if( $value ) echo $value ?></textarea>
        <p class="description"><?php _e( 'Description for product attributes.', 'gs-variation' ); ?></p>
    </div>

<?php endif; ?>