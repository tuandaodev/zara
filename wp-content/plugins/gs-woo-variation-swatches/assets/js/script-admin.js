jQuery(document).ready(function($) {

	$('#color-picker').wpColorPicker();
	$('#tool-color').wpColorPicker();
});

/**
 * Admin
 *
 * @version 1.1.0
 */
jQuery(document).ready(function($) {
    "use strict";

    var colorpicker = $( '.gs[data-type="colorpicker"]' ),
        image       = $( '.gs[data-type="image"]'),
        // apply colorpicker
        gs_woov_colorpicker = function( colorpicker ) {
            colorpicker.each( function() {

                $(this).wpColorPicker();

                if( $(this).hasClass('hidden_empty') && ! $(this).val() ) {
                    $(this).closest('.wp-picker-container').hide();
                }
            });
        },
        // apply upload image
        gs_woo_upload = function( image ) {

            image.each(function(){

                var button = $("<input type='button' name='' id='term_value_button' class='button' value='Upload' />");
                button.insertAfter(this);

                //image uploader
                button.on('click', function(e) {

                    e.preventDefault();

                    var t = $(this),
                        custom_uploader,
                        id = t.attr('id').replace('_button', '');

                    //If the uploader object has already been created, reopen the dialog
                    if (custom_uploader) {
                        custom_uploader.open();
                        return;
                    }

                    var custom_uploader_states = [
                        // Main states.
                        new wp.media.controller.Library({
                            library:   wp.media.query(),
                            multiple:  false,
                            title:     'Choose Image',
                            priority:  20,
                            filterable: 'uploaded'
                        })
                    ];
                    // Create the media frame.
                    custom_uploader = wp.media.frames.downloadable_file = wp.media({
                        // Set the title of the modal.
                        title: 'Choose Image',
                        library: {
                            type: ''
                        },
                        button: {
                            text: 'Choose Image'
                        },
                        multiple: false,
                        states: custom_uploader_states
                    });
                    //When a file is selected, grab the URL and set it as the text field's value
                    custom_uploader.on( 'select' , function() {
                        var attachment = custom_uploader.state().get( 'selection' ).first().toJSON();

                        $("#" + id).val( attachment.url );
                    });

                    //Open the uploader dialog
                    custom_uploader.open();
                });
            });
        };

    gs_woov_colorpicker( colorpicker );
    gs_woo_upload( image );


    // ADD DESCRIPTION TO ATTRIBUTE FORM

    var form_attr = $( '.product_page_product_attributes .woocommerce form' );

    if( typeof gs_woov_admin != 'undefined' && gs_woov_admin.html )
        form_attr.find('.form-field').last().after( gs_woov_admin.html );


    // FORM DIALOG

    var container           = $('.product_attributes'),
        dialog_wrap         = $( '#gs_woov_dialog_form' ),
        dialog_error        = dialog_wrap.find( '.dialog_error' ),
        // save original form
        dialog_form_o       = dialog_wrap.find( 'form').clone(),
        reset_form          = function() {
            // clone original form and change with current
            var clone = dialog_form_o.clone();
            dialog_wrap.find( 'form' ).replaceWith( clone );
        };

    // Add a new attribute (via ajax)
    container.on( 'click', 'button.gs_woov_add_new_attribute', function(e) {
        e.preventDefault();

        var wrapper     = $(this).closest('.woocommerce_attribute'),
            attribute   = wrapper.data( 'taxonomy' ),
            type        = $(this).data( 'type_input' ),
            form        = dialog_wrap.find( 'form' ),
            term_value  = form.find( '#term_value, #term_value_2' );

        // replace standard term value
        term_value.attr( 'data-type', type );

        // check type
        if( type == 'colorpicker' ) {
            gs_woov_colorpicker( term_value );
            double_color( form.find( '.gs_woov_add_color_icon' ) );
        }
        else{
            // remove not used input
            form.find( '#term_value_2, .gs_woov_add_color_icon, br').remove();
            if( type == 'image' ) {
                gs_woo_upload(term_value);
            }
        }

        // init dialog
        dialog_wrap.dialog({
            width: 350,
            modal: true,
            dialogClass: 'gs_woov_dialog_modal',
            buttons: {
                'Add': function(){
                    $(document).find( '#gs_woov_dialog_form form' ).trigger( "submit", [ wrapper, attribute ] );
                },
                Cancel: function() {
                    dialog_wrap.dialog( "close" );
                }
            },
            close: function() {
                reset_form();
            }
        });

        return false;
    });

    $(document).on("submit", '#gs_woov_dialog_form form', function (e, wrapper, attribute) {
        e.preventDefault();

        var t       = $(this),
            form = t.serializeArray(),
            data;

        // add action and taxonomy
        form.push({ name: "action", value: 'gs_woov_add_new_attribute' }, { name: "taxonomy", value: attribute } );
        data = $.param( form );

        t.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});

        $.post( gs_woov_admin.ajaxurl, data, function (response) {

            // unblock form
            t.unblock();

            if ( response.error ) {
                // Error
                dialog_error.html( response.error );
            }
            else if ( response.value ) {
                // Remove error
                dialog_error.html('');
                // Success
                wrapper.find('select.attribute_values').append('<option value="' + response.value + '" selected="selected">' + response.name + '</option>');
                wrapper.find('select.attribute_values').change();

                // close dialog
                dialog_wrap.dialog("close");
            }

        });

        return false;

    });

    var double_color = function( plus ){

        plus.off('click').on( 'click', function(){
            var t = $(this),
                tdata = t.data('content'),
                input_container = $(this).nextAll( '.wp-picker-container' ),
                input_clear = input_container.find( '.wp-picker-clear' );


            // change button content
            t.data( 'content', t.html() );
            t.html( tdata );

            input_clear.click();
            input_container.toggle();
        });
    };

    double_color( $( '.gs_woov_add_color_icon' ) );
});