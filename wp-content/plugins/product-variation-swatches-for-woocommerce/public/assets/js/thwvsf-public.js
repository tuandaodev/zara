var thwvsf_public_base = (function($, window, document) {
	'use strict';
	
	
	function isEmpty(val){
		return (val === undefined || val == null || val.length <= 0) ? true : false;
	}
			
	
	
	/********************************************
	***** CHARACTER COUNT FUNCTIONS - START *****
	********************************************/
	function display_char_count(elm, isCount){
		var fid = elm.prop('id');
        var len = elm.val().length;
		var displayElm = $('#'+fid+"-char-count");
		
		if(isCount){
			displayElm.text('('+len+' characters)');
		}else{
			var maxLen = elm.prop('maxlength');
			var left = maxLen-len;
			displayElm.text('('+left+' characters left)');
			if(rem < 0){
				displayElm.css('color', 'red');
			}
		}
	}
    /******************************************
	***** CHARACTER COUNT FUNCTIONS - END *****
	******************************************/
	
	function set_field_value_by_elm(elm, type, value){
		switch(type){
			case 'radio':
				elm.val([value]);
				break;
			case 'checkbox':
				if(elm.data('multiple') == 1){
					value = value ? value : [];
					elm.val([value]);
				}else{
					console.log(value);
					elm.val([value]);
				}
				break;
			case 'select':
				if(elm.prop('multiple')){
					elm.val(value);
				}else{
					elm.val([value]);
				}
				break;
			case 'country':
				elm.val([value]).change();
				break;
			case 'state':
				elm.val([value]).change();
				break;
			case 'multiselect':
			
				if(elm.prop('multiple')){
					if(typeof(value) != "undefined"){
						elm.val(value.split(',')).change();
					}
				}else{
					elm.val([value]);
				}
				break;
			default:
				elm.val(value);
				break;
		}
	}
	
	function get_field_value(type, elm, name){
		var value = '';
		switch(type){
			case 'radio':
				value = $("input[type=radio][name="+name+"]:checked").val();
				break;
			case 'checkbox':
				if(elm.data('multiple') == 1){
					var valueArr = [];
					$("input[type=checkbox][name='"+name+"[]']:checked").each(function(){
					   valueArr.push($(this).val());
					});
					value = valueArr;//.toString();
				}else{
					value = $("input[type=checkbox][name="+name+"]:checked").val();
				}
				break;
			case 'select':
				value = elm.val();
				break;
			case 'multiselect':
				value = elm.val();
				break;
			default:
				value = elm.val();
				break;
		}
		return value;
	}
	
	return {
		
		display_char_count : display_char_count,
		set_field_value_by_elm : set_field_value_by_elm,
		get_field_value : get_field_value,
	};
}(window.jQuery, window, document));

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
