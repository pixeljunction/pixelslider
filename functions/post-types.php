<?php
/*********************************************************************
* Function pxlsld_post_types()
* Creates the custom post types to use in the site.
*********************************************************************/
function pxlsld_post_types() {

	/* create labels for slides post type */
	$pxlsld_slide_labels = apply_filters( 'pxlsld_post_type_labels', array(
		'name' => _x( 'Slides', 'post type general name' ),
		'singular_name' => _x( 'Slide', 'post type singular name' ),
		'add_new' => _x( 'Add New', 'Slide' ),
	    'add_new_item' => __( 'Add New Slide' ),
	    'edit_item' => __( 'Edit Slide' ),
	    'new_item' => __( 'New Slide' ),
	    'view_item' => __( 'View Slide' ),
	    'search_items' => __( 'Search Slides' ),
	    'not_found' =>  __( 'No Slides found' ),
	    'not_found_in_trash' => __( 'No Slides found in Trash' ), 
	    'parent_item_colon' => '',
	    'menu_name' => 'Slides'
	) );
	
	/* register the post type */
	register_post_type( 'pxlsld_slide', array(
			'labels' => $pxlsld_slide_labels,
			'public' => true,
			'menu_position' => 25,
			'supports' => array( 'title', 'thumbnail' ),
			'query_var' => true,
			'rewrite' => array( 'slug' => 'pxl-slides', 'with_front' => false ),
			'has_archive' => true,
		)
	);
	
} // end custom post type function

/* add our custom taxonomies function wordpress init hook */
add_action( 'init', 'pxlsld_post_types' );