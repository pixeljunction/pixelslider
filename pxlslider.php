<?php
/*
Plugin Name: Pixel Slider
Plugin URI: http://pixeljunction.co.uk
Description: A slider plugin for WordPress that uses the Nivo Slider JQuery code from Dev 7 Studios.
Version: 0.1
Author: Mark Wilkinson
Author URI: http://markwilkinson.me
License: GPLv2 or later
*/

/* enqueue jquery */
wp_enqueue_script( 'jquery' );

/* load plugin post_type functions */
require_once dirname( __FILE__ ) . '/functions/post-types.php';

/* load plugin post_type functions */
require_once dirname( __FILE__ ) . '/functions/admin.php';
	
/***************************************************************
* Function pxlsld_register_scripts()
* Register the scripts needed for the slider.
***************************************************************/
function pxlsld_register_scripts() {
	
	/* register the js files for the nivo slider */
	wp_register_script( 'pxjn_nivo_js', plugins_url( 'js/jquery.nivo.slider.pack.js', __FILE__ ), 'jquery' );
	wp_register_script( 'pxjn_nivo_hook_js', plugins_url( 'js/pxlslider-hook.js', __FILE__ ), 'pxjn_nivo_js' );
	wp_register_style( 'pxjn_nivo_css', plugins_url( 'css/pxlslider.css', __FILE__ ) );
	
}

add_action( 'init', 'pxlsld_register_scripts' );

/***************************************************************
* Function pxlsld_print_scripts()
* Prints to scripts to the page in the footer when the slider
* is included on the page.
***************************************************************/
function pxlsld_print_scripts() {
	
	/* initiate the global variable for adding scripts */
	global $pxlsld_add_scripts;
	
	/* check whether scripts should be printed, is the shortcode on this page */
	if ( ! $pxlsld_add_scripts )
		return;
		
	/* print the scripts to the page */
	wp_print_scripts( 'pxjn_nivo_js' );
	wp_print_scripts( 'pxjn_nivo_hook_js' );
	wp_print_styles( 'pxjn_nivo_css' );
	
}

add_action( 'wp_footer', 'pxlsld_print_scripts' );

/***************************************************************
* Function pxlsld_slider_shortcode()
* Create the shortcode for outputting the slider. Added to a
* page or post like this [pxlslider height="300px"]
***************************************************************/
function pxlsld_slider_shortcode( $atts ) {
	
	/* extract the shortcode args, making each available as $variable */
	extract( shortcode_atts(
		array(
			'height' => '300px',
		),
	$atts )
	);
	
	/* initiate the global variable for adding scripts */
	global $pxlsld_add_scripts;
	
	/* set the variable to true to output the scripts to the page */
	$pxlsld_add_scripts = true;
	
	/* set some arguments for our slides query */
	$pxlsld_slides_args = array(
		'post_type' => 'pxlsld_slide',
		'showposts' => -1,
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_query' => array(
			'relation' => 'AND',
			array( // only return posts that have a post thumbnail
				'key' => '_thumbnail_id',
				'compare' => 'EXISTS'
			)
		)
	);
	
	/* build the new query */
	$pxlsld_slides = new WP_Query( $pxlsld_slides_args );
	
	/* check we have slides returned */
	if( $pxlsld_slides->have_posts() ) {
		
		/* add in a hook firing before the slider */
		do_action( 'pxlsld_before_slider' );
		
		/* echo some styles for height and width */
		?>
		
			<style type="text/css">.nivoSlider {height: <?php echo esc_attr( $height ); ?> !important;}</style>
		
		<?php
		
		/* output the wrapper div - important */
		echo '<div id="slider">';
		
		/* loop through each slide */
		while( $pxlsld_slides->have_posts() ) : $pxlsld_slides->the_post();
			
			/* set the html div which stores the caption for this slide */
			$pxlsld_slide_caption = '#slide-caption-'.get_the_ID();
			
			/* output the slide image for this slide */
			the_post_thumbnail( 'slide-image', array( 'title' => $pxlsld_slide_caption ) );
		
		/* end loop through slides */
		endwhile;
		
		echo '</div>';
		
		/* add in a hook firing after the slider */
		do_action( 'pxlsld_after_slider' );
		
	} // end if have slides
	
	/* wp_reset_query */
	wp_reset_query();
	
	/* begin second loop through to get the captions */
	/* check we have slides returned */
	if( $pxlsld_slides->have_posts() ) {

		/* begin the second loop for the captions */
		while( $pxlsld_slides->have_posts() ) : $pxlsld_slides->the_post();
			
			/* get the excerpt */
			$pxlsld_caption = get_the_excerpt();
			
			?>
			
			<div id="slide-caption-<?php the_ID(); ?>" class="nivo-html-caption">
				
				<?php
				
					/* add in a hook firing before the slider caption */
					do_action( 'pxlsld_before_slider_caption' );
				
				?>
				
				<div class="slide-caption-title"><?php the_title(); ?></div>
				
				<div class="slide-caption-content"><?php echo $pxlsld_caption; ?></div>
				
				<div class="slide-caption-link">
					<a href="<?php echo get_post_meta( get_the_ID(), '_pxlsld_slide_link', true ); ?>"><?php echo apply_filters( 'pxlsld_read_more_text', 'Find out more' ); ?></a>
				</div>
				
				<?php
				
					/* add in a hook firing after the slider caption */
					do_action( 'pxlsld_after_slider_caption' );
				
				?>
				
			</div><!-- // slide-caption -->
			
			<?php
		
		/* end loop through slides */
		endwhile;
	
	} // end if have slides
	
	/* wp_reset_query */
	wp_reset_query();
	
}

add_shortcode( 'pxlslider', 'pxlsld_slider_shortcode' );