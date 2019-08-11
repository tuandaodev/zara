<?php
	$block = $block_data[0];
	$settings = $block_data[1];
	$link_setting = empty($settings[0]) ? '' : $settings[0];
?>

<?php if($block === 'title'): ?>
	<h4 class="post-title">
	    <?php echo empty($link_setting) || $link_setting!='no_link' ? $this->getLinked($post, $post->title, $link_setting, 'link_title') : $post->title ?>
	</h4>
<?php elseif($block === 'image' && !empty($post->thumbnail)): ?>
	<div class="post-thumb">
	    <?php echo empty($link_setting) || $link_setting!='no_link' ? $this->getLinked($post, $post->thumbnail, $link_setting, 'link_image') : $post->thumbnail ?>
	</div>
<?php elseif($block === 'text'): ?>
	<div class="entry-content">
	    <?php echo empty($link_setting) || $link_setting==='text' ?  $post->content : $post->excerpt; ?>
	</div>
<?php elseif($block === 'link'): ?>
	<a href="<?php echo esc_url($post->link); ?>" class="vc_read_more"
   	title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'basel' ), $post->title_attribute ) ); ?>"<?php echo ! empty( $this->link_target ) ? $this->link_target : ''; ?>><?php _e( 'Read more', 'basel' ) ?></a>
<?php endif; ?>
