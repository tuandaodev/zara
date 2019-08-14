<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package     product-variation-swatches-for-woocommerce
 * @subpackage  product-variation-swatches-for-woocommerce/public
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWVSF_Public')):
 
class THWVSF_Public {
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		add_action('after_setup_theme', array($this, 'define_public_hooks'));
	}

	public function enqueue_styles_and_scripts() {
		global $wp_scripts;
		
		$is_quick_view = THWVSF_Utils::is_quick_view_plugin_active();

		if(is_product() ||( $is_quick_view && (is_shop() || is_archive())) || apply_filters('thwvsf_enqueue_public_scripts', false)){
			//$debug_mode = apply_filters('thwvsf_debug_mode', false);
			$suffix = '';
			
			$jquery_version = isset($wp_scripts->registered['jquery-ui-core']->ver) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';
			
			$this->enqueue_styles($suffix, $jquery_version,$is_quick_view);
			$this->enqueue_scripts($suffix, $jquery_version,$is_quick_view);
		}
	}
	
	private function enqueue_styles($suffix, $jquery_version, $is_quick_view) {
		wp_enqueue_style('thwvsf-public-style', THWVSF_ASSETS_URL_PUBLIC . 'css/thwvsf-public.css', $this->version);

		$advanced_settings = THWVSF_Utils::get_advanced_swatches_settings();
		
		if($advanced_settings && is_array($advanced_settings)){
			$icon_width = isset($advanced_settings['icon_width']) ? $advanced_settings['icon_width'] : '45px';
			$icon_width = is_numeric($icon_width)? $icon_width.'px' : $icon_width;
			
			$icon_height = isset($advanced_settings['icon_height']) ? $advanced_settings['icon_height'] : '45px';
			$icon_height = is_numeric($icon_height)? $icon_height.'px' : $icon_height;
			
			$label_icon_height = isset($advanced_settings['icon_label_height']) ? $advanced_settings['icon_label_height'] : '45px';
			$label_icon_height = is_numeric($label_icon_height )? $label_icon_height .'px' : $label_icon_height ;

			$label_icon_width = isset($advanced_settings['icon_label_width']) ? $advanced_settings['icon_label_width'] : '45px';
			$label_icon_width = is_numeric($label_icon_width )? $label_icon_width.'px' : $label_icon_width ;

			$icon_shape = isset($advanced_settings['icon_shape']) ? $advanced_settings['icon_shape'] : 'square';
			$icon_roundness = $icon_shape == 'square' ? '2px' : '50px';

			$tt_text_background_color = isset($advanced_settings['tooltip_text_background_color']) ? $advanced_settings['tooltip_text_background_color']: '#000000';
			$tt_text_color = isset($advanced_settings['tooltip_text_color']) ? $advanced_settings['tooltip_text_color']: '#ffffff';

			$attr_behavior = THWVSF_Utils::get_advanced_swatches_settings('behavior_for_unavailable_variation');
			$description_shadow = THWVSF_Utils::get_advanced_swatches_settings('description_shadow_enable');
			$description_shadow = $description_shadow != 'yes' ? 'no' : '';
			
			$custom_css = "
	       		.thwvsf-wrapper-ul .thwvsf-wrapper-item-li {
	               	width: {$icon_width}; 
	               	border-radius: {$icon_roundness}; 
	               	height:  {$icon_height}; 
	           	}
	           	.thwvsf-wrapper-ul .thwvsf-wrapper-item-li.thwvsf-label-li{
	               	width: {$label_icon_width}; 
	               	height:  {$label_icon_height}; 
	           	}	
				.thwvsf-wrapper-ul .thwvsf-tooltip .tooltiptext {
   					background-color: {$tt_text_background_color};
   					color : {$tt_text_color};
				}
				.thwvsf-wrapper-ul .thwvsf-tooltip .tooltiptext::after{
   					border-color: {$tt_text_background_color} transparent transparent;
				}";

	        if(isset($attr_behavior)){
	        	if($attr_behavior == 'blur'){
	        		$custom_css .= ".thwvsf-wrapper-ul .thwvsf-wrapper-item-li.deactive {
	        			opacity : 0.3;	
	        		}

	        		.thwvsf-wrapper-ul .thwvsf-wrapper-item-li.deactive::after, .thwvsf-wrapper-ul .thwvsf-wrapper-item-li.deactive::before {
	        				height: 0px;
	        		} ";
	        	}else if($attr_behavior == 'hide'){
	        		$custom_css .= ".thwvsf-wrapper-ul .thwvsf-wrapper-item-li.deactive {
	        			display: none;	
	        		}";
	        	}
	        }

	        if(isset($custom_css)){
	        	wp_add_inline_style('thwvsf-public-style', $custom_css );
	        }	
		}
	}

	private function enqueue_scripts($suffix, $jquery_version, $is_quick_view) {
		$deps = array();
		$deps = array('jquery', 'wc-add-to-cart-variation');	
		wp_register_script('thwvsf-public-script', THWVSF_ASSETS_URL_PUBLIC . 'js/thwvsf-public.js', $deps, $this->version, true );
								
		wp_enqueue_script('thwvsf-public-script');
		//$settings = THWVSF_Utils::get_advanced_swatches_settings();

		$wvs_var = array(
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'is_quick_view' => $is_quick_view,	
		);

		wp_localize_script('thwvsf-public-script', 'thwvsf_public_var', $wvs_var);
	}
	
	public function define_public_hooks(){
		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $this, 'swatches_display' ), 100, 2 );
		add_filter( 'woocommerce_reset_variations_link',array($this, 'reset_variation_link') );
	}

	public function reset_variation_link($link){
		$custom_reset = apply_filters('thwvsf_reset_variations_link',false);
		if($custom_reset){
			$link = '<a class="reset_variations thwvsf-variation-link" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>';
		}
		return $link;
	}

	public function get_attribute_fields($attribute,$product){
		if(taxonomy_exists($attribute)){
			$attribute_taxonomies = wc_get_attribute_taxonomies();
	        foreach ($attribute_taxonomies as $tax) {
	            if('pa_'.$tax->attribute_name == $attribute){
	                return($tax->attribute_type);
	                break;
	            }
	        }
	    }
	}

	public function get_attribute_id($taxonomy){
		$attribute_taxonomies = wc_get_attribute_taxonomies();
        foreach ($attribute_taxonomies as $tax) {
            if('pa_'.$tax->attribute_name == $taxonomy){
                return($tax->attribute_id);
                break;
            }
        }
	}
	
	public function swatches_display($html, $args){
		global $product;
		$attribute = $args['attribute'];
		$type = $this->get_attribute_fields($attribute,$product);
		
		if($type == 'select' || $type == null){
			return $html;
		}

		$swatch_types = array('color','image','label');
	
		if(in_array($type,$swatch_types)){
			$html = '';
			$attr_type_html = '';
			switch ($type) {
				
	    		case 'color':
	               $attr_type_html .= $this->add_color_display($html,$args,$type);
	            break;
	            case 'image':
	                $attr_type_html .= $this->add_image_display($html,$args,$type);
	            break;
	            case 'label' : 
	            	$attr_type_html .= $this->add_label_display($html,$args,$type);
	            break;
	             
			}
		}else{
			return $html;
		}

		$html .= '<div class="thwvsf_fields"> '. $attr_type_html.' </div>';
		return $html;
	}

	public function add_color_display($html, $args,$attr){
		$html = $this->default_variation_field($html,$args,$attr);
		$options  = $args['options'];
		$product  = $args['product'];
		$attribute = $args['attribute'];
		$name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
		$id = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$product_id = $product->get_id();
		
		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( ! empty( $options ) ) {
			if ( $product  && taxonomy_exists( $attribute )) {

				$terms = wc_get_product_terms( $product->get_id(), $attribute, array('fields' => 'all',) );
				$terms = taxonomy_exists( $attribute ) ? $terms  : $options;

				$html .= '<ul class="thwvsf-wrapper-ul">';
                
				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						$selected = sanitize_title( $args['selected'] ) == $term->slug ? 'thwvsf-selected' : '';
						$name     = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
						$label = get_term_meta( $term->term_id, 'label', true );
						$label = $label ? $label : $name;
						$color = get_term_meta( $term->term_id,'product_'.$attribute, true );
						$slug = $term->slug;

        				$tt_html = '';
        				$tooltip_type = THWVSF_Utils::get_advanced_swatches_settings('tooltip_enable');
        				
                		if($tooltip_type == 'yes'){
                			$tt_html = '<span class="tooltiptext tooltip_'.$id.'">'.$name .'</span>' ;
                		}
                		
						$html  .=  sprintf(
							'<li class="thwvsf-wrapper-item-li thwvsf-color-li thwvsf-div thwvsf-checkbox attribute_'.$id.' '. $slug.' '.$selected.' thwvsf-tooltip" data-attribute_name="attribute_'.$id.'" data-value="'.$slug.'" title="'.$name.'">
									'.$tt_html.'
									
								<span class="thwvsf-item-span thwvsf-item-span-color" style="background-color:'.esc_attr( $color).';"> 	
								</span>
								
								
							</li>');
					}
				}

				$html  .= '</ul>';
			}else{

			}
		}

		return $html;
	}

	public function add_image_display($html,$args,$attr){
		$html = $this->default_variation_field($html,$args,$attr);
		$options  = $args['options'];
		$product  = $args['product'];
		$attribute = $args['attribute'];
		$name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
		$id = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$product_id = $product->get_id();
		
		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute )) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array('fields' => 'all',) );
				$html .= '<ul class="thwvsf-wrapper-ul">';
 		
				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						$term_status = true;
						$selected = sanitize_title( $args['selected'] ) == $term->slug ? 'thwvsf-selected' : '';
						$name     = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
						$label = get_term_meta( $term->term_id, 'label', true );
						$label = $label ? $label : $name;

						$value = get_term_meta( $term->term_id,'product_'.$attribute, true );
						$image = $value ? wp_get_attachment_image_src( $value ) : '';
            			$image = $image ? $image[0] : THWVSF_URL . 'admin/assets/images/placeholder.png';
						$slug = $term->slug;
	        			$tt_html = '';
	    				$tooltip_type = THWVSF_Utils::get_advanced_swatches_settings('tooltip_enable');
        				
                		if($tooltip_type == 'yes'){
                			$tt_html = '<span class="tooltiptext tooltip_'.$id.'">'.$name .'</span>' ;
                		}

						$html .= '<li class="thwvsf-wrapper-item-li thwvsf-image-li thwvsf-div thwvsf-checkbox attribute_'.$id.' '. $slug.' '.$selected.' thwvsf-tooltip" data-attribute_name="attribute_'.$id.'" data-value="'.$slug.'" title="'.$name.'">'.$tt_html.'<img class="swatch-preview swatch-image "  src="'.$image.' " width="44px" height="44px"></img><span class="size-buttons-inventory-left stock_attribute_'.$id.' '. $slug.'"></span></li>';	
					}	
				}

				$html .= '</ul>';
			}
		}

		return $html;
	}

	public function add_label_display($html,$args,$attr){
		$html = $this->default_variation_field($html,$args,$attr);
		$options  = $args['options'];
		$product  = $args['product'];
		$attribute = $args['attribute'];
		$name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
		$id = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$product_id = $product->get_id();
		
		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute )) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array('fields' => 'all',) );
				$terms = taxonomy_exists( $attribute ) ? $terms  : $options;

				$html  .= '<ul class="thwvsf-wrapper-ul">';
				$settings_key = $this->get_attribute_id($attribute);
                		
				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						$selected = sanitize_title( $args['selected'] ) == $term->slug ? 'thwvsf-selected' : '';
						$name     = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
						$label = get_term_meta( $term->term_id, 'label', true );
						$label = $label ? $label : $name;

						$value = get_term_meta( $term->term_id,'product_'.$attribute, true );
						
						$slug = $term->slug;
        			
	        			$tt_html = '';
	        			$tooltip_type = THWVSF_Utils::get_advanced_swatches_settings('tooltip_enable');
        				
                		if($tooltip_type == 'yes'){
                			$tt_html = '<span class="tooltiptext tooltip_'.$id.'">'.$name .'</span>' ;
                		}

						$html  .=  sprintf(
							'<li class="thwvsf-wrapper-item-li thwvsf-label-li thwvsf-div thwvsf-checkbox attribute_'.$id.' '. $slug.' '.$selected.' thwvsf-tooltip" data-attribute_name="attribute_'.$id.'" data-value="'.$slug.'" title="'.$name.'">
								'.$tt_html.'
								<span class=" thwvsf-item-span item-span-text ">'.$value.'</span>

								<span class="size-buttons-inventory-left stock_attribute_'.$id.' '. $slug.'"></span>
								
							</li>');	
					}
				}

				$html  .= '</ul>';		
			}
		}

		return $html;
	}

	public function default_variation_field($html,$args,$attr){
		$args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
			'options'          => false,
			'attribute'        => false,
			'product'          => false,
			'selected'         => false,
			'name'             => '',
			'id'               => '',
			'class'            => '',
			'show_option_none' => __( 'Choose an option', 'woocommerce' ),
		) );

		// Get selected value.
		if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
			$selected_key     = 'attribute_' . sanitize_title( $args['attribute'] );
			$args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
		}

		$options               = $args['options'];
		$product               = $args['product'];
		$attribute             = $args['attribute'];
		$name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
		$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class                 = $args['class'];
		$show_option_none      = (bool) $args['show_option_none'];
		$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		$html  = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" style="display:none" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		$html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array(
					'fields' => 'all',
				) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						$html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
					}
				}
			} else {
				foreach ( $options as $option ) {
					// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
					$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
					$html    .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
				}
			}
		}

		$html .= '</select>';
		return $html;
	}
}

endif;

