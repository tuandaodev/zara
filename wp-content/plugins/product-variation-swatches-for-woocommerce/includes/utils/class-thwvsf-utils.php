<?php
/**
 * The common utility functionalities for the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    product-variation-swatches-for-woocommerce
 * @subpackage product-variation-swatches-for-woocommerce/includes/utils
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWVSF_Utils')):

class THWVSF_Utils {
	const OPTION_KEY_ADVANCED_SETTINGS = 'thwvs_swatches_advanced_settings';
	
	public static function get_advanced_swatches_settings($settings_key = false){
		$settings = get_option(self::OPTION_KEY_ADVANCED_SETTINGS,true);
		if($settings_key) {
			$settings_value = isset($settings[$settings_key])? $settings[$settings_key] : '';
			return empty($settings_value) ? false : $settings_value;
		}  
		return empty($settings) ? false : $settings;
	}

	public static function is_quick_view_plugin_active(){
		$quick_view = false;
		if(self::is_flatsome_quick_view_enabled()){
			$quick_view = 'flatsome';
		}else if(self::is_yith_quick_view_enabled()){
			$quick_view = 'yith';
		}else if(self::is_astra_quick_view_enabled()){
			$quick_view = 'astra';
		}
		return apply_filters('thwvsf_is_quick_view_plugin_active', $quick_view);
	}
	
	public static function is_yith_quick_view_enabled(){
		return is_plugin_active('yith-woocommerce-quick-view/init.php');
	}
	
	public static function is_flatsome_quick_view_enabled(){
		return (get_option('template') === 'flatsome');
	}

	public static function is_astra_quick_view_enabled(){
		return is_plugin_active('astra-addon/astra-addon.php');
	}
}

endif;