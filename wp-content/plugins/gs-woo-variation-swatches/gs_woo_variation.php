<?php 
/**
 *
 * @package   GS_WooCommerce_variation_swatches
 * @author    Golam Samdani <samdani1997@gmail.com>
 * @license   GPL-2.0+
 * @link      https://www.gsamdani.com
 * @copyright 2018 Golam Samdani
 *
 * @wordpress-plugin
 * Plugin Name:         GS WooCommerce Variation Swatches
 * Plugin URI:          https://www.gsamdani.com/wordpress-plugins
 * Description:         GS WooCommerce Variation Swatches plugin replaces the dropdown select of your variable products with Colors, Labels & Images. It's an extension of WooCommerce to create product variations for users to maximize product sale. Check documention here <a href="http://woovariation.gsamdani.com/documentation">WooCommerce Variation Swatches</a> 
 * Version:             1.2
 * Author:              Golam Samdani
 * Author URI:          https://www.gsamdani.com
 * Text Domain:         gs-variation
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 */

if( ! defined( 'GS_WOO_HACK_MSG' ) ) define( 'GS_WOO_HACK_MSG', __( 'Sorry cowboy! This is not your place', 'gs-variation' ) );

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) die( GS_WOO_HACK_MSG );

/**
 * Defining constants
 */
if( ! defined( 'GS_WOO_VERSION' ) ) define( 'GS_WOO_VERSION', '1.2' );
if( ! defined( 'GS_WOO_MENU_POSITION' ) ) define( 'GS_WOO_MENU_POSITION', 31 );
if( ! defined( 'GS_WOO_PLUGIN_DIR' ) ) define( 'GS_WOO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if( ! defined( 'GS_WOO_FILES_DIR' ) ) define( 'GS_WOO_FILES_DIR', GS_WOO_PLUGIN_DIR . 'includes' );
if( ! defined( 'GS_WOO_PLUGIN_URI' ) ) define( 'GS_WOO_PLUGIN_URI', plugins_url( '', __FILE__ ) );
if( ! defined( 'GS_WOO_FILES_URI' ) ) define( 'GS_WOO_FILES_URI', GS_WOO_PLUGIN_URI . '/includes' );

require_once GS_WOO_FILES_DIR . '/class-woov-setting.php';
include_once GS_WOO_FILES_DIR . '/gs-woocommerce-custom-function.php';
include_once GS_WOO_PLUGIN_DIR . '/gs-plugins/gs-plugins.php';
include_once GS_WOO_PLUGIN_DIR . '/gs-plugins/gs-plugins-free.php';
include_once GS_WOO_PLUGIN_DIR . '/gs-plugins/gs-wv-help.php';

if ( ! function_exists('gs_wvariation_pro_link') ) {
    function gs_wvariation_pro_link( $gsVars_links ) {
        $gsVars_links[] = '<a class="gs-pro-link" href="https://www.gsamdani.com/product/woocommerce-variation-swatches" target="_blank">Go Pro!</a>';
        $gsVars_links[] = '<a href="https://www.gsamdani.com/wordpress-plugins" target="_blank">GS Plugins</a>';
        return $gsVars_links;
    }
    add_filter( 'plugin_action_links_' .plugin_basename(__FILE__), 'gs_wvariation_pro_link' );
}

function gsw_activation_process(){
    if ( ! function_exists( 'gsw_activation' ) ) {
        require_once GS_WOO_FILES_DIR . '/templates/admin/function.activation.php';
    }

    function_activation();
}
register_activation_hook( __FILE__, 'gsw_activation_process' );


if( ! class_exists( 'GS_Woo_Variation' ) ){
    /**
     * GS_Woo_Variation class
     */
    class GS_Woo_Variation {
        
        /**
         * Singleton Instance
         *
         * @access private static
         */
        private static $_instance;
        
        /**
         * $post_type ARRAY An array of post type configuration
         *
         * @access public
         */
     
        
        public $_data;
        public $custom_types = array();
        /**
         * @var boolean Check if WooCommerce is 2.7
         */
        public $wc_is_27 = false;
        
        /**
         * Class Contructor
         */
        public function __construct() {
            $this->custom_types  = gs_get_custom_tax_types();
            $this->wc_is_27      = gs_check_wc_version( '2.7', '>=' );
            $this->_data = new Gs_Options( 'gsv', '' );
            add_action( 'admin_enqueue_scripts', array( &$this, 'load_pdf_admin_script' ) );
            add_action( 'admin_footer', array( $this, 'add_description_field' ) );
            add_action( 'woocommerce_attribute_added', array( $this, 'attribute_add_description_field' ), 10, 2 );
            add_action( 'admin_menu', array( &$this, 'variation_settings_menu' ) );
            add_action( 'admin_action_save_pdf_settings', array( &$this, 'save_pdf_settings' ) );
            add_filter( 'gs_woo_settings_save_before', array( &$this, 'gs_woo_settings_save_before_cb' ), 99, 1 );
            add_action( 'woocommerce_attribute_updated', array( $this, 'attribute_update_description_field' ), 10, 3 );
            add_action( 'init', array( $this, 'attribute_taxonomies' ) );
            add_action( 'gs_woov_print_attribute_field', array( $this, 'print_attribute_type' ), 10, 3 );
            add_filter( 'product_attributes_type_selector', array( $this, 'attribute_types' ), 10, 1 );
            add_action( 'woocommerce_product_option_terms', array($this, 'product_option_terms' ), 10, 2 );
            add_action( 'admin_footer', array( $this, 'product_option_add_terms_form' ) );
            add_action( 'created_term', array( $this, 'attribute_save' ), 10, 3 );
            add_action( 'edit_term', array( $this, 'attribute_save' ), 10, 3 );
            add_action( 'wp_ajax_gs_woov_add_new_attribute', array( $this, 'gs_woov_add_new_attribute_ajax' ) );
            add_action( 'wp_ajax_nopriv_gs_woov_add_new_attribute', array( $this, 'gs_woov_add_new_attribute_ajax' ) );

            require_once GS_WOO_FILES_DIR . '/class-woo-frontend.php';  
        }
        
        /**
         * Get class singleton instance
         *
         * @return Class Instance
         */
        public static function get_instance() {
            if ( ! self::$_instance instanceof GS_Woo_Variation ) {
                self::$_instance = new GS_Woo_Variation();
            }
            return self::$_instance;
        }
        
        public function load_pdf_admin_script() {
            $media = 'all';
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script( 'wp-color-picker');
            wp_enqueue_media();
            wp_enqueue_style( 'var-admin-css', GS_WOO_PLUGIN_URI. '/assets/css/variation-admin.css', '', GS_WOO_VERSION );
            wp_enqueue_script( 'gs-admin-script', GS_WOO_PLUGIN_URI . '/assets/js/script-admin.js', array('jquery', 'wp-color-picker', 'jquery-ui-dialog' ), GS_WOO_VERSION, true);
            wp_localize_script( 'gs-admin-script', 'gs_woov_admin', array( 'ajaxurl'   => admin_url( 'admin-ajax.php' ), ));
            wp_register_style( 'gs-var-free-plugins-style', GS_WOO_PLUGIN_URI . '/assets/css/gs_free_plugins.css', '', GS_WOO_VERSION, $media );
            wp_enqueue_style( 'gs-var-free-plugins-style' );
        }

        public function variation_settings_menu() {
            add_submenu_page( 
                'gsp-main', 
                'GS Woocommerce Variation Settings', 
                'Woo Variation Settings', 
                'manage_options', 
                'gs-variation-setting', 
                array( $this, 'variation_settings_content' )
                );
            
        }

        public function add_description_field(){
            global $pagenow, $wpdb;

            if( ! ( 'edit.php' == $pagenow && isset( $_GET['post_type'] ) && 'product' == $_GET['post_type'] && isset( $_GET['page'] ) && $_GET['page'] == 'product_attributes' ) ) {
                return;
            }

            $edit = isset( $_GET['edit'] ) ? absint( $_GET['edit'] ) : false;
            $att_description = false;

            if( $edit ) {
                $attribute_to_edit = $wpdb->get_var( "SELECT meta_value FROM " . $wpdb->prefix . "gs_woo_meta WHERE wc_attribute_tax_id = '$edit'" );
                $att_description  = isset( $attribute_to_edit ) ? $attribute_to_edit : false;
            }

            ob_start();

            wc_get_template( 'template.php', array(
                'value'   => $att_description,
                'edit'    => $edit
            ), '', GS_WOO_FILES_DIR . '/templates/admin/' );

            $html = ob_get_clean();

            
            wp_localize_script( 'gs-admin-script', 'gs_admin', array(
                'html' => $html
            ) );

        }

        public function attribute_add_description_field( $id, $attribute ) {
            global $wpdb;

            // get attribute description
            $descr = isset( $_POST['gs_attribute_description'] ) ? wc_clean( $_POST['gs_attribute_description'] ) : '';

            // insert db value

            if( $descr ) {
                $attr = array();

                $attr['wc_attribute_tax_id'] = $id;
                // add description
                $attr['meta_key']   = '_gs_attribute_description';
                $attr['meta_value'] = $descr;

                $wpdb->insert( $wpdb->prefix . 'gs_woo_meta', $attr );
            }
        }

        public function attribute_update_description_field( $id, $attribute, $old_attributes ) {
            global $wpdb;

            $descr = isset( $_POST['gs_attribute_description'] ) ? wc_clean( $_POST['gs_attribute_description'] ) : '';

            // get meta value
            $meta = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "gs_woo_meta WHERE wc_attribute_tax_id = %d", $id ) );

            if( ! isset( $meta ) ) {
                $this->attribute_add_description_field(  $id, $attribute );
            }
            elseif( $meta->meta_value != $descr ) {
                $attr = array();
                $attr['meta_value'] = $descr;
                $wpdb->update( $wpdb->prefix . 'gs_woo_meta', $attr, array( 'meta_id' => $meta->meta_id ) );
            }
        }

        public function attribute_taxonomies(){

            $attribute_taxonomies = wc_get_attribute_taxonomies();

            if ( $attribute_taxonomies ) {
                foreach ( $attribute_taxonomies as $tax ) {

                    // check if tax is custom
                    if( ! array_key_exists( $tax->attribute_type, $this->custom_types ) ) {
                        continue;
                    }

                    add_action( wc_attribute_taxonomy_name( $tax->attribute_name ) . '_add_form_fields', array( $this, 'add_attribute_field' ) );
                    add_action( wc_attribute_taxonomy_name( $tax->attribute_name ) . '_edit_form_fields', array( $this, 'edit_attribute_field' ), 10, 2 );
                    add_filter( 'manage_edit-' . wc_attribute_taxonomy_name( $tax->attribute_name ) . '_columns', array( $this, 'product_attribute_columns' ) );
                    add_filter( 'manage_' . wc_attribute_taxonomy_name( $tax->attribute_name ) . '_custom_column', array( $this, 'product_attribute_column' ), 10, 3 );
                }
            }
        }
        public function add_attribute_field( $taxonomy ) {
            global $wpdb;

            $attribute = substr( $taxonomy, 3 );
            $attribute = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attribute'" );

            $values = array(
                'value'    => array(
                    'value' => false,
                    'label' => $this->custom_types[ $attribute->attribute_type ],
                    'desc'  => ''
                ),
                'tooltip'  => array(
                    'value' => false,
                    'label' => __( 'Tooltip', 'gs-variation' ),
                    'desc'  => __( 'Use this placeholder {show_image} to show the image on tooltip. Only available for image type', 'gs-variation' ),
                ),
            );

            do_action( 'gs_woov_print_attribute_field', $attribute->attribute_type, $values );
        }

        public function edit_attribute_field( $term, $taxonomy ) {
            global $wpdb;

            $attribute = substr( $taxonomy, 3 );
            $attribute = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attribute'" );

            $values = array(
                'value'    => array(
                    'value' => gs_get_term_meta( $term->term_id, $taxonomy . '_gs_woov_value' ),
                    'label' => $this->custom_types[ $attribute->attribute_type ],
                    'desc'  => ''
                ),
                'tooltip'  => array(
                    'value' => gs_get_term_meta( $term->term_id, $taxonomy . '_gs_woov_tooltip' ),
                    'label' => __( 'Tooltip', 'gs-variation' ),
                    'desc'  =>  __( 'Use this placeholder {show_image} to show the image on tooltip. Only available for image type', 'gs-variation' ),
                ),
            );

            do_action( 'gs_woov_print_attribute_field', $attribute->attribute_type, $values, true );
        }



        public function print_attribute_type( $type, $args, $table = false ){

            foreach( $args as $key => $arg ) :

                $data  = $key == 'value' ? 'data-type="' . $type . '"' : '';
                $id    = $name = "term_{$key}";
                $values = explode(',', $arg['value'] );
                $value  = $values[0];
                $value_2 = '';
                if( $key == 'value' && $type == 'colorpicker' ){
                    // change name
                    $name .= '[]';
                    isset( $values[1] ) && $value_2 = $values[1];
                }

                if( $table ): ?>
                    <tr class="form-field">
                    <th scope="row" valign="top"><label for="term_<?php echo $key ?>"><?php echo $arg['label'] ?></label></th>
                    <td>
                <?php else: ?>
                    <div class="form-field">
                    <label for="term_<?php echo $key ?>"><?php echo $arg['label'] ?></label>
                <?php endif ?>

                <input type="text" class="gs" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>" <?php echo $data ?>/>
                <?php if( $key == 'value' && $type == 'colorpicker' ) : ?>
                    <span class="gs_woov_add_color_icon" data-content="<?php echo $value_2 ? '+' : '-' ?>"><?php echo $value_2 ? '-' : '+'; ?></span><br>
                    <input type="text" class="gs hidden_empty" name="<?php echo $name ?>" id="<?php echo $id ?>_2" value="<?php echo $value_2 ?>" <?php echo $data ?>/>
                <?php endif; ?>

                <p><?php echo $arg['desc'] ?></p>

                <?php if( $table ): ?>
                    </td>
                    </tr>
                <?php else: ?>
                    </div>
                <?php endif;
            endforeach;
        }

        public function attribute_types( $default_type ){
            $custom = gs_get_custom_tax_types();
            return is_array( $custom ) ? array_merge( $default_type, $custom ) : $default_type;
        }

        public function product_option_terms( $taxonomy, $i ) {

            if( ! array_key_exists( $taxonomy->attribute_type, $this->custom_types ) ) {
                return;
            }

            global $thepostid;

            $attribute_taxonomy_name = wc_attribute_taxonomy_name( $taxonomy->attribute_name );
            
            ?>
            <select multiple="multiple" data-placeholder="<?php _e( 'Select terms', 'gs-variation' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo $i; ?>][]">
                <?php
                $all_terms = $this->get_terms( $attribute_taxonomy_name );
                if ( $all_terms ) {
                    foreach ( $all_terms as $term ) {
                         echo '<option value="' . esc_attr( $term['value'] ) . '" ' . selected( has_term( absint( $term['id'] ), $attribute_taxonomy_name, $thepostid ), true, false ) . '>' . $term['name'] . '</option>';
                    }
                }
                ?>
            </select>
            <button class="button plus select_all_attributes"><?php _e( 'Select all', 'gs-variation' ); ?></button>
            <button class="button minus select_no_attributes"><?php _e( 'Select none', 'gs-variation' ); ?></button>
            <?php
        }

        public function product_option_add_terms_form() {

            global $pagenow, $post;

            if( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) || ( isset( $post ) && get_post_type( $post->ID ) != 'product' ) ){
                return;
            }

            ob_start();

            ?>

            <div id="gs_woov_dialog_form" title="<?php _e( 'Create new attribute term','gs-variation' ); ?>" style="display:none;">
                <span class="dialog_error"></span>
                <form>
                    <fieldset>
                        <label for="term_name"><?php _e( 'Name', 'gs-variation' ) ?>:
                            <input type="text" name="term_name" id="term_name" value="" >
                        </label>
                        <label for="term_slug"><?php _e( 'Slug', 'gs-variation' ) ?>:
                            <input type="text" name="term_slug" id="term_slug" value="">
                        </label>
                        <label for="term_value"><?php _e( 'Value', 'gs-variation' ); ?>:
                            <input type="text" class="gs" name="term_value[]" id="term_value" value="" data-type="label">
                            <span class="gs_woov_add_color_icon" data-content="-">+</span><br>
                            <input type="text" class="gs hidden_empty" name="term_value[]" id="term_value_2" value="" data-type="label">
                        </label>
                        <label for="term_tooltip"><?php _e( 'Tooltip', 'gs-variation' ); ?>:
                            <input type="text" name="term_tooltip" id="term_tooltip" value="">
                        </label>
                    </fieldset>
                </form>
            </div>

            <?php

            echo ob_get_clean();
        }


        protected function get_terms( $tax_name ) {
            global $wp_version;
            
            if( version_compare($wp_version, '4.5', '<' ) ) {
                $terms = get_terms( $tax_name, array(
                    'orderby'       => 'name',
                    'hide_empty'    => '0'
                ) );
            }
            else {
                $args = array(
                    'taxonomy'      => $tax_name,
                    'orderby'       => 'name',
                    'hide_empty'    => '0'
                );
                // get terms
                $terms = get_terms( $args );
            }
            
            $all_terms = array();
            
            foreach( $terms as $term ) {
                $all_terms[] = array(
                    'id'    => $term->term_id,
                    'value' => $this->wc_is_27 ? $term->term_id : $term->slug,
                    'name'  => $term->name
                );
            }
            
            return $all_terms;
        }

        public function product_attribute_columns( $columns ) {

            if( empty( $columns ) ) {
                return $columns;
            }


            $temp_cols = array();
            // checkbox
            $temp_cols['cb'] = $columns['cb'];
            // value
            $temp_cols['gs_woov_value'] = __( 'Value', 'gs-variation' );

            unset( $columns['cb'] );
            $columns = array_merge( $temp_cols, $columns );

            return $columns;
        }

       public function product_attribute_column( $columns, $column, $id ) {
            global $taxonomy, $wpdb;

            if ( $column == 'gs_woov_value' ) {

                $attribute  = substr( $taxonomy, 3 );
                $attribute  = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attribute'");
                $att_type   = $attribute->attribute_type;

                $value = gs_get_term_meta( $id, $taxonomy . '_gs_woov_value' );
                $columns .= $this->_print_attribute_column( $value, $att_type );
            }

            return $columns;
        }

        protected function _print_attribute_column( $value, $type ) {
            $output = '';

            if( $type == 'colorpicker' ) {

                $values = explode(',', $value );
                if( isset( $values[1] ) && $values[1] ) {
                    $style = "border-bottom-color:{$values[0]};border-left-color:{$values[1]}";
                    $output = '<span class="gs-wccl-color"><span class="gs-woov-bicolor" style="'.$style.'"></span></span>';
                }
                else {
                    $output = '<span class="gs-wccl-color" style="background-color:'. $values[0] .'"></span>';    
                }
            }
            elseif( $type == 'label' ) {
                $output = '<span class="gs-wccl-label">'. $value .'</span>';
            }
            elseif( $type == 'image' ) {
                $output = '<img class="gs-woov-image" src="'. $value .'" alt="" />';
            }

            return $output;
        }

        public function attribute_save( $term_id, $tt_id, $taxonomy ) {

            if( isset( $_POST['term_value'] ) ) {

                if( is_array( $_POST['term_value'] ) ){
                    // first remove empty values
                    $array_values = array_filter( $_POST['term_value'] );
                    if( empty( $array_values ) ) {
                        $value = '';
                    }
                    else {
                        $value = implode( ',', $array_values );
                    }
                }
                else {
                    $value = $_POST['term_value'];
                }

                gs_update_term_meta( $term_id, $taxonomy . '_gs_woov_value', $value );
            }
            if( isset( $_POST['term_tooltip'] ) ) {
                gs_update_term_meta( $term_id, $taxonomy . '_gs_woov_tooltip', $_POST['term_tooltip'] );
            }
        }

        public function gs_woov_add_new_attribute_ajax() {

            if( ! isset( $_POST['taxonomy'] ) || ! isset( $_POST['term_name'] ) || ! isset( $_POST['term_value'] ) ) {
                die();
            }

            $tax     = esc_attr( $_POST['taxonomy'] );
            $term    = wc_clean( $_POST['term_name'] );
            $slug    = wc_clean( $_POST['term_slug'] );
            $value   = wc_clean( implode( ',', array_filter( $_POST['term_value'] ) ) );
            $tooltip = wc_clean( $_POST['term_tooltip'] );
            $args    = array();

            if( $value == '' ) {
                wp_send_json( array(
                    'error' => __( 'A value is required for this term', 'gs-variation' )
                ) );
            }

            if ( taxonomy_exists( $tax ) ) {

                if( $slug ) {
                    $args['slug'] = $slug;
                }
                // insert term
                $result = wp_insert_term( $term, $tax, $args );

                if ( is_wp_error( $result ) ) {
                    wp_send_json( array(
                        'error' => $result->get_error_message()
                    ) );
                }
                else {
                    $term = get_term_by( 'id', $result['term_id'], $tax );

                    // add value
                    gs_update_term_meta( $term->term_id, $tax . '_gs_woov_value', $value );
                    if( $tooltip ) {
                        gs_update_term_meta( $term->term_id, $tax . '_gs_woov_tooltip', $tooltip );
                    }

                    wp_send_json( array(
                        'id'    => $term->term_id,
                        'value' => $this->wc_is_27 ? $term->term_id : $term->slug,
                        'name'  => $term->name
                    ) );
                }
            }

            die();
        }

        public function variation_settings_content() {
            $data = $this->_data->get_options( 'gsv' );
            ?>
            <div class="wrap">
                <h2><?php _e( 'GS Woocommerce Variation Settings', 'gs-variation ') ?></h2>
                
                <?php if( isset( $_REQUEST['msg'] ) && $_REQUEST['msg'] != '' ) { ?>
                <div class="<?php echo isset( $_REQUEST['notice'] ) && $_REQUEST['notice'] != '' ? $_REQUEST['notice'] : 'updated' ?>">
                    <p><?php echo urldecode( $_REQUEST['msg'] ) ?></p>
                </div>
                <?php } ?>
                
                
                <div id="poststuff gswvar" class="gswvar-options" style="width: 845px; float: left;">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                        	<h2 class="nav-tab-wrapper">  
					            <a href="<?php echo admin_url( 'admin.php?page=gs-variation-setting' ) ?>" class="nav-tab"><?php _e( 'WooCommerce Variation Swatches', 'gs-variation' ) ?></a>
					        </h2>
                            <div class="ev_tab_content">
                            <form action="<?php echo admin_url( '?action=save_pdf_settings' ) ?>" method="post">

                            <?php if( ! isset( $_REQUEST['tab'] ) || $_REQUEST['tab'] == 'genral' ) { ?>
                                <div class="postbox">
                                    <h3 class="hndle"><?php _e( 'General Settings', 'sn' ) ?></h3>
                                    <div class="inside">
                                        <table cellpadding="5" cellspacing="5" class="form-table">
                                            <tr>
                                                <th><?php _e( ' Attribute behavior:', 'gs-variation' ) ?></th>
                                                <td>
                                                    <input type="radio" name="gs_settings[attribute_behavior]" value="hide" <?php checked( isset( $data['attribute_behavior'] ) && $data['attribute_behavior']=='hide'  ); ?> >Hide
                                                    <input type="radio" name="gs_settings[attribute_behavior]" value="blur" <?php checked( isset( $data['attribute_behavior'] ) && $data['attribute_behavior']=='blur' ) ;?>>Blur
                                                  
                                                </td>
                                            </tr>
                                            
                                            
                                            <tr>
                                                <th><?php _e( 'Show Attribute Description', 'gs-variation' ) ?></th>
                                                <td>
                                              
                                                    <input <?php checked( isset( $data['show_attribute_description'] ) ? $data['show_attribute_description'] : 0, 1, true ) ?> type="checkbox" name="gs_settings[show_attribute_description]" value="1">
                                                    <span class="option-desc">Choose to show description below each attribute in single product page</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th><?php _e( 'Enable plugin in archive pages', 'gs-variation' ) ?></th>
                                                <td>
                                                    <input <?php checked( isset( $data['enable_archive_page'] ) ? $data['enable_archive_page'] : 0, 1, true ) ?> type="checkbox" name="gs_settings[enable_archive_page]" value="1">
                                                    <span class="option-desc">Choose to show attribute selection in archive shop pages.</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th><?php _e( 'Enable ajax in archive pages', 'gs-variation' ) ?></th>
                                                <td>
                                                    <input <?php checked( isset( $data['enable_ajax_archive_page'] ) ? $data['enable_ajax_archive_page'] : 0, 1, true ) ?> type="checkbox" name="gs_settings[enable_ajax_archive_page]" value="1">
                                                    <span class="option-desc">Enable ajax  in archive shop pages.</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th><?php _e( "Label for 'Add to cart' button", 'gs-variation' ) ?></th>
                                                <td>
                                              
                                                    <input type="text" name="gs_settings[cart_button]" value="<?php echo isset ( $data['cart_button'] ) ? esc_html( $data['cart_button'] ): 'add to cart' ?>">
                                                    <span class="option-desc">Set Preffered 'Add to cart' Button Text when a variation is selected.</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th><?php _e( 'Form Position', 'gs-variation' ) ?></th>
                                                <td>
                                                   <select name="gs_settings[form_position]">
                                                        <option <?php echo isset( $data['form_position'] ) && $data['form_position'] == 'before' ? 'selected' : '' ?> value="before"><?php _e( ' Before add to cart button ', 'gs-variation' ) ?></option>
                                                        <option <?php echo isset( $data['form_position'] ) && $data['form_position'] == 'after' ? 'selected' : '' ?> value="after"><?php _e( ' After add to cart button', 'gs-variation' ) ?></option>
                                                    </select>
                                                    <span class="option-desc">Choose the form position in archive shop page.</span>
                                                </td>
                                            </tr>

                                             
                                        </table>
                                    </div>
                                </div>
                                
                            <?php } ?>
                          

                            <p class="submit">
                                    <input type="submit" class="button button-primary" value="<?php _e( 'Save Settings', 'gs-variation' ) ?>" name="gs_settings[submit]">
                                </p>
                            </form>
                            </div>
                        </div>
                   
                    </div>
                </div>
            </div>





            <div class="gswvar-admin-sidebar" style="width: 277px; float: left; margin: 62px 0 0 22px; text-align: center;">
                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Support / Report a bug' ) ?></span></h3>
                    <div class="inside centered">
                        <p>Please feel free to let me know if you got any bug to report. Your report / suggestion can make the plugin awesome!</p>
                        <p><a href="https://www.gsamdani.com/support" target="_blank" class="button button-primary">Get Support</a></p>
                    </div>
                </div>
                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Buy me a coffee' ) ?></span></h3>
                    <div class="inside centered">
                        <p>If you like the plugin, please buy me a coffee to inspire me to develop further.</p>
                        <p><a href='https://www.2checkout.com/checkout/purchase?sid=202460873&quantity=1&product_id=1' class="button button-primary" target="_blank">Donate</a></p>
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Subscribe to NewsLetter' ) ?></span></h3>
                    <div class="inside centered">
                        <p>Sign up today & be the first to get notified on new plugin updates. Your information will never be shared with any third party.</p>
                            <!-- Begin MailChimp Signup Form -->
                        <link href="//cdn-images.mailchimp.com/embedcode/slim-081711.css" rel="stylesheet" type="text/css">
                        <style type="text/css">
                            #mc_embed_signup{background:#fff; clear:left; font:13px "Open Sans",sans-serif; }
                            /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                               We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                        </style>
                        <div id="mc_embed_signup">
                        <form action="//gsamdani.us11.list-manage.com/subscribe/post?u=92f99db71044540329de15732&amp;id=2600f1ae0f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate style="padding: 0;">
                            <div id="mc_embed_signup_scroll">
                            
                            <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Enter your Email address" required style="width: 100%; border:1px solid #E2E1E1; text-align: center;">
                            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                            <div style="position: absolute; left: -5000px;"><input type="text" name="b_92f99db71044540329de15732_2600f1ae0f" tabindex="-1" value=""></div>
                            <div class="clear" style="text-align: center; display: block;">
                                <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button button-primary" style="display: inline; margin: 0; background: #00a0d2; font-size: 13px;">
                            </div>
                            </div>
                        </form>
                        </div>
                        <!--End mc_embed_signup-->
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Join GS Plugins on facebook' ) ?></span></h3>
                    <div class="inside centered">
                        <iframe src="//www.facebook.com/plugins/likebox.php?href=https://www.facebook.com/gsplugins&amp;width&amp;height=258&amp;colorscheme=dark&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId=723137171103956" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:220px;" allowTransparency="true"></iframe>
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Follow GS Plugins on twitter' ) ?></span></h3>
                    <div class="inside centered">
                        <a href="https://twitter.com/gsplugins" target="_blank" class="button button-secondary">Follow @gsplugins<span class="dashicons dashicons-twitter" style="position: relative; top: 3px; margin-left: 3px; color: #0fb9da;"></span></a>
                    </div>
                </div>
            </div> <!-- end gswvar-admin-sidebar -->
            <?php
        }
        
        public function save_pdf_settings() {
            $data = $_POST['gs_settings'];

            $data = apply_filters( 'gs_woo_settings_save_before', $data );
            $this->_data->set_options( $data );
            wp_redirect( admin_url( 'admin.php?page=gs-variation-setting&notice=updated&msg=' . urlencode( __( 'Your settings is saved.', 'gs-variation' ) ) ) );
            
        }

        public function gs_woo_settings_save_before_cb( $data ){

            if( ! isset( $data['enable_tooltip'] ) ) $data['enable_tooltip'] = 0;
            if( ! isset( $data['show_attribute_description'] ) ) $data['show_attribute_description'] = 0;
            if( ! isset( $data['enable_archive_page'] ) ) $data['enable_archive_page'] = 0;
            if( ! isset( $data['enable_ajax_archive_page'] ) ) $data['enable_ajax_archive_page'] = 0; 
            return $data;
        }
    }
    
    add_action( 'init', 'init_this_class', 1 );
    function init_this_class() {
        gs_variation();
    }
    
    function gs_variation() {
        return GS_Woo_Variation::get_instance();
    }
}