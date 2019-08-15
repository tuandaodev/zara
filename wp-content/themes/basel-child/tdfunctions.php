<?php

if( ! function_exists( 'td_basel_get_grid_el_class' ) ) {
	function td_basel_get_grid_el_class($loop = 0, $columns = 4, $different_sizes = false, $xs_size = false, $sm_size = 4, $md_size = 3) {
		$classes = '';

		if( ! in_array( $columns, array(1,2,3,4,6,12) ) ) {
			$columns = 4;
		}

		if( ! $xs_size ) {
			$xs_size = apply_filters('basel_grid_xs_default', 6);
		}

		if( $columns < 3) {
			$xs_size = 12;
			if($columns == 1)
				$sm_size = 12;
			else
				$sm_size = 6;
		}		

		$md_size = 12/$columns;

		// every third element make 2 times larger (for isotope grid)
		if( $loop % 3 == 0 )  { // ( $loop + 1 ) % 4  == 0 || 
			$md_size *= 2;
			$sm_size *= 2;
			$classes .= ' product1row ';
		}

		$col = ' col-xs-' . $xs_size . ' col-sm-' . $sm_size . ' col-md-';

		$classes .= $col . $md_size;

		if($loop > 0) {

			//testing
			$classes .= ' loop' . $loop . ' ';

			if ( 0 == ( $loop - 1 ) % $columns || 1 == $columns )
				$classes .= ' first ';
			if ( 0 == $loop % $columns )
				$classes .= ' last ';
		}

		return $classes;
	}
}

if( ! function_exists( 'td_basel_woocommerce_main_loop' ) ) {

	//add_action( 'td_basel_woocommerce_main_loop', 'td_basel_woocommerce_main_loop' );

	function td_basel_woocommerce_main_loop( $fragments = false ) {
		global $paged, $wp_query;

        $max_page = $wp_query->max_num_pages;

		if ( $fragments ) ob_start();
		
		if ( $fragments && isset( $_GET['loop'] ) ) basel_set_loop_prop( 'woocommerce_loop', sanitize_text_field( (int) $_GET['loop'] ) );
		
		if ( have_posts() ) : ?>
		
			<?php if( ! $fragments ) woocommerce_product_loop_start(); ?>
			
				<?php if ( wc_get_loop_prop( 'total' ) || $fragments ): ?>
					
					<?php while ( have_posts() ) : the_post(); ?>
	
						<?php wc_get_template_part( 'tdcontent', 'product' ); ?>
	
					<?php endwhile; // end of the loop. ?>
					
				<?php endif; ?>

			<?php if( ! $fragments ) woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				if( ! $fragments ) do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php else: ?>

			<?php 
				/**
				 * Hook: woocommerce_no_products_found.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
			?>

		<?php endif;

		if ( $fragments ) $output = ob_get_clean();

	    if( $fragments ) {
	    	$output =  array(
	    		'items' => $output,
	    		'status' => ( $max_page > $paged ) ? 'have-posts' : 'no-more-posts',
	    		'nextPage' => str_replace( '&', '&', next_posts( $max_page, false ) )
	    	);

	    	echo json_encode( $output );
	    }
	}
}