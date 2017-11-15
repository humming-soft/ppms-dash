<style>
	div.ui-datepicker{
		font-size:14px;
	}
</style>
<div class="col-md-12">
	<div class="col-xs-3 col-sm-3 col-md-3">
		<div class="row">
			<div class="logo_holder col-md-12">
				<div class=""><img style="height: 30px;" src="<?php echo $this->config->base_url(); ?>assets/mmc/images/logo/padu_logo.png"></div>
			</div>
		</div>
	</div>
	<div class="col-xs-9 col-sm-9 col-md-9">
		<div class="row">
			<div class="col-xs-5 col-sm-5 col-md-5">
				<div id="header_datetime">
					<span class="hd_label hd_label_today"><i class="fa fa-calendar-o" aria-hidden="true"></i> Today: <span id="current_date">22 JUN 2016</span></span>
					<span class="hd_label hd_label_data"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Data Date :</span>
						<input readonly="true" id="data_date" type="text" value="" placeholder="Data date" name="data-date">
						<input type="hidden" id="data_date_selected" value=""/>
						<div style="display: none;" class="input-group-btn">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Date <span class="caret"></span></button>
							<ul id="date_list" class="dropdown-menu">
							</ul>
						</div>
					<span id="date_selector" class="hd_label hd_button">Change</span>
				</div>
			</div>
			<div id="header_menu" class="col-xs-7 col-sm-7 col-md-7">
				<a class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<i class="fa fa-user" aria-hidden="true"></i>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
<!--					<li><a href="temp_nav.php">Temp. Sitemap (will be remove)</a></li>-->
<!--					<li role="separator" class="divider"></li>-->
					<li><a href="<?php echo $this->config->base_url(); ?>logout">Logout</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>