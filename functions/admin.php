<?php
/* add image sizes for the admin and front end slides */
add_image_size( 'slide-admin', 400, 125, true );
add_image_size( 'slide-image', 960, 310, true );

/*********************************************************************
* Function pxlsld_excerpt_meta_box()
* Creates the output for a new metabox on the slides post type edit
*********************************************************************/
function pxlsld_excerpt_meta_box( $post ) {

	?>
	
	<label class="screen-reader-text" for="excerpt"><?php _e( 'Excerpt' ) ?></label>
	<textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
	<p><?php _e( 'Add a caption for your slide here.' ); ?></p>
	
	<?php
	
}

/*********************************************************************
* Function pxlsld_slide_metabox_changes()
* 
*********************************************************************/
function pxlsld_slide_metabox_changes() {
	
	/* remove the metabox for the featured image on the slides post type */
	remove_meta_box( 'postimagediv', 'pxlsld_slide', 'side' );
	
	/* add the metabox for the featured image using different title */
	add_meta_box( 'postimagediv', __( 'Slide Image' ), 'post_thumbnail_meta_box', 'pxlsld_slide', 'advanced', 'low' );
	
	/* remove the excerpt metabox on the slides post type */
	remove_meta_box( 'postexcerpt', 'pxlsld_slide', 'side' );
	
	/* add the post exceprt metabox back with a new title */
	add_meta_box( 'postexcerpt', __( 'Slide Caption' ), 'pxlsld_excerpt_meta_box', 'pxlsld_slide', 'advanced', 'low' );
	
}

add_action( 'do_meta_boxes', 'pxlsld_slide_metabox_changes' );

/*********************************************************************
* Function pxlsld_add_metabox()
* Adds a metabox for the slide post type
*********************************************************************/
function pxlsld_add_metabox() {
	
	/* add metabox */
	add_meta_box(
		'pxlsld_slide_info',
		'Slide Information',
		'pxlsld_metabox_html',
		'pxlsld_slide',
		'normal',
		'default',
		''
	);
	
}

add_action( 'add_meta_boxes', 'pxlsld_add_metabox' );

/*********************************************************************
* Function pxlsld_metabox_html()
* Generates the HTML output for the metabox.
*********************************************************************/
function pxlsld_metabox_html( $post ) {
	
	/* use nonce for verification */
	wp_nonce_field( plugin_basename( __FILE__ ), 'pxlsld_nonce_name' );
	
	/* create the input */
	?>
	
		<table class="form-table pxlsld-metabox">
		
			<tbody>
			
				<tr>
				
					<th style="width: 18%;">
					
						<label for="pxlsld_slide_link"><strong>Slide Link URL</strong></label>
					
					</th>
					
					<td style="width: 78%;">
					
						<input style="width: 95%;" type="text" value="<?php echo esc_attr( get_post_meta( $post->ID, '_pxlsld_slide_link', true ) ); ?>" name="pxlsld_slide_link" />
						
						<p class="pxlsld-description">Enter a URL above for the slide link.</p>
					
					</td>
				
				</tr>
			
			</tbody>
		
		</table>
	
	<?php
	
}

/*********************************************************************
* Function pxlsld_save_metabox_data()
* Saves the submitted metabox information.
*********************************************************************/
function pxlsld_save_metabox_data( $post_id ) {
	
	/* check this is the correct post type */ 
	if ( 'pxlsld_slide' == $_REQUEST['post_type'] ) {
		
		/* check if the current user is authorised to do this action */
		if( ! current_user_can( 'edit_page', $post_id ) )
		    return;
		
		// Secondly we need to check if the user intended to change this value.
		if ( ! isset( $_POST[ 'pxlsld_nonce_name' ] ) || ! wp_verify_nonce( $_POST[ 'pxlsld_nonce_name' ], plugin_basename( __FILE__ ) ) )
			return;
			
		/* santize the user input */
		$pxlsld_data = sanitize_text_field( $_POST[ 'pxlsld_slide_link'] );
		
		/* get the post id */
		$pxlsld_post_id = $_POST[ 'post_ID' ];
		
		/* save the post data */
		update_post_meta( $post_id, '_pxlsld_slide_link', $pxlsld_data );
	
	}
	
}

add_action( 'save_post', 'pxlsld_save_metabox_data' );

/*********************************************************************
* Function pxlsld_edit_slides_columns()
* Sets up new columns on the slides post type
*********************************************************************/
function pxlsld_edit_slides_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'slide-image' => __( 'Slide Image' ),
		'caption' => __( 'Caption Text'),
		'date' => __( 'Date' )
	);
	return $columns;
}
add_filter( 'manage_edit-pxlsld_slide_columns', 'pxlsld_edit_slides_columns' ) ;

/*********************************************************************
* Function pxlsld_manage_slides_columns()
* Creates the ouput for the new slides columns
*********************************************************************/
function pxlsld_manage_slides_columns( $column, $post_id ) {
	
	/* gain access to the global post variable */
	global $post;
	
	/* setup swtich statement to change output depending on column */
	switch( $column ) {
		
		/* if displaying the 'image' column. */
		case 'slide-image' :
			
			/* get the thumbnail. */
			$image = get_the_post_thumbnail( $post->ID, 'slide-admin' ); // uses slide admin image size from add_image_size
			
			/* if no thumbnail is found */
			if( empty( $image ) ) {
				
				/* output a default message */
				echo '<a href="'.admin_url().'post.php/?post='.$post->ID.'&action=edit">No Image Added Yet</a>';
			
			/* if there is a thumbnail */
			} else {
				
				/* output the thumbnail, linking to the post edit screen */
				echo '<a href="'.admin_url().'post.php/?post='.$post->ID.'&action=edit">'.$image.'</a>';
				
			} // end if no image
			
			/* break out of the switch statement */
			break;
			
		/* if displaying the 'caption' column. */
		case 'caption' :
		
			/* get the caption excerpt. */
			$caption = get_the_excerpt();
			
			/* if no caption is found, output a default message. */
			if ( empty( $caption ) ) {
				
				/* echo an error message */
				echo __( 'No caption added yet!' );
				
			/* if there is a caption */
			} else {
				
				/* output the excerpt */
				the_excerpt();
				
			} // end if no caption
			
			/* break out of the switch statement */
			break;
			
		/* Just break out of the switch statement for everything else. */
		default :
			break;
			
	} // end switch statement
	
}

add_action( 'manage_pxlsld_slide_posts_custom_column', 'pxlsld_manage_slides_columns', 10, 2 );