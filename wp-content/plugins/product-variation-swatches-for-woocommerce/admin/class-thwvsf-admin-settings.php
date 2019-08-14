<?php
/**
 * The admin settings page specific functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package     product-variation-swatches-for-woocommerce
 * @subpackage  product-variation-swatches-for-woocommerce/admin
 */
if(!defined('WPINC')){ die; }

if(!class_exists('THWVSF_Admin_Settings')):

abstract class THWVSF_Admin_Settings {
	protected $page_id = '';	
	public static $section_id = '';
	
	protected $tabs = '';
	protected $sections = '';
	
	public function __construct($page, $section = '') {
		$this->page_id = $page;
		
		$this->tabs = array( 'general_settings' => 'Global Settings');
	}
	
	public function get_tabs(){
		return $this->tabs;
	}

	public function get_current_tab(){
		return $this->page_id;
	}

	public function render_tabs(){
		$current_tab = $this->get_current_tab();
		$tabs = $this->get_tabs();

		if(empty($tabs)){
			return;
		}
		
		echo '<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">';
		foreach( $tabs as $id => $label ){
			$active = ( $current_tab == $id ) ? 'nav-tab-active' : '';
			$label = $label;
			echo '<a class="nav-tab '.$active.'" href="'. $this->get_admin_url($id) .'">'.$label.'</a>';
		}
		echo '</h2>';

		$this->output_premium_version_notice();
	}

	public function output_premium_version_notice(){
		?>
        <div id="message" class="wc-connect updated thwcfd-notice">
            <div class="squeezer">
            	<table>
                	<tr>
                    	<td width="70%">
                        	<p>The premium version of the <strong><i>Variation Swatches for WooCommerce</i></strong> comes with a handful of extra features for swatches display.</p>
                            <ul>
                            	<li class="dashicons-before dashicons-yes">Extra Swatch Option – Radio: <i>Allows you to display your product attributes in the form of Radio buttons.</i>.</li>
                                <li class="dashicons-before dashicons-yes">More Tooltips: <i>You can set an image, term name, or description as a tooltip for swatches</i>.</li>
                                <li class="dashicons-before dashicons-yes">More option to style the swatches.</li>
                                <li class="dashicons-before dashicons-yes">Display Out of Stock Label & Stock Left Alert.</li>
                                <li class="dashicons-before dashicons-yes">Display Swatches on Shop Page.</li>
                                <li class="dashicons-before dashicons-yes">Individual Attribute Settings.</li>
                            </ul>
                        </td>
                        <td>
                        	<a target="_blank" href="https://www.themehigh.com/product/woocommerce-product-variation-swatches" class="">
                            	<img src="<?php echo THWVSF_ASSETS_URL_ADMIN.'images/upgrade-btn.png'; ?>" />
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
	}
	
	public function render_sections() {
		$current_section = $this->get_current_section();
		$sections = $this->get_sections();

		if(empty($sections)){
			return;
		}
		
		$array_keys = array_keys( $sections );
		$section_html = '';
		
		foreach( $sections as $id => $label ){
			$label = __($label, 'product-variation-swatches-for-woocommerce');
			$url   = $this->get_admin_url($this->page_id, sanitize_title($id));	
			$section_html .= '<li><a href="'. $url .'" class="'.($current_section == $id ? 'current' : '').'">'.$label.'</a> '.(end($array_keys) == $id ? '' : '|').' </li>';
		}	
		
		if($section_html){
			echo '<ul class="thpladmin-sections">';
			echo $section_html;	
			echo '</ul>';
		}
	} 
	
	public function get_admin_url($tab = false, $section = false){
		$url = 'edit.php?post_type=product&page=th_product_variation_swatches_for_woocommerce';
		if($tab && !empty($tab)){
			$url .= '&tab='. $tab;
		}
		if($section && !empty($section)){
			$url .= '&section='. $section;
		}
		return admin_url($url);
	}
		
	public function render_form_field_element($field, $atts = array(), $render_cell = true){
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'label_cell_props' => '',
				'input_cell_props' => '',
				'label_cell_colspan' => '',
				'input_cell_colspan' => '',
			), $atts );
		
			$ftype     = isset($field['type']) ? $field['type'] : 'text';
			$flabel    = isset($field['label']) && !empty($field['label']) ? __($field['label'],'') : '';
			$sub_label = isset($field['sub_label']) && !empty($field['sub_label']) ? __($field['sub_label'],'') : '';
			$tooltip   = isset($field['hint_text']) && !empty($field['hint_text']) ? __($field['hint_text'],'') : '';
			
			$field_html = '';
			
			if($ftype == 'text'){
				$field_html = $this->render_form_field_element_inputtext($field, $atts);
				
			}else if($ftype == 'number'){
				$field_html = $this->render_form_field_element_inputnumber($field, $atts);
				
			}else if($ftype == 'textarea'){
				$field_html = $this->render_form_field_element_textarea($field, $atts);
				   
			}else if($ftype == 'select'){
				$field_html = $this->render_form_field_element_select($field, $atts);     
				
			}else if($ftype == 'multiselect'){
				$field_html = $this->render_form_field_element_multiselect($field, $atts);     
				
			}else if($ftype == 'colorpicker'){
				$field_html = $this->render_form_field_element_colorpicker($field, $atts);              
            
			}else if($ftype == 'checkbox'){
				$field_html = $this->render_form_field_element_checkbox($field, $atts, $render_cell);   
				$flabel 	= '&nbsp;';  

			}else if($ftype == 'radio'){
				$field_html = $this->render_form_field_element_radio($field, $atts);		
			}
			
			if($render_cell){
				$required_html = isset($field['required']) && $field['required'] ? '<abbr class="required" title="required">*</abbr>' : '';
				
				$label_cell_props = !empty($args['label_cell_props']) ? $args['label_cell_props'] : '';
				$input_cell_props = !empty($args['input_cell_props']) ? $args['input_cell_props'] : '';
				
				?>
				<td <?php echo $label_cell_props ?> >
					<?php echo $flabel; echo $required_html; 
					if($sub_label){
						?>
						<br/><span class="thpladmin-subtitle"><?php echo $sub_label; ?></span>
						<?php
					}
					?>
				</td>
			
				<?php $this->render_form_fragment_tooltip($tooltip); ?>
				<td <?php echo $input_cell_props ?> ><?php echo $field_html; ?></td>
				<?php
			}else{
				echo $field_html;
			}
		}
	}
	
	private function prepare_form_field_props($field, $atts = array()){
		$field_props = '';
		$args = shortcode_atts( array(
			'input_width' => '',
			'input_name_prefix' => 'i_',
			'input_name_suffix' => '',
		), $atts );
		
		$ftype = isset($field['type']) ? $field['type'] : 'text';
		
		if($ftype == 'multiselect'){
			$args['input_name_suffix'] = $args['input_name_suffix'].'[]';
		}
		
		$fname  = $args['input_name_prefix'].$field['name'].$args['input_name_suffix'];
		$fvalue = isset($field['value']) ? $field['value'] : '';
		
		$input_width  = $args['input_width'] ? 'width:'.$args['input_width'].';' : '';
		$fid=isset($field['id']) ? $field['id'] : '';
		$frequired=isset($field['required']) ? 'required' : '';

		$field_props  = 'name="'. $fname .'" value="'. $fvalue .'" style="'. $input_width .'"id="'.$fid.'"'.$frequired.' ';
		$field_props .= ( isset($field['placeholder']) && !empty($field['placeholder']) ) ? ' placeholder="'.$field['placeholder'].'"' : '';
		$field_props .= ( isset($field['onchange']) && !empty($field['onchange']) ) ? ' onchange="'.$field['onchange'].'"' : '';
		if($ftype == 'number'){
			$fmin=isset($field['min']) ? $field['min'] : '';
			$fmax=isset($field['max']) ? $field['max'] : '';

			$field_props .= 'min="'. $fmin .'"max="'.$fmax.'"';
		}
		$field_props .=  isset($field['disabled']) ? 'disabled' : '';
		return $field_props;
	}
	
	private function render_form_field_element_inputtext($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			$readonly = (isset($field['read-only']) && $field['read-only']== 'yes') ? 'readonly':'';
			
			$field_html = '<input type="text" '. $field_props.'  '.$readonly.' />';
		}
		return $field_html;
	}

	private function render_form_field_element_inputnumber($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			$field_html = '<input type="number" '. $field_props .' />';
		}
		return $field_html;
	}
	
	private function render_form_field_element_textarea($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'rows' => '5',
				'cols' => '100',
			), $atts );
		
			$fvalue = isset($field['value']) ? $field['value'] : '';
			$field_props = $this->prepare_form_field_props($field, $atts);
			$field_html = '<textarea '. $field_props .' rows="'.$args['rows'].'" cols="'.$args['cols'].'" >'.$fvalue.'</textarea>';
		}
		return $field_html;
	}
	
	private function render_form_field_element_select($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$fvalue = isset($field['value']) ? $field['value'] : '';
			$field_props = $this->prepare_form_field_props($field, $atts);
			
			$field_html = '<select '. $field_props .' >';
			foreach($field['options'] as $value => $label){
				$selected = $value === $fvalue ? 'selected' : '';
				$field_html .= '<option value="'. trim($value) .'" '.$selected.'>'. $label .'</option>';
			}
			$field_html .= '</select>';
		}
		return $field_html;
	}
	
	private function render_form_field_element_multiselect($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			
			$field_html = '<select multiple="multiple" '. $field_props .' class="thpladmin-enhanced-multi-select" >';
			foreach($field['options'] as $value => $label){
				//$selected = $value === $fvalue ? 'selected' : '';
				$label = __($label, 'product-variation-swatches-for-woocommerce');
				$field_html .= '<option value="'. trim($value) .'" >'. $label .'</option>';
			}
			$field_html .= '</select>';
		}
		return $field_html;
	}

	private function render_form_field_element_radio($field, $atts = array()){
		$field_html = '';
		$args = shortcode_atts( array(
			'label_props' => '',
			'cell_props'  => 3,
			'render_input_cell' => false,
			'render_label_cell' => false,
		), $atts );

		$atts = array(
			'input_width' => 'auto',
		);

		if($field && is_array($field)){
			
			$fvalue = isset($field['value']) ? $field['value'] : '';
			
			$field_props = $this->prepare_form_field_props($field, $atts);



			$field_html .= '<input type="hidden" name="i_' . $field['name'] . '"  value="'. trim($fvalue) .'" />';			

			foreach($field['options'] as $value => $label){


				$selected = $value === $fvalue ? 'rad-selected' : '';	
				
				$img_layout = '';
				$flabel = isset($label['name']) && !empty($label['name']) ? __($label['name'],'') : '';
				$onchange = ( isset($field['onchange']) && !empty($field['onchange']) ) ? ' onclick="'.$field['onchange'].'"' : '';
				$img_layout = isset($label['layout_image']) && !empty($label['layout_image']) ? $label['layout_image'] : '';				
				$field_html .='<label  '. $args['label_props'] .' '.$onchange.' class=" '.$value.' '.$selected.'" data-value="'. trim($value) .'"> ';

				$field_html .= '<img src= "'. THWVSF_URL . 'admin/assets/images/' . $img_layout.'"/>';
				$field_html .= $flabel.'</label>';
			}			
		}		
		return $field_html;
	}
	
	/*private function render_form_field_element_radio($field, $atts = array()){

		$field_html = '';
		$args = shortcode_atts( array(
			'label_props' => '',
			'cell_props'  => 3,
			'render_input_cell' => false,
			'render_label_cell' => false,
		), $atts );

		$atts = array(
			'input_width' => 'auto',
		);

		if($field && is_array($field)){
			
			$fvalue = isset($field['value']) ? $field['value'] : '';
			$field_html .= '<input type="hidden" name="i_' . $field['name'] . '"  value="'. trim($fvalue) .'" />';
			$field_props = $this->prepare_form_field_props($field, $atts);			

			foreach($field['options'] as $value => $label){
				$checked ='';
				$img_layout = '';

				//$flabel = isset($label) && !empty($label) ? THWMSC_i18n::t($label) : '';
				$flabel = isset($label['name']) && !empty($label['name']) ? __($label['name'],'') : '';
				$onchange = ( isset($field['onchange']) && !empty($field['onchange']) ) ? ' onchange="'.$field['onchange'].'"' : '';
				$img_layout = isset($label['layout_image']) && !empty($label['layout_image']) ? $label['layout_image'] : '';

				$checked = $value === $fvalue ? 'checked' : '';
				$selected = $value === $fvalue ? 'rad-selected' : '';				
				$field_html .='<label for="'. $value .'" '. $args['label_props'] .' class="'.$selected.'" > ';				

				$field_html .= '<input type="radio" name="i_' . $field['name'] . '" id="'. $value . '" value="'. trim($value) .'" ' . $checked . $onchange . '/>';
				//$field_html .= '<span class ="layout-icon ' . $value . '"></span>';
				$field_html .= '<img src= "'. THWVSF_URL . 'admin/assets/images/' . $img_layout.'"/>';
				$field_html .= $flabel.'</label>';
			}			
		}		
		return $field_html;
	}*/
	
	private function render_form_field_element_checkbox($field, $atts = array(), $render_cell = true){
		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'label_props' => '',
				'cell_props'  => 3,
				'render_input_cell' => false,
			), $atts );
			
			$fid 	= 'i_'. $field['name'];
			$flabel = isset($field['label']) && !empty($field['label']) ? __($field['label']) : '';
			$field_props  = $this->prepare_form_field_props($field, $atts);
			$field_props .= $field['checked'] ? ' checked' : '';
			
			$field_html  = '<input type="checkbox" id="'. $fid .'" '. $field_props .' />';

			$field_html .= '<label for="'. $fid .'" '. $args['label_props'] .' > '. $flabel .'</label>';
			if($field['name'] == 'enable_on_shop_page'){
				$tooltip = 'Feature is not available for the product having radio swatches';
				$field_html .= '<a href="javascript:void(0)" title="'. $tooltip.'" class="thpladmin_tooltip thwvsf_checkbox_tooltip"><img src="'.THWVSF_ASSETS_URL_ADMIN.'images/help.png" title=""/></a>';
			}
		}

		if(!$render_cell && $args['render_input_cell']){
			return '<td '. $args['cell_props'] .' >'. $field_html .'</td>';
		}else{
			return $field_html;
		}
	}
	
	private function render_form_field_element_colorpicker($field, $atts = array()){
		$backgrnd =  isset($field['value']) ? $field['value'] : '';
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			
			$field_html  = '<span class="thpladmin-colorpickpreview '.$field['name'].'_preview" style="width:20px; background-color:'.$backgrnd.'"></span>';
            $field_html .= '<input type="text" '. $field_props .' class="thpladmin-colorpick" />';
		}
		return $field_html;
	}
	
	public function render_form_fragment_tooltip($tooltip = false){
		if($tooltip){

			?>
			<td style="width: 26px; padding:0px;">
				<a href="javascript:void(0)" title="<?php echo $tooltip; ?>" class="thpladmin_tooltip"><img src="<?php echo THWVSF_ASSETS_URL_ADMIN; ?>images/help.png" title=""/></a>
			</td>
			<?php
		}else{
			?>
			<td style="width: 26px; padding:0px;"></td>
			<?php 
		}
	}
	
	public function render_form_fragment_h_separator($atts = array()){
		$args = shortcode_atts( array(
			'colspan' 	   => 6,
			'padding-top'  => '5px',
			'border-style' => 'dashed',
    		'border-width' => '1px',
			'border-color' => '#e6e6e6',
			'content'	   => '',
		), $atts );
		
		$style  = $args['padding-top'] ? 'padding-top:'.$args['padding-top'].';' : '';
		$style .= $args['border-style'] ? ' border-bottom:'.$args['border-width'].' '.$args['border-style'].' '.$args['border-color'].';' : '';
		
		?>
        <tr><td colspan="<?php echo $args['colspan']; ?>" style="<?php echo $style; ?>"><?php echo $args['content']; ?></td></tr>
        <?php
	}
	
	/*private function output_h_separator($show_line = true){
		$style = $show_line ? 'margin: 5px 0; border-bottom: 1px dashed #ccc' : '';
		echo '<tr><td colspan="6" style="'.$style.'">&nbsp;</td></tr>';
	}*/
	
	public function render_field_form_fragment_h_spacing($padding = 5){
		$style = $padding ? 'padding-top:'.$padding.'px;' : '';
		?>
        <tr><td colspan="6" style="<?php echo $style ?>"></td></tr>
        <?php
	}
	
	public function render_form_field_blank($colspan = 3){
		?>
        <td colspan="<?php echo $colspan; ?>">&nbsp;</td>  
        <?php
	}
	
	public function render_form_section_separator($props, $atts=array()){
		?>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" style="height:10px;"></td></tr>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" class="thpladmin-form-section-title" ><?php echo $props['title']; ?></td></tr>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" style="height:0px;"></td></tr>
		<?php
	}
}

endif;