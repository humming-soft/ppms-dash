<style>
    #breadcrumbs {
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        margin: 0;
        overflow: hidden;
        padding: 15px 0 5px 20px;
        width: 100%;
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0.02);
    }

    #breadcrumbs li {
        list-style-type: none;
        float: left;
        padding-left: 10px;
    }

    #breadcrumbs>li+li:before {
        padding-right: 10px;
        color: #ccc;
        font-family: FontAwesome;
        content: "\f105";
    }
    /*
    #dashboardtop_container:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            border-width: 0 42px 42px 0;
            border-style: solid;
            border-color: #ffffff rgba(0, 0, 0, 0.1);
            -webkit-box-shadow: 0 0 0 rgba(0, 0, 0, 0.4), 0 0 10px rgba(0, 0, 0, 0.3);
            -moz-box-shadow: 0 0 0 rgba(0, 0, 0, 0.4), 0 0 10px rgba(0, 0, 0, 0.3);
            box-shadow: 0 0 0 rgba(0, 0, 0, 0.4), 0 0 10px rgba(0, 0, 0, 0.3);
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
            -o-transform: rotate(-90deg);
            filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=3);
    }*/
    #dashboardtop_container > .container > .row > div {
        background: rgba(0,0,0,0.2);
    }
    #dashboardtop_container > .container > .row > div+div {
        border-left: 1px dashed #ccc;
       /* height: 100%; */
    }
    #stats {/*
            background: none 0px 0px repeat scroll rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-image-source: initial;
    border-image-slice: initial;
    border-image-width: initial;
    border-image-outset: initial;
    border-image-repeat: initial;
    border-radius: 30px;
    box-shadow: rgba(255, 255, 255, 0.0980392) 0px 1px 0px;
    color: rgb(255, 255, 255);
    margin: 10px;
    height: 40px;
    width: 40%;
    line-height: 41px;
    text-align: center;*/
        display: inline;
        margin-right: 30px;
        color: #ffd461;
    }
    #dashboardtop_container {
        /*height: 60px; */line-height:60px; 
    }

	div.dropdown_design0 {
		border-top: 1px solid transparent;
	}
	div.dropdown_design0.open {
		background: #082B3A;
		color: #fff;
		border-top-color: #fff;
	}
	div.dropdown_design1 {
		min-width: 125px;
		background: #082B3A;
		border-bottom: 10px solid rgba(255, 255, 255, 0.16);
		right: 0;
		margin-top: 0;
		border-top-right-radius: 0;
		border-top-left-radius: 0;
		border-bottom-right-radius: 0;
		border-bottom-left-radius: 0;
		line-height: 100%;
		color: #fff;
	}
	div.dropdown_design1 p {
		text-align: center;
	}
	div.dropdown_design1 div.user-info p {
		font-weight: bold;
		font-size: 1.3em;
	}
	div.dropdown_design1 div.getreport > ul {
		padding: 0 !important;
		margin: 0 !important;
	}
	div.dropdown_design1 div.getreport > ul > li {
		display: inline-block;
		text-align: center;
		padding: 0 5px;
	}
	div.dropdown_design1 > div {
		padding: 10px 0;
		border-bottom: 1px solid rgba(255, 255, 255, 0.16);
	}
	div.dropdown_design1 > div:last-child {
		border-bottom: none;
	}

</style>

<div id="container">
    <div id="dashboardtop_container" class="removeonprint">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 text-center"><i class="fa fa-area-chart fa-lg" style="color : #15A6E9; padding-right: 5px"></i><span style="color: #15A6E9; font-size: 12px; font-weight: bold; font-family: ‘Impact, Charcoal, sans-serif;">DASHBOARD</span></div>
                <div class="col-lg-8 col-md-8 col-sm-8" style="padding-left:30px;">
					<div class="pull-left" id="stats"><i class="fa fa-calendar"></i> <span id="date_placeholder"></span></div>
					<span class="pull-left" style="">Data Date: <!--b id="data_date2">27 October 2014</b--></span>
					<div style="top: 15px;" class="input-group col-lg-4 col-md-5">
						<input style="cursor: pointer;" readonly="true" id="data_date" class="form-control" type="text" value="" placeholder="Data date" name="data-date">
						<input type="hidden" id="data_date_selected" value=""/>
						<span id="date_selector" style="cursor: pointer;" class="input-group-addon">
							<i id="date_open" class="fa fa-calendar"></i>
						</span>
						<div style="display: none;" class="input-group-btn">
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Date <span class="caret"></span></button>
						<ul id="date_list" class="dropdown-menu">
							<!--<li><a href="?current">Current</a></li>
							<li><a href="#" onClick="loadPage('v9/index?date=11-Oct-14')">11-Oct-14</a></li>
							<li><a href="#" onClick="loadPage('v9/index?date=06-Nov-14')">06-Nov-14</a></li>-->
						</ul>
						</div><!-- /btn-group -->
					</div>
				</div>
                <div class="col-lg-2 col-md-2 col-sm-2" style="background: rgba(0,0,0,0.5);text-align: center;">
				
				
				<div class="dropdown_design0 dropdown " style="padding-left:10px;">
            <a href="#" title="" class="user-ico clearfix" data-toggle="dropdown" aria-expanded="false">
                <img class="" src="<?php echo $this->config->base_url(); ?>assets/img/user.jpg" alt="User profile picture" style="width:24px">
                <i class="fa fa-chevron-down" style="color:#fff; padding-left:5px;"></i>
            </a>
            <div class="dropdown_design1 dropdown-menu float-right">
            	<div class="user-info">
            		<p><?php echo ($this->session->userdata['fullname']); ?></p>
            	</div>
            	<div class="getreport">
            		<p>Get this report of current page:</p>
					<ul>
						<li>
							<a href="#" id="toExcelButton" onclick="return false;"><img src="<?php echo $this->config->base_url(); ?>assets/img/excel.png"></a>
						</li>
						<li>
							<a href="#" id="toPDFButton" onclick="return false;"><img src="<?php echo $this->config->base_url(); ?>assets/img/pdf.png"></a>
						</li>
					</ul>
            	</div>
            	<div class="logout">
            		<button class="btn btn-danger" style="" onclick="location.href='/ppms/logout'">Logout</button>
            	</div>
            </div>

            <!-- <div class="dropdown_design1 dropdown-menu float-right">
				<div class="user-info">Hummingsoft Developer</div>
				<span>Get this report of project:</span>
				<br><span>Get this report of project:</span>
				<ul>
					<li>
						<a href="http://localhost/simplejob"><img src="img/excel.png"></a>
					</li>
					<li>
						<a href="http://localhost/simplejob"><img src="img/pdf.png"></a>
					</li>
				</ul>
				<hr class="hr-dotted">
                <button class="btn btn-danger" style="" onclick="location.href='/mpxd/logout'">Logout</button>
            </div> -->
        </div>
				
				</div>
            </div>
        </div>
    </div>
    <div id="breadcrumbs_container">
        <ol id="breadcrumbs">
            <li>
                <a href="../../ppms/dashboard/">
                    <i class="fa fa-home fa-md" style="padding-top:2px;"></i>
                </a>
            </li>

        </ol>
    </div>

    <div id="portlet_container">

    </div>
</div>
<!--
<div class="col-xs-12 col-sm-12 col-md-6" style="text-align: center;" style="height: 300px;">
    <div class="block block-drop-shadow" id="portlet_1">

        <div class="header bg-dot30">
            <img src="./img/icons/line_chart-26.png" style="float: left;margin-right: 13px;"> 
            <h2>TITLE</h2>
        </div>
        <div class="content" style=" background: #1d2a34; ">
            <div class="row">
                dsadsad
                sada
                dsa
                d
                sad
                sa
                
                <div id="chartS">
                </div>
            </div>
        </div>
    </div>
</div>-->