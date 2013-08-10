jQuery(window).load(function() {
    jQuery('#slider').nivoSlider({
        effect:'fade', //Specify sets like: 'fold,fade,sliceDown'
        slices:15,
        animSpeed:500, //Slide transition speed
        captionOpacity: 1,
        directionNavHide: false,
		pauseTime:8000,
    });
});