<?php
/**
 * The admin advanced settings page functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package     product-variation-swatches-for-woocommerce
 * @subpackage  product-variation-swatches-for-woocommerce/admin
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWVSF_Admin_Settings_General')):

class THWVSF_Admin_Settings_General extends THWVSF_Admin_Settings{
	protected static $_instance = null;
	
	private $settings_fields = NULL;
	private $cell_props_L = array();
	private $cell_props_R = array();
	private $cell_props_CB = array();
	private $cell_props_TA = array();
	
	public function __construct() {
		parent::__construct('general_settings');
		$this->init_constants();
	}
	
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	} 
	
	public function init_constants(){
		$this->cell_props_L = array( 
			'label_cell_props' => 'class="titledesc" scope="row" style="width: 20%;"', 
			'input_cell_props' => 'class="forminp"', 
			'input_width' => '250px', 
			'label_cell_th' => true 
		);
		$this->cell_props_CP = array(
			'label_cell_props' => 'class="titledesc" scope="row" style="width: 20%;"', 
			'input_cell_props' => 'class="forminp"', 
			'input_width' => '225px',
			'label_cell_th' => true 
		);
		$this->cell_props_R = array( 'label_cell_width' => '13%', 'input_cell_width' => '34%', 'input_width' => '250px' );
		$this->cell_props_CB = array( 'cell_props' => 'colspan="3"', 'render_input_cell' => true );
		$this->cell_props_TA = array( 
			'label_cell_props' => 'class="titledesc" scope="row" style="width: 20%; vertical-align:top"', 
			'rows' => 10, 
		);
		
		$this->settings_fields = $this->get_advanced_settings_fields();
	}
	
	public function get_advanced_settings_fields(){
		$behaviors = array(
			'hide' => __('Hide', ''),
			'blur_with_cross' => __('Blur With Cross',''),
		);

		$icon_shapes = array(
			'round' => 'Round',
			'square' => 'Square',
		);

		return array(
			'attribute_other_settings' => array('title'=>'Attributes Settings', 'type'=>'separator', 'colspan'=>'3'),

			'icon_height' => array('type'=>'text', 'name'=>'icon_height', 'label'=>'Icon Height','value' => '45px'),
			'icon_width' => array('type'=>'text', 'name'=>'icon_width','label'=>'Icon Width','value'=>'45px'),
			'icon_label_height' => array('type'=>'text', 'name'=>'icon_label_height', 'label'=>' Label/Button Icon Height','value' => '45px'),
			'icon_label_width' => array('type'=>'text', 'name'=>'icon_label_width','label'=>'Label/Button Icon Width','value'=>'45px'),
			
			'icon_shape' => array('type'=>'select', 'name'=>'icon_shape','options' =>$icon_shapes,'label'=>'Icon shape','value'=>'square'),

			'tool_tip_settings' => array('title'=>'Tooltip Settings', 'type'=>'separator', 'colspan'=>'3'),

			'tooltip_enable' =>array('name'=>'tooltip_enable', 'label'=>'Enable tooltip (Attribute term name will be displayed as Tooltip)', 'type'=>'checkbox','hint_text'=>'', 'value'=>'yes', 'checked'=>0),
			'tooltip_text_background_color' => array('name'=>'tooltip_text_background_color', 'label'=>'Term name background color','type'=>'colorpicker','value' => '#000000'),
			'tooltip_text_color' => array('name'=>'tooltip_text_color', 'label'=>'Term name text color','type'=>'colorpicker','value' => '#ffffff'),
			
			'other_settings' => array('title'=>'Other Settings', 'type'=>'separator', 'colspan'=>'3'),
			'behavior_for_unavailable_variation' => array('name' => 'behavior_for_unavailable_variation','type' => 'select','options' =>$behaviors,'label' => 'Behavior for unavailable variation','value' => 'blur_with_cross' ),
		);
	}
	
	public function render_page(){
		$this->render_tabs();
		$this->render_content();
	}
		
	public function save_advanced_settings($settings){
		$result = update_option(THWVSF_Utils::OPTION_KEY_ADVANCED_SETTINGS, $settings);
		return $result;
	}
	
	private function reset_settings(){
		delete_option(THWVSF_Utils::OPTION_KEY_ADVANCED_SETTINGS);
		echo '<div class="updated"><p>'. __('Settings successfully reset','') .'</p></div>';	
	}
	
	private function save_settings(){
		$settings = $this->prepare_field_from_posted_data($_POST, $this->settings_fields);
		$result = $this->save_advanced_settings($settings);

		if ($result == true) {
			echo '<div class="updated"><p>'. __('Your changes were saved.','') .'</p></div>';
		} else {
			echo '<div class="error"><p>'. __('Your changes were not saved due to an error (or you made none!).','') .'</p></div>';
		}
	}
	
	private function render_content(){
		if(isset($_POST['reset_settings']))
			$this->reset_settings();	
			
		if(isset($_POST['save_settings']))
			$this->save_settings();
			
		$settings = THWVSF_Utils::get_advanced_swatches_settings();
		?>            
        <div style="padding-left: 30px;">               
		    <form id="advanced_settings_form" method="post" action="">
                <table class="form-table thpladmin-form-table">
                    <tbody>
                    <?php 
					foreach( $this->settings_fields as $name => $field ) { 
						if($field['type'] === 'separator'){
							$this->render_form_section_separator($field);
						}else {
					?>
                        <tr valign="top">
                            <?php 
								if($field['type'] === 'dynamic_options'){
									$this->render_validator_settings($settings, $field);
									
								}else{
									if(is_array($settings) && isset($settings[$name])){
										if($field['type'] === 'checkbox'){
											if($field['value'] === $settings[$name]){
												$field['checked'] = 1;
											}else{
												$field['checked'] = 0;
											}
										}else{
											$field['value'] = $settings[$name];
										}
									}
									
									if($field['type'] === 'checkbox'){
										$this->render_form_field_element($field, $this->cell_props_CB,false);

									}else if($field['type'] === 'multiselect' || $field['type'] === 'textarea'){
										$this->render_form_field_element($field, $this->cell_props_L);

									}else if($field['type'] === 'colorpicker'){
										$this->render_form_field_element($field,$this->cell_props_CP);

									}else{
										$this->render_form_field_element($field, $this->cell_props_L);
									} 
								}
							?>
                        </tr>
                    <?php 
						}
					} 
					?>
                    </tbody>
                </table> 
                <p class="submit">
					<input type="submit" name="save_settings" class="button-primary" value="Save changes">
                    <input type="submit" name="reset_settings" class="button" value="Reset to default" 
					onclick="return confirm('Are you sure you want to reset to default settings? all your changes will be deleted.');">
            	</p>
            </form>
    	</div>       
    	<?php
	}

	public  function prepare_field_from_posted_data($posted, $props){
		$field = array();	
		
		foreach( $props as $pname => $property ){
			$iname  = 'i_'.$pname;
			$pvalue = '';
			if($property['type'] === 'checkbox'){
				$pvalue = isset($posted[$iname]) ? $posted[$iname] : 0;

			}else if(isset($posted[$iname])){
				$pvalue = is_array($posted[$iname]) ? implode(',', $posted[$iname]) : sanitize_text_field($posted[$iname]);
			}
			
			$field[$pname] =  $pvalue;
		}
		
		return $field;
	}
}

endif;