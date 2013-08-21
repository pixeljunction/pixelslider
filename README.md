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