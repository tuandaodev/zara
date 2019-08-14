<?php
/**
 * Auto-loads the required dependencies for this plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    product-variation-swatches-for-woocommerce
 * @subpackage product-variation-swatches-for-woocommerce/includes
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWVSF_Autoloader')):

class THWVSF_Autoloader {
	private $include_path = '';
	
	private $class_path = array();
	
	public function __construct() {
		$this->include_path = untrailingslashit(THWVSF_PATH);
		
		if(function_exists("__autoload")){
			spl_autoload_register("__autoload");
		}
		spl_autoload_register(array($this, 'autoload'));
	}

	/** Include a class file. */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			require_once( $path );
			return true;
		}
		return false;
	}
	
	/** Class name to file name. */
	private function get_file_name_from_class( $class ) {
		return 'class-' . str_replace( '_', '-', $class ) . '.php';
	}
	
	public function autoload( $class ) {
		$class = strtolower( $class );
		$file  = $this->get_file_name_from_class( $class );
		$path  = '';
		$file_path  = '';

		if (isset($this->class_path[$class])){
			$file_path = $this->include_path . '/' . $this->class_path[$class];
		} else {
			if (strpos($class, 'thwvsf_admin') === 0){
				$path = $this->include_path . '/admin/';
			} elseif (strpos($class, 'thwvsf_public') === 0){
				$path = $this->include_path . '/public/';
			} elseif (strpos($class, 'thwvsf_utils') === 0){
				$path = $this->include_path . '/includes/utils/';
			} else{
				$path = $this->include_path . '/includes/';
			}
			$file_path = $path . $file;
			
		}
		
		if( empty($file_path) || (!$this->load_file($file_path) && strpos($class, 'thwvsf_') === 0) ) {
			$this->load_file( $this->include_path . $file );
		}
	}
}

endif;

new THWVSF_Autoloader();
