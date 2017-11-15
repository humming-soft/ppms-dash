<!-- to use scroller : class="col-md-12 scroll_set_1" -->
<?php include 'templates/portlet_header.php' ?>
<title>PPMS</title>
<div id="wrapper" class="">
<!--    <div id="loading_pad" class="loading_pad loading_pad_gohide">-->
<!--    	<span>-->
<!--    		<p>Loading</p>-->
<!--    		<img src="../assets/img/loading.gif">-->
<!--    	</span>-->
<!--    </div>-->
    <div id="loading_pad" class="loading_pad_gohide"><span><b>HUMWORKS DASHBOARD</b><p class="ltext">Loading...</p></span></div>
    <div id="cover" class="overlay_gohide">
    <div id="wrapper_landing_page">
        <!-- header -->
        <div id="header">
            <?php include 'module/header_menu.php' ?>
        </div>
        <!-- -- -->
        <!-- content -->
        <div id="content">
            <div id="dashboard" class="col-md-12">
                <div class="col-md-12">
                    <div class="">
                            <div id="breadcrumbs_container">
                                <ol id="breadcrumbs">
                                    <li>
                                        <a class="custom_breadcrumb-item" style="outline: 0; padding: 0 5px; font-size: .9em;" href="<?php echo $this->config->base_url(); ?>dashboard">
                                            <i class="fa fa-home fa-md" aria-hidden="true" style="padding-top:2px;"></i>
                                        </a>
                                    </li>

                                </ol>
                            </div>
                    </div>
                    <div class="title breadcrumbs_title"></div>
                </div>

                <div class="col-md-12 plate_portlet plr0">
<!--                    <div class="row">-->
                        <div id="portlet_container" style="width: 100%"></div>
<!--                    </div>-->
                </div>
            </div>

        </div>
        </div>
        <!-- -- -->
    </div>
<?php include 'templates/default_footer.php' ?>