<?php
/**
 * Common function
 *
 * @author Golam Samdani
 * @package GS WooCommerce Variation Swatches
 * @version 1.0.0
 */


if( ! function_exists( 'gs_get_term_meta' ) ) {
    /**
     * Get term meta. If WooCommerce version is >= 2.6 use get_term_meta else use get_woocommerce_term_meta
     *
     * @param $term_id
     * @param $key
     * @param bool $single
     *
     * @return mixed
     * @author Golam Samdani
     */
    function gs_get_term_meta( $term_id, $key, $single = true ) {
        if ( gs_check_wc_version( '2.6', '>=' ) ) {
            return function_exists( 'get_term_meta' ) ? get_term_meta( $term_id, $key, $single ) : get_metadata( 'woocommerce_term', $term_id, $key, $single );
        } else {
            return get_woocommerce_term_meta( $term_id, $key, $single );
        }
    }
}

if( ! function_exists( 'gs_update_term_meta' ) ) {
    /**
     * Get term meta. If WooCommerce version is >= 2.6 use update_term_meta else use update_woocommerce_term_meta
     *
     * @param string|int $term_id
     * @param string $meta_key
     * @param mixed $meta_value
     * @param mixed $prev_value
     *
     * @return bool
     * @author Golam Samdani
     */
    function gs_update_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' ) {
        if ( gs_check_wc_version( '2.6', '>=' ) ) {
            return function_exists( 'update_term_meta' ) ? update_term_meta( $term_id, $meta_key, $meta_value, $prev_value ) : update_metadata( 'woocommerce_term', $term_id, $meta_key, $meta_value, $prev_value );
        } else {
            return update_woocommerce_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' );
        }
    }
}

if( ! function_exists( 'gs_get_custom_tax_types' ) ) {
    /**
     * Return custom product's attributes type
     *
     * @author Golam Samdani
     * @since 1.2.0
     * @return mixed|void
     */
    function gs_get_custom_tax_types() {
        return apply_filters( 'gs_get_custom_tax_types', array(
            'colorpicker' => __( 'Colorpicker', 'gs-variation' ),
            'image'       => __( 'Image', 'gs-variation' ),
            'label'       => __( 'Label', 'gs-variation' )
        ) );
    }
}

if( ! function_exists( 'gs_check_wc_version' ) ) {
    /**
     * Check installed WooCommerce version
     *
     * @since 1.3.0
     * @author Golam Samdani
     * @param string $version
     * @param string $operator
     * @return boolean
     */
    function gs_check_wc_version( $version, $operator ) {
        return version_compare( WC()->version, $version, $operator );
    }
}