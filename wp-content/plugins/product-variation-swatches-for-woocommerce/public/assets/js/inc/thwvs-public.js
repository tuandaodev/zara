(function( $ ) {
	'use strict';

	
	function initialize_thwvs(){

		var enable_stock_alert = thwvsf_public_var.enable_stock_alert;
		var min_value_stock = thwvsf_public_var.min_value_stock;
		
		
		var swatches_form = function( $form ) {
			var self = this;
			self.$form                = $form;
			this.variationData        = $form.data( 'product_variations' );
			this.$attributeFields     = $form.find( '.variations select' );
			self.$singleVariation     = $form.find( '.single_variation' );
			self.$singleVariationWrap = $form.find( '.single_variation_wrap' );
			//$form.on( 'change.thwvsf_variation_form', '.variations select', {swatches_form: this },this.onChangeselect_field );
			$form.on( 'click.thwvsf_variation_form', '.thwvsf-checkbox', { swatches_form : this }, this.onselect );
			$form.on( 'check_variations.thwvsf_variation_form', { swatches_form : this }, this.onFindVariation );
			$form.on( 'click.thwvsf_variation_form', '.reset_variations', { swatches_form: this }, this.onReset );
		};

		

		swatches_form.prototype.onReset = function( event ) {
			var form = event.data.swatches_form;
			$('.thwvsf_fields .thwvsf-checkbox').removeClass( 'thwvsf-selected' );
			$('.thwvsf_fields > span').removeClass( 'selected' );
			$('.thwvsf_fields .thwvsf-checkbox').removeClass( 'deactive');
			$('.thwvsf-rad').attr('checked',false);
			$('.thwvsf-rad-li > label').removeClass( 'thwvsf-selected' );
			var $element = $( this );
			
			var $button = $element.parents('.variations_form').siblings('.thwvsf_add_to_cart_button');	
			active_and_deactive_variation(form);
					
		};

		swatches_form.prototype.onselect = function( event ) {
			
			var form = event.data.swatches_form;
			var $element = $( this ),
				$select = $element.closest( '.value' ).find( 'select' ),
				attribute_name = $select.data( 'attribute_name' ) || $select.attr( 'name' ),
				value = $element.data( 'value' ),
				clicked = attribute_name;
			selected.push(attribute_name);

			

			if ( ! $select.find( 'option[value="' + value + '"]' ).length ) {
							
				$element.siblings( '.thwvsf-checkbox' ).removeClass( 'thwvsf-selected' );
				$select.val( '' ).change();
				alert('No combination');
				return false;
			}

			if ( $element.hasClass('thwvsf-selected') ) {
				$select.val( '' );
				$element.removeClass('thwvsf-selected');
				
			} else {
				
				$element.addClass('thwvsf-selected').siblings('.thwvsf-selected').removeClass('thwvsf-selected');

				$select.val( value );

			}

			$select.change();



			if(  $("BODY.post-type-archive").length > 0){
				shop_page_add_to_cart_funtion(form);
			}
			active_and_deactive_variation(form);
			
				
		}


		swatches_form.prototype.onselectradio = function( event ) {

			var form = event.data.swatches_form;
			var $element = $( this ),
				$select = $element.closest( '.value' ).find( 'select' ),
				attribute_name = $select.data( 'attribute_name' ) || $select.attr( 'name' ),
				value = $element.data( 'value' );
			clicked = attribute_name;
			selected.push(attribute_name);	
			
			
			$select.val( value );
			$select.change();
			
			
		}

		


		function active_and_deactive_variation(form){


			var $attributeFields = form.$attributeFields;
			var $addtocart_button = form.$form.find('.woocommerce-variation-add-to-cart');

			//var choosed_attr = $select.data( 'attribute_name' ) || $select.attr( 'name' );
						
			$attributeFields.each( function( index, el ) {

				var current_attr_select     = $( el ),
					current_attr_name       = current_attr_select.data( 'attribute_name' ) || current_attr_select.attr( 'name' );
				
				var $current_attr = form.$form.find('.'+ current_attr_name);
				//console.log($current_attr);
				$current_attr.addClass('deactive');
				var options = current_attr_select.children( 'option');

				options.each( function(i,option){
			 		var opt_val = option.value;
			 		if(opt_val != ''){
			 			var $current_opt = form.$form.find('.'+ current_attr_name +'.'+ opt_val);
			 			$current_opt.removeClass('deactive');
			 		}
			 	});
				
			});	
		}


		

		

		
		$.fn.wc_set_variation_attr = function( attr, value ) {
			if ( undefined === this.attr( 'data-o_' + attr ) ) {
				this.attr( 'data-o_' + attr, ( ! this.attr( attr ) ) ? '' : this.attr( attr ) );
			}
			if ( false === value ) {
				this.removeAttr( attr );
			} else {
				this.attr( attr, value );
			}
		};

	

		swatches_form.prototype.onFindVariation = function( event ) {
			
			var form = event.data.swatches_form;
			
			var $attributeFields = form.$attributeFields;

			
			active_and_deactive_variation(form);
			
	
		}


		$.fn.thwvsf_variation_form = function() {
			
			new swatches_form( this );
			
			return this;
		};

		//1
		// $('.variations_form' ).thwvsf_variation_form();

	$(function() {
		if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
			$( '.variations_form' ).each( function() {
				
				$( this ).thwvsf_variation_form();
			});
		}
	});
			
		

		var clicked = null,
			selected = [];


	}


 	initialize_thwvs(), "flatsome" == thwvsf_public_var.is_quick_view ? $(document).on("mfpOpen", function() {
        initialize_thwvs()
    }) : "yith" == thwvsf_public_var.is_quick_view && $(document).on("qv_loader_stop", function() {
        initialize_thwvs()
    })


})( jQuery );
