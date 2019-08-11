<?php
/**
 * Frontend class
 *
 * @version 1.0.0
 */
/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) die( GS_WOO_HACK_MSG );

if ( ! class_exists( 'Gs_Woov_Frontend' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class Gs_Woov_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \Gs_Woov_Frontend
		 * @since 1.0.0
		 */
		protected static $instance;

	

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $single_product_attributes = array();

		/**
		 * Returns single instance of the class
		 *
		 * @return \Gs_Woov_Frontend
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}
		public $_data;

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->_data = new Gs_Options( 'gsv', '' );

			add_action( 'template_redirect', array( $this, 'init_variables' ), 99 );

			add_action( 'woocommerce_before_single_product', array( $this, 'create_attributes_json' ) );

			// enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			//add select options in loop
			add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_select_options' ), 99, 2 );
			add_action( 'flatsome_product_box_after', array( $this, 'add_select_options' ), 99 );

			// add image shop_catalaog to available variation array
			add_filter( 'single_product_large_thumbnail_size', array( $this, 'set_shop_catalog_image' ) );

			//ajax add to cart
			if( version_compare( WC()->version, '2.4', '>=' ) ){
				add_action( 'wc_ajax_gs_woov_add_to_cart', array( $this, 'add_to_cart_ajax_loop' ) );
			}
			else {
				add_action( 'wp_ajax_gs_woov_add_to_cart', array( $this, 'add_to_cart_ajax_loop' ) );
			}
			add_action( 'wp_ajax_nopriv_gs_woov_add_to_cart', array( $this, 'add_to_cart_ajax_loop' ) );

			add_filter( 'woocommerce_available_variation', array( $this, 'loop_variations_attr' ), 99, 3 );

			// add_action( 'woocommerce_before_single_product', array( $this, 'remove_scripts_gift_card' ) );
			
			//compatibility with WooCommerce Quick View
			add_action( 'wc_quick_view_before_single_product', array( $this, 'wc_quick_view_json_product_attr' ) );
			add_action( 'woocommerce_single_product_lightbox_summary', array( $this, 'wc_quick_view_json_product_attr' ) );
			// add custom style also on tab Product Attributes
		    add_filter( 'woocommerce_attribute', array( $this, 'product_attributes_tab' ), 99, 3 );
		}

		/**
		 * Init plugin variables
		 *
		 * @since 1.2.0
		 * @author Golam Samdani
		 */
		public function init_variables(){

			global $post;

			if( is_null( $post ) ) {
				return;
			}

			// get product
			$product = wc_get_product( $post->ID );

			if( ! $product ) {
				return;
			}

			self::_create_custom_attributes_array( $product );
		}

		/**
		 * Dequeue scripts if product is gift card
		 *
		 * @since 1.0.7
		 * @author Golam Samdani
		 */
		public function remove_scripts_gift_card(){
			global $product;

			if( is_product() && $product->is_type( 'gift-card' ) ){
				wp_dequeue_script( 'wc-add-to-cart-variation' );
				wp_dequeue_script( 'gs-woo-frontend' );
				wp_dequeue_style( 'gs-woo-frontend' );
			}
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts(){
			$data = $this->_data->get_options( 'gsv' );

			
			global $post;

			if( isset( $data['enable_tooltip'] ) && $data['enable_tooltip']=='1'){
				$enable_tooltip=$data['enable_tooltip'];
			}else{
				$enable_tooltip='';
			}
			if( isset( $data['show_attribute_description'] ) && $data['show_attribute_description']=='1'){
				$show_attribute_description=$data['show_attribute_description'];
			}else{
				$show_attribute_description='';
			}

			if( isset( $data['image_on_hover'] ) && $data['image_on_hover']=='1'){
				$image_on_hover=$data['image_on_hover'];
			}else{
				$image_on_hover='';
			}

			if( isset( $data['attribute_behavior'] ) && $data['attribute_behavior']=='blur'){
				$attribute_behavior='blur';
			}elseif ( isset( $data['attribute_behavior'] ) && $data['attribute_behavior']=='hide') {
				$attribute_behavior='hide';
			}else{
				$attribute_behavior='hide';
			}

			wp_register_script( 'gs-woo-frontend', GS_WOO_PLUGIN_URI. '/assets/js/gs-woo-front.js', array( 'jquery', 'wc-add-to-cart-variation' ), GS_WOO_VERSION, true );
			 wp_register_style( 'gs-woo-frontend', GS_WOO_PLUGIN_URI. '/assets/css/gs-woo-front.css' , false, GS_WOO_VERSION );

			wp_enqueue_script( 'wc-add-to-cart-variation' );
			wp_enqueue_script( 'gs-woo-frontend' );
			wp_enqueue_style( 'gs-woo-frontend' );

			wp_localize_script( 'gs-woo-frontend', 'gs_wccl_general', array(
				'ajaxurl'       => version_compare( WC()->version, '2.4', '>=' ) ? WC_AJAX::get_endpoint( "%%endpoint%%" ) : admin_url( 'admin-ajax.php', 'relative' ),
				'cart_redirect' => get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes',
				'cart_url'      => function_exists('wc_get_cart_url') ? wc_get_cart_url() : WC()->cart->get_cart_url(),
				'view_cart'     => esc_attr__( 'View Cart', 'gs-variation' ),
				'description'   => $show_attribute_description,
				'add_cart'      => apply_filters( 'gs_woov_add_to_cart_button_content', isset( $data['cart_button'] )? $data['cart_button']:'add to cart' ),
				'grey_out'		=> $attribute_behavior,
				'image_hover'   => $image_on_hover,
				'wrapper_container_shop' => apply_filters( 'gs_wrapper_container_shop_js', 'li.product' )
			) );

			if( is_product() && ! is_null( $post ) ) {
				$this->create_attributes_json( $post->ID );
				// remove standard action
				remove_action( 'woocommerce_before_single_product', array( $this, 'create_attributes_json' ), 10 );
			}


	
		}

		/**
		 * Add select options to loop
		 *
		 * @since 1.0.0
		 * @param $html
		 * @param object $product WC_Product
		 * @return mixed
		 * @author Golam Samdani
		 */
		public function add_select_options( $html = '', $product = false ){
			$data = $this->_data->get_options( 'gsv' );
		

			if( ! $product )
				global $product;

			// let's third part skip form on loop for specific product or on specific conditions using filter gs_skip_form_variable_loop
			if( ( isset( $product ) && get_post_type( $product ) && ! $product->is_type( 'variable' ) )
			    || (isset( $data['enable_archive_page']) && $data['enable_archive_page'] !='1') 
			    || ( isset( $_REQUEST[ 'action' ] ) && $_REQUEST['action'] == 'gs-woocompare-view-table' )
				|| apply_filters( 'gs_skip_form_variable_loop', false ) ) {
				return $html;
			}

			$product_id = $product->get_id();
			// get available variations
			$transient = 'gs_get_available_variations_' . $product_id;
			if( false === ( $available_variations = get_transient( $transient ) ) ){
				$available_variations   = $product->get_available_variations();
				set_transient( $transient, $available_variations, 7 * DAY_IN_SECONDS );
			}
			// if not there are not available variations return
			if( empty( $available_variations ) ){
				return $html;
			}

			// get variation attributes
			$attributes = $product->get_variation_attributes();

			// form position
			$position = isset( $data['form_position'] ) ? $data['form_position']:'before';
			$new_html = $inputbox = '';

			if( class_exists( 'WooCommerce_Thumbnail_Input_Quantity' ) ) {
				$incremental    = new WooCommerce_Thumbnail_Input_Quantity();
				$inputbox       = $incremental->print_input_box(null);
			}

			// get default attributes
			$selected_attributes    = is_callable( array( $product, 'get_default_attributes' ) ) ? $product->get_default_attributes() : $product->get_variation_default_attributes();
			$data_product_variations =( isset($data['enable_ajax_archive_page']) && $data['enable_ajax_archive_page']== '1' ) ? 'false' : esc_attr( json_encode( $available_variations ) );
			
			$template_args = apply_filters( 'gs_variable_loop_template_attr', array(
				'product'                   => $product,
				'product_id'                => $product_id,
				'attributes'   			    => $attributes,
				'selected_attributes' 	    => $selected_attributes,
				'attributes_types'          => $this->get_variation_attributes_types( $attributes ),
				'data_product_variations'   => $data_product_variations
			) );

			ob_start();
			wc_get_template( 'gs-variable-loop.php', $template_args, '', GS_WOO_FILES_DIR . '/templates/' );
			$form = ob_get_clean();

			switch( $position ) {
				case 'before':
					$new_html = $inputbox . $form . $html;
					break;
				case 'after':
					$new_html = $inputbox . $html . $form;
					break;
			}

			if( current_action() == 'flatsome_product_box_after' ) {
				echo $new_html;
			}
			else {
				return apply_filters( 'gs_html_form_in_loop', $new_html );
			}
		}

		/**
		 * Print select option in loop
		 *
		 * @since 1.0.0
		 * @author Golam Samdani
		 */
		public function print_select_options(){
			echo $this->add_select_options();
		}

		/**
		 * Get an array of types and values for each attribute
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function get_variation_attributes_types( $attributes ) {
			global $wc_product_attributes;
			$types = array();
			$defined_attr = gs_get_custom_tax_types();

			if( ! empty( $attributes ) ) {
				foreach( $attributes as $name => $options ) {

					$current = isset( $wc_product_attributes[$name] ) ? $wc_product_attributes[$name] : false;

					if ( $current && array_key_exists( $current->attribute_type, $defined_attr ) ) {
						$types[$name] = $current->attribute_type;
					}
				}
			}

			return $types;
		}

		/**
		 * Create custom attribute json
		 *
		 * @since 1.0.0
		 * @param boolean|int $product_id
		 * @param boolean $return
		 * @return array
		 * @author Golam Samdani
		 */
		public function create_attributes_json( $product_id = false, $return = false ) {

			global $wc_product_attributes;

			/**
			 * @type object $product \WC_Product
			 */
			if( ! $product_id ) {
				global $product;
			}
			else {
				$product = wc_get_product( $product_id );
			}

			if( ! $product || ! $product->is_type( 'variable' ) ){
				return false;
			}

			// ensure that global array isset
			empty( $this->single_product_attributes ) && self::_create_custom_attributes_array( $product );

			// if empty global array exit
			if( empty( $this->single_product_attributes ) ) {
				return false;
			}

			// get attribute used for variation
			$attributes_variations = $product->get_variation_attributes();
			$custom_attributes_variations = array();

			// remove unused attribute for variation
			foreach( ( array ) $this->single_product_attributes as $key => $value ){
				if( array_key_exists( $key, $attributes_variations ) ) {
					// first set key then add to json array
					$variation_key = function_exists( 'wc_variation_attribute_name' ) ? wc_variation_attribute_name( $key ) : 'attribute_' . sanitize_title( $key );
					$custom_attributes_variations[ $variation_key ] = $value;
				}
			}

			if( ! $return && ! empty( $custom_attributes_variations ) ) {

				// ensure that that script was included
				wp_enqueue_script( 'gs-woo-frontend' );

				wp_localize_script( 'gs-woo-frontend', 'gs_woov_wccl', array(
					'attributes'    => json_encode( $custom_attributes_variations )
				));
			}
			else {
				return $custom_attributes_variations;
			}

		}

		/**
		 * Create product attributes array with custom values
		 *
		 * @since 1.1.1
		 * @author Golam Samdani
		 * @param object $product The product
		 * @param array $attributes_to_check Array with attributes to get values and return
		 */
		protected function _create_custom_attributes_array( $product, $attributes_to_check = array() ) {

			global $wc_product_attributes;

			// Product attributes - taxonomies and custom, ordered, with visibility and variation attributes set
			$attributes           = $product->get_attributes();
			// get custom tax type
			$custom_tax = gs_get_custom_tax_types();

			if( ! is_array( $attributes ) ) {
				return;
			}

			foreach( $attributes as $attribute ) {

				// check if current attribute is used for variations otherwise continue
				if( ! isset( $attribute['name'] ) || ( ! empty( $attributes_to_check ) && ! array_key_exists( $attribute['name'], $attributes_to_check ) ) ) {
					continue;
				}

				// set taxonomy name
				$taxonomy_name = wc_sanitize_taxonomy_name( $attribute['name'] );
				// init attr array
				$this->single_product_attributes[ $taxonomy_name ] = array();

				if ( isset( $attribute['is_taxonomy'] ) && $attribute['is_taxonomy'] ) {

					if ( ! taxonomy_exists( $taxonomy_name ) ) {
						continue;
					}

					// get taxonomy
					$attribute_taxonomy = $wc_product_attributes[ $taxonomy_name ];

					// set description and default value
					$this->single_product_attributes[ $taxonomy_name ][ 'descr' ] = $this->get_attribute_taxonomy_descr( $attribute_taxonomy->attribute_id );

					// if is custom add values and tooltip
					if( array_key_exists( $attribute_taxonomy->attribute_type, $custom_tax ) ) {

						// add type value
						$this->single_product_attributes[ $taxonomy_name ]['type'] = $attribute_taxonomy->attribute_type;

						// get terms and add to array
						$product_id = $product->get_id();
						$terms = wc_get_product_terms( $product_id, $taxonomy_name, array( 'fields' => 'all' ) );

						foreach ( $terms as $term ) {
							// get value of attr
							$value   = gs_get_term_meta( $term->term_id, $taxonomy_name . '_gs_woov_value');
							$tooltip = gs_get_term_meta( $term->term_id, $taxonomy_name . '_gs_woov_tooltip');

							// add terms values
							$term_values = apply_filters( 'gscreate_custom_attributes_term_attr', array( 'value' => $value, 'tooltip' => $tooltip ), $taxonomy_name, $term, $product );
							$this->single_product_attributes[ $taxonomy_name ]['terms'][ $term->slug ] = $term_values;
						}
					}

				}
			}
		}

		/**
		 * Get product attribute taxonomy description for table gs_woov_wccl_meta
		 *
		 * @since  1.0.0
		 * @param integer $id
		 * @return null|string
		 * @author Golam Samdani
		 */
		public function get_attribute_taxonomy_descr( $id ) {

			global $wpdb;

			$meta_value = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM " . $wpdb->prefix . "gs_woo_meta WHERE wc_attribute_tax_id = %d", $id ) );

			return isset( $meta_value ) ? $meta_value : '';
		}

		/**
		 * Set shop catalaog image to available variation array
		 *
		 * @since 1.0.0
		 * @return string
		 * @author Golam Samdani
		 */
		public function set_shop_catalog_image() {
			return is_product() ? 'shop_single' : 'shop_catalog';
		}

		/**
		 * Add to cart in ajax
		 *
		 * @since 1.0.0
		 * @author Golam Samdani
		 */
		public function add_to_cart_ajax_loop(){

			if( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'gs_woov_add_to_cart' || ! isset( $_REQUEST['product_id'] ) || ! isset( $_REQUEST['variation_id'] ) ) {
				die();
			}

			$product_id = intval( $_REQUEST['product_id'] );
			$variation_id = intval( $_REQUEST['variation_id'] );
			$quantity = isset( $_REQUEST['quantity'] ) ? $_REQUEST['quantity'] : 1;

			parse_str( $_REQUEST['attr'], $attributes );

			// get product status
			$product_status    = get_post_status( $product_id );

			if( empty( $attributes ) )
				die();


			if( WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $attributes ) && 'publish' === $product_status ) {

				do_action( 'woocommerce_ajax_added_to_cart', $product_id );

				if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
					wc_add_to_cart_message( $product_id );
				}

				// Fragments and mini cart are returned
				WC_AJAX::get_refreshed_fragments();
			}
			else {

				// If there was an error adding to the cart, redirect to the product page to show any errors
				$data = array(
					'error'       => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
				);
			}

			wp_send_json( $data );

			die();

		}

		/**
		 * Filter loop attributes for variation form
		 *
		 * @since 1.0.6
		 * @param array $attr
		 * @param object $product
		 * @param object $variation
		 * @return array
		 * @author Golam Samdani
		 */
		public function loop_variations_attr( $attr, $product, $variation ) {

			if( ( ! is_shop() && ! is_product_taxonomy() && ! is_product_category() ) || class_exists( 'JCKWooThumbs' ) ) {
				return $attr;
			}

			$image = $image_srcset = $image_sizes = '';

			if ( has_post_thumbnail( $variation->get_id() ) ) {
				$attachment_id = get_post_thumbnail_id( $variation->get_id() );
				$attachment    = wp_get_attachment_image_src( $attachment_id, 'shop_catalog' );
				$image         = $attachment ? current( $attachment ) : '';
				$image_srcset  = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $attachment_id, 'shop_catalog' ) : '';
				$image_sizes   = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $attachment_id, 'shop_catalog' ) : '';
			}

			$attr['image_src']    = $image;
			$attr['image_srcset'] = $image_srcset;
			$attr['image_sizes']  = $image_sizes;

			return $attr;
		}

		/**
		 * Compatibility with WooCommerce Quick View Plugin from WooThemes.
		 * This method adds attribute json for product in quick view
		 *
		 * @since 1.1.0
		 * @author Golam Samdani
		 */
		public function wc_quick_view_json_product_attr(){

			global $product;

			$product_id = $product->get_id();
			$attr = $this->create_attributes_json( $product_id, true );

			if( $attr ) {
				?>
				<div class="gs-woo-data" style="display:none;" data-attr="<?php echo htmlspecialchars( json_encode( $attr ) ) ?>"></div>
				<?php
			}

		}

		/**
		 * Add style also on tab Product Attributes
		 *
		 * @since 1.2.0
		 * @author Golam Samdani
		 * @param string $html
		 * @param array $attribute
		 * @param array $values
		 * @return string
		 */
		public function product_attributes_tab( $html, $attribute, $values ) {

			global $product;
			$data = $this->_data->get_options( 'gsv' );

			if( ! $attribute['is_taxonomy'] || empty( $data['show-custom-on-tab'] ) ) {
				return $html;
			}

			// ensure that global attribute array isset
			empty( $this->single_product_attributes ) && self::_create_custom_attributes_array( $product );

			// get values from global array
			$attribute_name = $attribute['name'];
			$custom_values = isset( $this->single_product_attributes[ $attribute_name ] ) ? $this->single_product_attributes[ $attribute_name ] : array();

			if( empty( $custom_values ) || ! isset( $custom_values['type'] ) ) {
				return $html;
			}

			$custom_html = $this->render_attributes_type( $custom_values );

			return $custom_html ? $custom_html : $html;
		}

		/**
		 * Render custom attributes type for product tab
		 *
		 * @since 1.2.0
		 * @author Golam Samdani
		 * @param array $values
		 * @return string
		 */
		public function render_attributes_type( $values ){

			$data = $this->_data->get_options( 'gsv' );


			$html = '<div class="select_box_' . $values['type'] . ' select_box on_ptab">';

			foreach( $values['terms'] as $term ) {

				$html .= '<div class="select_option_' . $values['type'] . ' select_option">';
				// get values
				$term_values = explode( ',', $term['value'] );

				switch( $values['type'] ) {
					case 'colorpicker': // type color
						if ( count( $term_values ) > 1 ) {
							$style = "border-bottom-color:{$term_values[0]};border-left-color:{$term_values[1]}";
							$html .= '<span class="gs_woov_value"><span class="gs-woov-bicolor" style="' . $style . '"></span></span>';
						} else {
							$html .= '<span class="gs_woov_value" style="background-color:' . $term_values[0] . '"></span>';
						}
						break;
					case 'label': // type label
						$html .= '<span class="gs_woov_value">'. $term_values[0] .'</span>';
						break;
					case 'image': // type image
						$html .= '<img class="gs_woov_value" src="'. $term_values[0] .'" alt="" />';
						break;
					default:
						do_action( 'gs_woo_render_attributes_type_' . $values['type'], $values );
						break;
				}

				
				$html .= '</div>';
			}

			$html .= '</div>';

			return $html;
		}

	}
}


/**
 * Unique access to instance of Gs_Woov_Frontend class
 *
 * @return \Gs_Woov_Frontend
 * @since 1.0.0
 */
function Gs_Woov_Frontend(){
	return Gs_Woov_Frontend::get_instance();
}

Gs_Woov_Frontend();