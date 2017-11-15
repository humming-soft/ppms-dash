<!--<link media="screen, print" href=--><?php //echo $this->config->base_url(); ?><!--assets/css/stylesheets.css rel=stylesheet type=text/css />-->
<!--<link media="screen, print" rel="stylesheet" href="--><?php //echo $this->config->base_url(); ?><!--assets/css/slippry.css">-->
<!--<link media="screen, print" rel="stylesheet" href="--><?php //echo $this->config->base_url(); ?><!--assets/mmc/package/jquery-ui-1.11.4.custom/jquery-ui.theme-flick.css" />-->
<link media="screen, print" rel="stylesheet" type="text/css" href="<?php echo $this->config->base_url(); ?>assets/mmc/package/jquery-ui-1.11.4.custom/jquery-ui.theme.dotluv.css">
<!--jsImageslider-->
<link media="screen, print" rel="stylesheet" href="<?php echo $this->config->base_url(); ?>assets/js/plugins/jsImageSlider/js-image-slider.css" />
<link media="screen, print" rel="stylesheet" href="<?php echo $this->config->base_url(); ?>assets/css/jquery.gridster.css">
<!--<link media="screen, print" rel="stylesheet" type="text/css" href="--><?php //echo $this->config->base_url(); ?><!--assets/mmc/package/jquery-gridster/jquery.gridster.css">-->
<!--<link media="screen, print" rel="stylesheet" href="--><?php //echo $this->config->base_url(); ?><!--assets/css/custom.css">-->
<link media="screen, print" rel="stylesheet" href="<?php echo $this->config->base_url(); ?>assets/css/font-awesome-animation.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->base_url(); ?>assets/mmc/css/_sn.css">
<script>
    var permission = <?php echo json_encode($permission); ?>;
    var pages = <?php echo json_encode($menu); ?>;
</script>
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/plugins/jquery/jquery-migrate.min.js></script>
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/plugins/jquery/globalize.js></script>
<!--<script type=text/javascript src=--><?php //echo $this->config->base_url(); ?><!--assets/js/js.js></script>-->
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/settings.js></script>
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/plugins/knob/jquery.knob.js></script>

<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/jquery.gridster.js></script>

<!--<script type=text/javascript src=--><?php //echo $this->config->base_url(); ?><!--assets/mmc/package/jquery-gridster/jquery.gridster.min.js></script>-->
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/history.min.js></script>
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/underscore-min.js></script>
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/backbone-min.js></script>

<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/menuzord.js></script>
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/gridster-bootstrap.js></script>
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/plugin/wb-popover/jquery.webui-popover.min.js></script>
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/mpxd.js></script>

<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/datasourcei.js></script>
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/datasourcez.js></script>
<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/datasources.js></script>

<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/tinybox.js></script>

<script src="<?php echo $this->config->base_url(); ?>assets/js/leaflet-src.js"></script>
<script src="<?php echo $this->config->base_url(); ?>assets/js/leaflet.awesome-markers.js"></script>
<script src="<?php echo $this->config->base_url(); ?>assets/js/leaflet-knn.js"></script>
<script src="<?php echo $this->config->base_url(); ?>assets/js/jquery.panzoom.min.js"></script>

<script src="<?php echo $this->config->base_url(); ?>assets/js/js.cookie-2.1.0.min.js"></script>
<!-- Gallery src-->
<link media="screen, print" href="<?php echo $this->config->base_url(); ?>assets/plugin/nano-gallery2/css/nanogallery.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/plugin/nano-gallery2/jquery.nanogallery.min.js"></script>

<!--Picasa slider-->
<link media="screen, print" href="<?php echo $this->config->base_url(); ?>assets/plugin/picasa-slider/jquery.googleslides.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/plugin/picasa-slider/jquery.googleslides.js"></script>

<!--jsImage slider-->
<script src="<?php echo $this->config->base_url(); ?>assets/js/plugins/jsImageSlider/js-image-slider.js"></script>

<script type="text/javascript">
    baseURL = <?php echo json_encode($this->config->base_url()); ?>;
    function prettyDate(d) {
        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var date = (typeof d == "undefined") ? new Date() : new Date(d);
        str = "{0} {1} {2}";
        return str.format(date.getDate(), monthNames[date.getMonth()], date.getFullYear());
    }
    $(function() {
        screenWidth = $(document).width();
        screenType = 0;
        if (screenWidth <= 1500)
            screenType = 1;
        if (screenWidth <= 1280)
            screenType = 2;
        $.mCustomScrollbar.defaults.scrollButtons.enable = true;
        loadPage(getRoute(), true);
    });
</script>
