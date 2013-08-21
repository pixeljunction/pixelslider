#pixelslider

Pixel Slider is a WordPress plugin that adds a simple jQuery slider to your WordPress site. Uses the Nivo Slider jQuery plugin. The plugin adds a new post type to WordPress for the slides. The plugin allows one slider to be created, each post in the new post type is a slide in the slider.

## Installation

To install the plugin simply download the ZIP and then add the extracted files to your wp-content/plugins folder. Or upload the ZIP in the WordPress UI for adding a plugin.

## Usage

Once installed you can then add some slides to your slider by clicking Slides > Add New from the WordPress dashboard menu. Each slide need a title and caption (both of which are displayed in the slider) and a link if you would like to include one to link to the slide to a location.

## Hooks

The plugin contains a number of hooks that allow developers to inject theiir own code at certain points in the plugin code. These are outlined below

### pxlsld_before_slider

This hooks runs before any of the slider is loaded and outside the main slider div. An example of its use is below, where you could include some HTML before the slider loads.

```php
function my_pre_slider_content() {

	echo '<p>Below is a selection of featured content.</p>';

}

add_action( 'pxlsld_before_slider', 'my_pre_slider_content' );
```

### pxlsld_after_slider

As above but fires directly after the slider and again outside the main slider div

### pxlsld_before_slider_caption

This hooks fires before the caption for the slider is outputted. An example of its use us below, perhaps for wrapping an additional div around the caption (which would need to utlise the following hook for after the slider).

```php
function my_pre_slider_caption_content() {

	echo '<div class="caption-wrapper">';

}

add_action( 'pxlsld_before_slider_caption', 'my_pre_slider_caption_content' );
```

### pxlsld_after_slider_caption

This hooks fires after the caption for the slider is outputted. An example (linked to the above example) is shown below:

```php
function my_post_slider_caption_content() {

	echo '</div>';

}

add_action( 'pxlsld_after_slider_caption', 'my_post_slider_caption_content' );
```

## Filters

Filters mean you can change the ways the plugin outputs HTML etc. Below is a list of the filters available for developers to utilise:

### pxlsld_read_more_text

This allows you to change the text that is displayed below the slider content that contains the links for the slide. By default the text is find out more but can easily be changed. For example:

```php
function my_readmore_text( $content ) {

	$content = 'Read more about this feature...';
	
	return $content;

}

add_filter( 'pxlsld_read_more_text', 'my_readmore_text' );
```

### pxlsld_post_type_labels

This filter allows you to completely change the post type labels for the slides post type. Therefore if you would rather they were labelled differently in the WordPress admin you can do that. An example of how to do this is provided below:

```php
function my_pxlslider_labels( $labels ) {

	$labels = array(
		'name' => __( 'My Slider' ),
	    'singular_name' => __( 'My Slider' ),
	    'add_new' => __( 'Add New', 'My Slider' ),
	    'add_new_item' => __( 'Add New My Slider' ),
	    'edit_item' => __( 'Edit My Slider' ),
	    'new_item' => __( 'New My Slider' ),
	    'view_item' => __( 'View My Slider' ),
	    'search_items' => __( 'Search My Sliders' ),
	    'not_found' => __( 'No SMy liders found' ),
	    'not_found_in_trash' => __( 'No My Sliders found in trash' ),
	    'parent_item_colon' => '',
	    'menu_name' => __( 'My Sliders', 'soliloquy' )
	);
	
	return $labels;

}

add_filter( 'pxlsld_post_type_labels', 'my_pxlslider_labels' );
```