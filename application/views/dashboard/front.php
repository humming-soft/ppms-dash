<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content ="width=device-width,initial-scale=0.8,user-scalable=yes"/>
		<meta charset="utf-8">
		<meta name="mobile-web-app-capable" content="yes">
		<title>MRT Line</title>
		
		<link href="<?php echo $this->config->base_url(); ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->config->base_url(); ?>assets/css/_sn.css" rel="stylesheet" type="text/css" />
        <style>
            .glow-red-blinking.on {
                -webkit-animation-name: glow-red;
                -webkit-animation-duration: 1s;
                -webkit-animation-iteration-count: infinite;
                -webkit-animation-timing-function: ease-in-out;
                -webkit-animation-direction: alternate;
                -webkit-filter: drop-shadow(12px 12px 7px rgba(0,0,0,0.5))

            animation-name: glow-red;
                animation-duration: 1s;
                animation-iteration-count: infinite;
                animation-timing-function: ease-in-out;
                animation-direction: alternate;
                filter: url(#drop-shadow);
            }

            @-webkit-keyframes glow-red {
                0% { fill: hsl(0, 100%, 10%); }
                100% { fill: hsl(0, 100%, 63%); }
            }

            @keyframes glow-red {
                0% { fill: hsl(0, 100%, 10%); }
                100% { fill: hsl(0, 100%, 63%); }
            }

        </style>
		<script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/js/d3.v3.min.js"></script>
		<script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/js/plugins/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/plugin/zoomooz/jquery.zoomooz.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/js/pages/dashboard.js"></script>

		<script type="text/javascript">

			$( document ).ready(function() {
				$( window ).load(function() {
					$(function() {
						if($(window).width() > 1900){
							$("body").css("visibility", "hidden");
							$("#container").zoomTo({
								targetsize:1.2,duration:150,animationendcallback : function(){
									$("#container").animate({
										top: +60
									},100)
									$("body").css("visibility", "visible");
								}
							});
						} else if($(window).width() > 1700){
							$("body").css("visibility", "hidden");
							$("#container").zoomTo({
								targetsize:1.15,duration:150,animationendcallback : function(){
									$("#container").animate({
										top: +50
									},100)
									$("body").css("visibility", "visible");
								}
							});
						} else if($(window).width() > 1500){
							$("body").css("visibility", "hidden");
							$("#container").zoomTo({
								targetsize:1.1,duration:150,animationendcallback : function(){
									$("#container").animate({
										top: +30
									},100)
									$("body").css("visibility", "visible");
								}
							});
						} else if($(window).width() > 1300){
							$("body").css("visibility", "hidden");
							$("#container").zoomTo({
								targetsize:1,duration:150,animationendcallback : function(){
									$("#container").animate({
									},100)
									$("body").css("visibility", "visible");
								}
							});
						}
						var allowedPage = allowedPageToString();
                        console.log(allowedPage);
						$('a').on('click', function(e){
							clickedLinkCheck($(this).attr("href"),allowedPage);
							e.preventDefault();
							return false;
						})
					});
					function allowedPageToString(){
						var pages = {};
						var pageObj = <?php echo $this->session->userdata('allowed_page'); ?>;
						for(var k in pageObj){
							pages[k] = pageObj[k].slug +'/'+ pageObj[k].page;
						}
						return pages;
					}
					function clickedLinkCheck(href,pages){
						for(var k in pages){
							if(href.indexOf(pages[k]) > -1)
							location.href=href;
						}
					}
					console.log( "done" );
				});
			});
			// lunch
			function refreshme (){
				location.reload();
				$( document ).ready(function() {
					$( window ).load(function() {
						$(function() {
							if($(window).width() > 1900){
								$("body").css("visibility", "hidden");
								$("#container").zoomTo({
									targetsize:1.2,duration:150,animationendcallback : function(){
										$("#container").animate({
											top: +60
										},100)
										$("body").css("visibility", "visible");
									}
								});
							} else if($(window).width() > 1700){
								$("body").css("visibility", "hidden");
								$("#container").zoomTo({
									targetsize:1.15,duration:150,animationendcallback : function(){
										$("#container").animate({
											top: +50
										},100)
										$("body").css("visibility", "visible");
									}
								});
							} else if($(window).width() > 1500){
								$("body").css("visibility", "hidden");
								$("#container").zoomTo({
									targetsize:1.1,duration:150,animationendcallback : function(){
										$("#container").animate({
											top: +30
										},100)
										$("body").css("visibility", "visible");
									}
								});
							} else if($(window).width() > 1300){
								$("body").css("visibility", "hidden");
								$("#container").zoomTo({
									targetsize:1,duration:150,animationendcallback : function(){
										$("#container").animate({
										},100)
										$("body").css("visibility", "visible");
									}
								});
							}
							var allowedPage = allowedPageToString();
							$('a').on('click', function(e){
								clickedLinkCheck($(this).attr("href"),allowedPage);
								e.preventDefault();
								return false;
							})
						});
						function allowedPageToString(){
							var pages = {};
							var pageObj = <?php echo $this->session->userdata('allowed_page'); ?>;
							for(var k in pageObj){
								pages[k] = pageObj[k].slug +'/'+ pageObj[k].page;
							}
							return pages;
						}
						function clickedLinkCheck(href,pages){
							for(var k in pages){
								if(href.indexOf(pages[k]) > -1)
								location.href=href;
							}
						}
						console.log( "done" );
					});
				});
			}
		</script>
	</head>
	<body>
	<nav>
			
		</nav>
	<div id="container" style="position:relative; margin:auto;">	
	<div id="navbarcontainer" style="position:absolute; top:65px; z-index:1; width:1280px;">
		<div style="position:relative">
			<!-- <ul class="nav">
				<li><a href="#" class=""><i class="icon-wrench"></i></a></li>
				<li><a href="#" class=""><i class="icon-exchange"></i></a></li>
				<li><a href="#" class=""><i class="icon-money"></i></a></li>
				<li><a href="#" class=""><i class="icon-h-sign"></i></a></li>
			</ul> -->
			
			 <div id="navbar">
				<!--<a href="./graph_din.html"><img src="<?php echo $this->config->base_url(); ?>assets/img/construction.png" /></a>-->
				
				<a href="/ppms/programme/scurve"><img src="<?php echo $this->config->base_url(); ?>assets/img/programme2.png" /></a>
				<!--<a href="#"><img src="<?php echo $this->config->base_url(); ?>assets/img/commercial2.png" /></a>-->
				<a class="nopointer" href="#"><img src="<?php echo $this->config->base_url(); ?>assets/img/safety3.png" /></a>
				<a href="/ppms/procurement/summary"><img src="<?php echo $this->config->base_url(); ?>assets/img/procurement.png" /></a>
				<a href="#"><img src="<?php echo $this->config->base_url(); ?>assets/img/logout.png" onclick="location.href='/ppms/logout'"/></a>
			</div> 
			
		</div>
		<img src="<?php echo $this->config->base_url(); ?>assets/img/Dashboard-topbar.png" style="position: absolute;top: -77px;width: 1280px; z-index: -1;" />
		
		
	</div>
	<img src="<?php echo $this->config->base_url(); ?>assets/img/guideways-cleaned-opacity9.jpg" alt="" id="mapimg" style="width: 1280px; height:800px; position:absolute;"/>
	<div style="position: absolute; z-index: 99;top: -7px;left: -8px;">
		
		<!-- text href -->
		<a title="Sungai Buloh" href="<?php echo $this->config->base_url(); ?>sungai-buloh/index" style="position: absolute; top: 394px; left: 87px; height: 15px; width: 72px;"></a>
		<a title="Kg Selamat" href="<?php echo $this->config->base_url(); ?>kampung-selamat/index" style="position: absolute; top: 424px; left: 93px; height: 15px; width: 72px;"></a>
		<a title="Kwasa Damansara" href="<?php echo $this->config->base_url(); ?>kwasa-damansara/index" style="position: absolute; top: 512px; left: 140px; height: 23px; width: 72px;"></a>
		<a title="Kwasa Sentral" href="<?php echo $this->config->base_url(); ?>kwasa-sentral/index" style="position: absolute; top: 553px; left: 121px; height: 15px; width: 72px;"></a>
		<a title="Kota Damansara" href="<?php echo $this->config->base_url(); ?>kota-damansara/index" style="position: absolute; top: 584px; left: 219px; height: 15px; width: 72px;"></a>
		<a title="Surian" href="<?php echo $this->config->base_url(); ?>surian/index" style="position: absolute; top: 523px; left: 215px; height: 15px; width: 46px;"></a>
		<a title="Mutiara Damansara" href="<?php echo $this->config->base_url(); ?>mutiara-damansara/index" style="position: absolute; top: 463px; left: 217px; height: 15px; width: 72px;"></a>
		<a title="Bandar Utama" href="<?php echo $this->config->base_url(); ?>bandar-utama/index" style="position: absolute; top: 452px; left: 296px; height: 15px; width: 72px;"></a>
		<a title="TTDI" href="<?php echo $this->config->base_url(); ?>ttdi/index" style="position: absolute; top: 452px; left: 367px; height: 15px; width: 32px;"></a>
		<a title="Phileo Damansara" href="<?php echo $this->config->base_url(); ?>phileo-damansara/index" style="position: absolute; top: 429px; left: 373px; height: 15px; width: 72px;"></a>
		<a title="Pusat Bandar Damansara" href="<?php echo $this->config->base_url(); ?>pusat-bandar-damansara/index" style="position: absolute; top: 374px; left: 365px; height: 15px; width: 72px;"></a>
		<a title="Semantan" href="<?php echo $this->config->base_url(); ?>semantan/index" style="position: absolute; top: 327px; left: 362px; height: 15px; width: 72px;"></a>
		<a title="Muzium Negara" href="<?php echo $this->config->base_url(); ?>muzium-negara/index" style="position: absolute; top: 289px; left: 437px; height: 15px; width: 90px;"></a>
		<a title="Pasar Seni" href="<?php echo $this->config->base_url(); ?>pasar-seni/index" style="position: absolute; top: 260px; left: 458px; height: 15px; width: 61px;"></a>
		<a title="Merdeka" href="<?php echo $this->config->base_url(); ?>merdeka/index" style="position: absolute; top: 231px; left: 497px; height: 15px; width: 60px;"></a>
		<a title="Bukit Bintang" href="<?php echo $this->config->base_url(); ?>bukit-bintang/index" style="position: absolute; top: 198px; left: 514px; height: 15px; width: 60px;"></a>
		<a title="Tun Razak Exchange" href="<?php echo $this->config->base_url(); ?>tun-razak-exchange/index" style="position: absolute; top: 183px; left: 611px; height: 15px; width: 60px;"></a>
		<a title="Cochrane" href="<?php echo $this->config->base_url(); ?>cochrane/index" style="position: absolute; top: 206px; left: 646px; height: 15px; width: 60px;"></a>
		<a title="Maluri" href="<?php echo $this->config->base_url(); ?>maluri/index" style="position: absolute; top: 240px; left: 609px; height: 15px; width: 47px;"></a>
		<a title="Taman Pertama" href="<?php echo $this->config->base_url(); ?>taman-pertama/index" style="position: absolute; top: 235px; left: 668px; height: 15px; width: 60px;"></a>
		<a title="Taman Midah" href="<?php echo $this->config->base_url(); ?>taman-midah/index" style="position: absolute; top: 257px; left: 716px; height: 15px; width: 60px;"></a>
		<a title="Taman Mutiara" href="<?php echo $this->config->base_url(); ?>taman-mutiara/index" style="position: absolute; top: 271px; left: 786px; height: 15px; width: 60px;"></a>
		<a title="Taman Connaught" href="<?php echo $this->config->base_url(); ?>taman-connaught/index" style="position: absolute; top: 334px; left: 767px; height: 15px; width: 60px;"></a>
		<a title="Taman Suntex" href="<?php echo $this->config->base_url(); ?>taman-suntex/index" style="position: absolute; top: 268px; left: 845px; height: 15px; width: 48px;"></a>
		<a title="Sri Raya" href="<?php echo $this->config->base_url(); ?>sri-raya/index" style="position: absolute; top: 267px; left: 919px; height: 15px; width: 60px;"></a>
		<a title="Bandar Tun Hussein Onn" href="<?php echo $this->config->base_url(); ?>bandar-tun-hussein-onn/index" style="position: absolute; top: 308px; left: 998px; height: 15px; width: 71px;"></a>
		<a title="Bukit Dukung" href="<?php echo $this->config->base_url(); ?>bukit-dukung/index" style="position: absolute; top: 350px; left: 1017px; height: 15px; width: 55px;"></a>
		<a title="Taman Koperasi Cuepacs " href="<?php echo $this->config->base_url(); ?>taman-koperasi-cuepacs/index" style="position: absolute; top: 385px; left: 1053px; height: 15px; width: 85px;"></a>
		<a title="Sungai Kantan" href="<?php echo $this->config->base_url(); ?>sungai-kantan/index" style="position: absolute; top: 416px; left: 1119px; height: 15px; width: 50px;"></a>
		<a title="Bandar Kajang" href="<?php echo $this->config->base_url(); ?>bandar-kajang/index" style="position: absolute; top: 441px; left: 1176px; height: 15px; width: 50px;"></a>
		<a title="Kajang" href="<?php echo $this->config->base_url(); ?>kajang/index" style="position: absolute; top: 466px; left: 1220px; height: 15px; width: 50px;"></a>
		<a title="Sungai Buloh Depot" href="<?php echo $this->config->base_url(); ?>dpt1/index" style="position: absolute; top: 490px; left: 15px; height: 35px; width: 95px;"></a>
		<a title="Kajang Depot" href="<?php echo $this->config->base_url(); ?>dpt2/index" style="position: absolute; top: 475px; left: 1128px; height: 30px; width: 45px;"></a>
		
		<!-- icon href -->
		<a title="Sungai Buloh" href="<?php echo $this->config->base_url(); ?>sungai-buloh/index" style="position: absolute; top: 400px; left: 63px; height: 15px; width: 15px;"></a>
		<a title="Kg Selamat" href="<?php echo $this->config->base_url(); ?>kampung-selamat/index" style="position: absolute; top: 430px; left: 74px; height: 15px; width: 15px;"></a>
		<a title="Kwasa Damansara" href="<?php echo $this->config->base_url(); ?>kwasa-damansara/index" style="position: absolute; top: 518px; left: 120px; height: 15px; width: 15px;"></a>
		<a title="Kwasa Sentral" href="<?php echo $this->config->base_url(); ?>kwasa-sentral/index" style="position: absolute; top: 553px; left: 112px; height: 15px; width: 15px;"></a>
		<a title="Kota Damansara" href="<?php echo $this->config->base_url(); ?>kota-damansara/index" style="position: absolute; top: 576px; left: 205px; height: 15px; width: 15px;"></a>
		<a title="Surian" href="<?php echo $this->config->base_url(); ?>surian/index" style="position: absolute; top: 523px; left: 258px; height: 15px; width: 15px;"></a>
		<a title="Mutiara Damansara" href="<?php echo $this->config->base_url(); ?>mutiara-damansara/index" style="position: absolute; top: 466px; left: 278px; height: 15px; width: 15px;"></a>
		<a title="Bandar Utama" href="<?php echo $this->config->base_url(); ?>bandar-utama/index" style="position: absolute; top: 474px; left: 338px; height: 15px; width: 15px;"></a>
		<a title="TTDI" href="<?php echo $this->config->base_url(); ?>ttdi/index" style="position: absolute; top: 470px; left: 386px; height: 15px; width: 15px;"></a>
		<a title="Phileo Damansara" href="<?php echo $this->config->base_url(); ?>phileo-damansara/index" style="position: absolute; top: 452px; left: 423px; height: 15px; width: 15px;"></a>
		<a title="Pusat Bandar Damansara" href="<?php echo $this->config->base_url(); ?>pusat-bandar-damansara/index" style="position: absolute; top: 376px; left: 437px; height: 15px; width: 15px;"></a>
		<a title="Semantan" href="<?php echo $this->config->base_url(); ?>semantan/index" style="position: absolute; top: 328px; left: 426px; height: 15px; width: 15px;"></a>
		<a title="Muzium Negara" href="<?php echo $this->config->base_url(); ?>muzium-negara/index" style="position: absolute; top: 290px; left: 524px; height: 15px; width: 15px;"></a>
		<a title="Pasar Seni" href="<?php echo $this->config->base_url(); ?>pasar-seni/index" style="position: absolute; top: 256px; left: 520px; height: 15px; width: 15px;"></a>
		<a title="Merdeka" href="<?php echo $this->config->base_url(); ?>merdeka/index" style="position: absolute; top: 233px; left: 549px; height: 15px; width: 15px;"></a>
		<a title="Bukit Bintang" href="<?php echo $this->config->base_url(); ?>bukit-bintang/index" style="position: absolute; top: 199px; left: 559px; height: 15px; width: 15px;"></a>
		<a title="Tun Razak Exchange" href="<?php echo $this->config->base_url(); ?>tun-razak-exchange/index" style="position: absolute; top: 203px; left: 600px; height: 15px; width: 15px;"></a>
		<a title="Cochrane" href="<?php echo $this->config->base_url(); ?>cochrane/index" style="position: absolute; top: 217px; left: 628px; height: 15px; width: 15px;"></a>
		<a title="Maluri" href="<?php echo $this->config->base_url(); ?>maluri/index" style="position: absolute; top: 230px; left: 650px; height: 15px; width: 15px;"></a>
		<a title="Taman Pertama" href="<?php echo $this->config->base_url(); ?>taman-pertama/index" style="position: absolute; top: 258px; left: 688px; height: 15px; width: 15px;"></a>
		<a title="Taman Midah" href="<?php echo $this->config->base_url(); ?>taman-midah/index" style="position: absolute; top: 277px; left: 730px; height: 15px; width: 15px;"></a>
		<a title="Taman Mutiara" href="<?php echo $this->config->base_url(); ?>taman-mutiara/index" style="position: absolute; top: 283px; left: 773px; height: 15px; width: 15px;"></a>
		<a title="Taman Connaught" href="<?php echo $this->config->base_url(); ?>taman-connaught/index" style="position: absolute; top: 313px; left: 818px; height: 15px; width: 15px;"></a>
		<a title="Taman Suntex" href="<?php echo $this->config->base_url(); ?>taman-suntex/index" style="position: absolute; top: 280px; left: 895px; height: 15px; width: 15px;"></a>
		<a title="Sri Raya" href="<?php echo $this->config->base_url(); ?>sri-raya/index" style="position: absolute; top: 284px; left: 925px; height: 15px; width: 15px;"></a>
		<a title="Bandar Tun Hussein Onn" href="<?php echo $this->config->base_url(); ?>bandar-tun-hussein-onn/index" style="position: absolute; top: 311px; left: 978px; height: 15px; width: 15px;"></a>
		<a title="Bukit Dukung" href="<?php echo $this->config->base_url(); ?>bukit-dukung/index" style="position: absolute; top: 353px; left: 999px; height: 15px; width: 15px;"></a>
		<a title="Taman Koperasi Cuepacs " href="<?php echo $this->config->base_url(); ?>taman-koperasi-cuepacs/index" style="position: absolute; top: 391px; left: 1028px; height: 15px; width: 15px;"></a>
		<a title="Sungai Kantan" href="<?php echo $this->config->base_url(); ?>sungai-kantan/index" style="position: absolute; top: 434px; left: 1127px; height: 15px; width: 15px;"></a>
		<a title="Bandar Kajang" href="<?php echo $this->config->base_url(); ?>bandar-kajang/index" style="position: absolute; top: 450px; left: 1160px; height: 15px; width: 15px;"></a>
		<a title="Kajang" href="<?php echo $this->config->base_url(); ?>kajang/index" style="position: absolute; top: 478px; left: 1203px; height: 15px; width: 15px;"></a>
		<a title="Kajang Depot" href="<?php echo $this->config->base_url(); ?>dpt2/index" style="position: absolute; top: 455px; left: 1137px; height: 19px; width: 20px;"></a>

		<a title="MSPR1" href="<?php echo $this->config->base_url(); ?>mspr1/index" style="position: absolute; top: 400px; left: 20px; height: 40px; width: 40px;"></a>
		<a title="MSPR4" href="<?php echo $this->config->base_url(); ?>mspr4/index" style="position: absolute; top: 475px; left: 425px; height: 40px; width: 60px;"></a>
		<a title="MSPR6" href="<?php echo $this->config->base_url(); ?>mspr6/index" style="position: absolute; top: 300px; left: 710px; height: 30px; width: 40px;"></a>
		<a title="MSPR8" href="<?php echo $this->config->base_url(); ?>mspr8/index" style="position: absolute; top: 300px; left: 880px; height: 40px; width: 45px;"></a>
		<a title="MSPR9" href="<?php echo $this->config->base_url(); ?>mspr9/index" style="position: absolute; top: 497px; left: 1193px; height: 40px; width: 40px;"></a>
		<a title="MSPR11" href="<?php echo $this->config->base_url(); ?>mspr11/index" style="position: absolute; top: 455px; left: 1060px; height: 18px; width: 70px;"></a>
		
	</div>
	
	<div style="position:absolute; top: 258px;left: 129px;"><img src="<?php echo $this->config->base_url(); ?>assets/img/arrow2.png" style="width:20px;"/></div>
	
	<div id="project_progress_container" style="position:absolute; z-index:2; top: 119px;left: 30px;">
		<span id="overall_actual" class="header-left" style="font-size:90px;"></span>
		<!-- <span class="subheader-left" style="">%</span> -->
	</div>
	
	
	<div style="position:absolute; z-index:2; top: 125px;left: 179px;">
		<span class="header-left" style="font-size:72px;" id="overall_early"></span>
		<!-- <span class="subheader-left" style="">%</span> -->
	</div>
	
	<div style="position:absolute; z-index:2; top: 209px; right: 1029px;">
		<span class="header-left" style="font-size:37px;" id="overall_variance"></span>
		<!-- <span class="subheader-left" style="">%</span> -->
	</div>
	
	<div style="position:absolute; z-index:2; top: 95px; left: 134px;">
		<span class="header-left"><i class="fa fa-calendar" style="color:#77DD77; margin-right:7px;"></i></span><span class="header-left" style="font-size:12px; color:#77DD77; line-height:20px" id="overall_date">As of <span style="color: #f3b308" id="progress_date"></span></span>		
	</div>
	
	<div style="position:absolute; z-index:2; top: 88px;left: 807px;">
		<span class="header-left"><i class="fa fa-calendar" style="color:#77DD77; margin-right:7px;"></i></span><span class="header-left" style="font-size:12px; color:#77DD77; line-height:20px">As of <span id="financial_date" style="color: #f3b308">30 September 2014</span></span>
	</div>
	
	<div style="position:absolute; z-index:2; top: 112px;left: 639px;">
		<span class="header-left" style="font-size:24px;" id="project_spend_to_date"><?php echo number_format($data['project_spend_to_date'], 2, '.', ','); ?> Bil</span>
	</div>
	
	<div style="position:absolute; z-index:2; top: 162px;left: 830px;">
		<span class="header-left" style="font-size:24px;" id="pdp_reimbursables"><?php echo number_format($data['pdp_reimbursables'], 2, '.', ',');?> Mil</span>
	</div>
	
	<div style="position:absolute; z-index:2; top: 112px;left: 830px;">
		<span class="header-left" style="font-size:24px;" id="awarded_packages"><?php echo number_format($data['awarded_packages'], 2, '.', ','); ?> Bil</span>
	</div>
	
	
	<div style="position:absolute; z-index:2;top: 162px;left: 988px;">
		<span class="header-left" style="font-size:24px;" title="" id="retention_sum"><?php echo number_format($data['retention_sum'], 2, '.', ','); ?> Mil</span>
	</div>
	
	
	<div style="position:absolute; z-index:2;top: 112px;left: 988px;">
		<span class="header-left" style="font-size:24px;" title="" id="wpcs_payment"><?php echo number_format($data['wpcs_payment'], 2, '.', ','); ?> Bil</span>
	</div>
	
	
	<div style="position:absolute; z-index:2;top: 162px;left: 1132px;">
		<span class="header-left" style="font-size:24px;" id="contingency_sum"><?php echo number_format($data['contingency_sum'], 2, '.', ','); ?> Mil</span>
	</div>
	
	<div style="position:absolute; z-index:2; top: 112px;left: 1132px;">
		<span class="header-left" style="font-size:24px;" id="variation_orders"><?php echo number_format($data['variation_orders'], 2, '.', ','); ?> Mil</span>
	</div>
	
	<div style="position:absolute; z-index:2; top: 441px;left: 21px;">
		<a href="<?php echo $this->config->base_url(); ?>v1/index" class="pkg_title">V1</a>
	</div>	
	<div style="position:absolute; z-index:2; top: 608px;left: 161px;">
		<a href="<?php echo $this->config->base_url(); ?>v2/index" class="pkg_title">V2</a>
	</div>
	<div style="position:absolute; z-index:2; top: 540px;left: 400px;">
		<a href="<?php echo $this->config->base_url(); ?>v3/index" class="pkg_title">V3</a>
	</div>
	<div style="position:absolute; z-index:2; top: 460px;left: 495px;">
		<a href="<?php echo $this->config->base_url(); ?>v4/index" class="pkg_title">V4</a>
	</div>	
	<div style="position:absolute; z-index:2; top: 377px;left: 573px;">
		<a href="<?php echo $this->config->base_url(); ?>ug/index" class="pkg_title">UG</a>
	</div>
	<div style="position:absolute; z-index:2; top: 385px;left: 731px;">
		<a href="<?php echo $this->config->base_url(); ?>v5/index" class="pkg_title">V5</a>
	</div>
	<div style="position:absolute; z-index:2; top: 465px;left: 893px;">
		<a href="<?php echo $this->config->base_url(); ?>v6/index" class="pkg_title">V6</a>
	</div>
	<div style="position:absolute; z-index:2; top: 540px;left: 1033px;">
		<a href="<?php echo $this->config->base_url(); ?>v7/index" class="pkg_title">V7</a>
	</div>
	<div style="position:absolute; z-index:2; top: 595px;left: 1136px;">
		<a href="<?php echo $this->config->base_url(); ?>v8/index" class="pkg_title">V8</a>
	</div>
	<div style="position:absolute; z-index:2; top: 350px;left: 200px;">
		<a href="<?php echo $this->config->base_url(); ?>north/index" class="pkg_title2">North</a>
	</div>
	<div style="position:absolute; z-index:2; top: 290px;left: 1110px;">
		<a href="<?php echo $this->config->base_url(); ?>south/index" class="pkg_title2">South</a>
	</div>
	<!-- 
	
<area  alt="" title="" href="http://www.image-maps.com/" shape="rect" coords="554,639,671,674" style="outline:none;" target="_self"     />
<area  alt="" title="" href="http://www.image-maps.com/" shape="rect" coords="679,639,782,674" style="outline:none;" target="_self"     />
<area  alt="" title="" href="http://www.image-maps.com/" shape="rect" coords="786,639,885,674" style="outline:none;" target="_self"     />
 -->
	<div id="reset" style="display:none; position: absolute; top: 579px; left: 710px;z-index: 5;"><img src="<?php echo $this->config->base_url(); ?>assets/img/reset.png" style="width: 43px;"/></div>
	<div id="onschedule" class="togglebutton" style="top:558px; left:580px;"  ></div>
	<div id="critical" class="togglebutton" style=" top:558px; left:679px;"  ></div>
	<div id="delayed" class="togglebutton" style="top:558px; left:780px;"  ></div>
	<!--<map name="imgmap" id="ImageMapsCom-image-maps-2014-06-21-092652">
	<area  alt="" title="" href="http://www.image-maps.com/" shape="rect" coords="1097,193,1205,228" style="outline:none;" target="_self"    onclick="alert('asd');" />
	<area  alt="" title="" href="http://www.image-maps.com/" shape="rect" coords="1096,282,1205,315" style="outline:none;" target="_self"     />
	<area  alt="" title="" href="http://www.image-maps.com/" shape="rect" coords="1094,240,1208,273" style="outline:none;" target="_self"     />
	</map>
	<div id="awarded" style="min-width: 300px; height: 400px; position:absolute; top: 410px; left: 522px;"></div>-->
	</div>
	
	<!--<script type=text/javascript src=<?php echo $this->config->base_url(); ?>assets/js/highcharts.js></script> -->
		<script type="text/javascript">
		
		//var polyfilter_scriptpath = '/ceo/lib/';
		

			
		window.scrollTo(0,1);
		$(window).load(function(){
//			data = <?php //echo json_encode($data); ?>//;
            data = {"overall_actual":80,"overall_early":82,"overall_late":81,"overall_variance":-3,"progress_date":"31-Jan-16","comdate":"31-Jan-16","project_spend_to_date":15.50896,"awarded_packages":21.35293,"wpcs_payment":12.83955,"variation_orders":407.05,"pdp_reimbursables":1446.98,"retention_sum":398.4,"contingency_sum":548.16,"V1":"-16.5","V2":"-3.5","V3":"-17.0","V4":"-20.0","DPT1":"-11.5","MSPR1":"-32.0","MSPR4":"-14.0","V5":"11.0","V6":"-10.0","V7":"-5.5","V8":"-34.0","DPT2":"-8.5","MSPR6":"-10.0","MSPR8":"-7.0","MSPR9":"-13.0","MSPR11":"-2.0","SBK-S-01":"-2.0","SBK-S-02":"9.5","SBK-S-03":"3.0","SBK-S-04":"11.0","SBK-S-05":"2.0","SBK-S-06":"1.0","SBK-S-07":"10.5","SBK-S-08":"12.0","SBK-S-09":"3.5","SBK-S-10":"4.0","SBK-S-11":"-4.5","SBK-S-12":"19.0","SBK-S-13":"3.0","SBK-S-14":"-"};
			if (data['overall_actual'] > 99) $('#overall_actual').css({ "fontSize" : "59px", "marginTop" : "31px"});
			if (data['overall_variance'] > 99) $('#overall_variance').css({ "fontSize" : "59px", "marginTop" : "10px", "marginLeft" : "-8px"});
			if (data['overall_early'] > 99) $('#overall_early').css({ "fontSize" : "59px", "marginTop" : "6px", "marginLeft" : "-8px"});
		
			$('#overall_actual').text(data['overall_actual']);
			//$('#overall_variance').text(data['overall_variance'].toFixed(0));
			$('#overall_variance').text(data['overall_variance']);
			$('#overall_early').text(data['overall_late']);
			$('#progress_date').text(prettyDate(data['progress_date']));
			$('#financial_date').text(prettyDate(data['comdate']));
		
			$i = $('#mapimg');
			//Width and height
			var w = $i.width();
			var h = $i.height();
			
			
			var station_circle_size = 9;
			var parking_box_size = 15;
			//var parking_box_height = 10;
			//[[44.21666666666667,81.65925925925926],[55.31666666666667,81.6],[63.93333333333333,81.62962962962963]]
			//Data
			stations = [
			[5.066666666666666,50.162962962962965,93, "v1"],
			[5.916666666666667,54.04444444444444,95, "v1"],
			[9.466666666666667,64.88888888888889,95, "v1"],
			[8.883333333333333,69.3037037037037,99, "v2"],
			[16.166666666666664,72.23703703703703,90, "v2"],
			[20.233333333333334,65.62962962962963,90, "v2"],
			[21.883333333333333,58.42962962962963,78, "v3"],
			[26.55,59.58518518518518,90, "v3"],
			[30.283333333333335,58.84444444444444,90, "v3"],
			[33.18333333333333,56.62222222222222,90, "v4"],
			[34.25,47.17037037037037,90, "v4"],
			[33.38333333333333,41.15555555555556,96, "v4"],
			[41.05,36.50370370370371,74, "ug"],
			[40.71666666666667,32.148148148148145,47, "ug"],
			[42.983333333333334,29.333333333333332,99, "ug"],
			[43.78333333333334,25.125925925925923,39, "ug"],
			[47.099999999999994,25.6,72, "ug"],
			[49.166666666666664,27.348148148148148,75, "ug"],
			[50.949999999999996,29.037037037037038,82, "ug"],
			[53.88333333333334,32.41481481481481,47, "v5"],
			[57.15,34.785185185185185,84, "v5"],
			[60.45,35.644444444444446,94, "v5"],
			[63.96666666666667,39.2,77, "v5"],
			[70.05,35.288888888888884,78, "v6"],
			[72.33333333333334,35.7037037037037,90, "v6"],
			[76.48333333333333,39.08148148148148,90, "v6"],
			[78.18333333333334,44.32592592592592,90, "v7"],
			[80.48333333333333,49.03703703703704,90, "v7"],
			[88.16666666666667,54.48888888888889,71, "v8"],
			[90.71666666666667,56.38518518518518,90, "v8"],
			[94.1,59.88148148148148,90, "v8"],     
			/* Legend */             [58.2,73.4, 10, 1],[51.4,73.4, 50, 1],[44.3,73.4, 100, 1],
			// /* Legend */             [33.4,81.8, 10, 1],[26.2,81.8, 50, 1],[18.5,81.8, 100, 1], V2
			/* System */
			// [46.57000,79.95, 100, "sbk-s-01"],
			// [48.60500,79.95, 100, "sbk-s-02"],
			// [50.64000,79.95, 100, "sbk-s-03"],
			// [52.67500,79.95, 100, "sbk-s-04"],
			// [54.71000,79.95, 100, "sbk-s-05"],
			// [56.74500,79.95, 100, "sbk-s-06"],
			// [58.78000,79.95, 100, "sbk-s-07"],
			// [60.81500,79.95, 100, "sbk-s-08"],
			// [62.85000,79.95, 100, "sbk-s-09"],
			// [64.88500,79.95, 100, "sbk-s-10"],
			// [66.92000,79.95, 100, "sbk-s-11"],
			// [68.95500,79.95, 100, "sbk-s-12"],
			// [70.99000,79.95, 100, "sbk-s-13"],
			// [73.02500,79.95, 100, "sbk-s-14"]
			// V1
//			[44.65,64.2, 100, "sbk-s-01"],
//			[48.65,64.2, 100, "sbk-s-02"],
//			[52.65,64.2, 100, "sbk-s-03"],
//			[56.65,64.2, 100, "sbk-s-04"],
////            [60.65,64.2, 100, "sbk-s-05"],
//			[60.65,64.2, 100, "sbk-s-05"],
//			[64.65,64.2, 100, "sbk-s-06"],//aaa
//			[40.65,72.4, 100, "sbk-s-07"],
//			[44.65,72.4, 100, "sbk-s-08"],
//			[48.65,72.4, 100, "sbk-s-09"],
//			[52.65,72.4, 100, "sbk-s-10"],
//			[56.65,72.4, 100, "sbk-s-11"],
//			[60.65,72.4, 100, "sbk-s-12"],
//			[64.65,72.4, 100, "sbk-s-13"],
//			[68.65,72.4, 100, "sbk-s-14"]
			 ];
			parkings = [
			[2.7000000000000003,49.36296296296297,68],
			//[6.800000000000001,68.5925925925926,82],
			//[10.05,65.8074074074074,86],
			[26.166666666666664,61.392592592592585,67, true],
			[29.916666666666668,60.91851851851852,78, true],
			[32.95,58.72592592592593,99],
			[35.4,46.400000000000006,75, true],
			//[50.38333333333333,30.992592592592594,86],
			[56.53333333333334,36.82962962962963,74],
			[64.8,40.41481481481482,73, true],
			[69.68333333333334,37.007407407407406,91],
			//[72.08333333333333,37.6,100],
			//[75.78333333333333,40.474074074074075,62],
			[86.6,56.15,20],
			//[88.86666666666667,51.55555555555556,61],
			//[93.60000000000001,61.86666666666667,73]
			[93.60000000000001,61.86666666666667,73]
			];
			paths = [
			["M112.55,658.879c-0.843-0.005-1.686-0.011-2.529-0.017     c-0.706-2.075,0.736-3.454,1.64-4.995c3.193-5.437,6.676-10.679,9.419-16.407c5.158-10.769,2.337-20.279-3.837-29.428     c-1.758-2.604-4.034-2.747-6.299-0.631c-3.284,3.065-6.432,6.275-9.737,9.316c-0.852,0.783-1.691,2.711-3.178,1.441     c-1.853-1.58,0.232-2.735,1.104-3.586c4.478-4.372,8.177-9.723,14.403-11.98c0.654-0.237,1.178-0.351,1.208-1.201     c0.1-2.799-5.705-10.712-8.772-11.135c-3.587-0.495-5.109-2.278-5.823-5.663c-0.667-3.166-2.614-5.542-5.636-7.189     c-4.559-2.485-8.169-5.974-11.198-10.362c-6.704-9.713-13.936-19.061-20.854-28.628c-2.084-2.882-3.558-6.103-2.403-9.803     c0.245-0.785,0.565-2.164,1.818-1.883c1.072,0.24,1.558,1.385,1.141,2.392c-1.492,3.603,0.909,6.101,2.555,8.622     c6.916,10.59,15.601,19.893,22.393,30.592c1.825,2.876,4.894,4.549,7.786,6.131c3.776,2.066,6.187,5.116,7.178,9.105     c0.591,2.381,1.69,3.325,3.989,3.573c3.473,0.375,5.553,2.518,6.977,5.647c3.779,8.31,8.045,16.416,11.468,24.866     c2.513,6.205,1.456,12.79-0.705,18.979C121.848,644.682,116.066,651.178,112.55,658.879z",80,false,"v1"],
			["M261.055,618.955c0.852,0.835,1.704,1.668,2.554,2.5     c0.461,1.761,0.844,3.551,1.399,5.285c3.083,9.637,1.203,17.264-6.606,24.617c-17.919,16.875-35.038,34.603-52.39,52.075     c-4.702,4.734-9.671,7.208-16.457,4.611c-4.329-1.656-9.269-1.97-13.3-4.093c-7.254-3.819-14.717-3.301-22.339-2.786     c-9.002,0.607-17.943-0.125-26.875-1.214c-5.06-0.616-8.533-3.673-9.913-8.049c-3.394-10.759-6.002-21.756-7.106-33.04     c0.848-0.678,1.691-0.78,2.529,0.017c1.917,9.088,3.709,18.204,5.785,27.256c1.839,8.021,4.336,10.209,12.431,11.17     c9.603,1.139,19.268,1.357,28.877,0.428c5.472-0.53,10.493,0.179,15.459,2.379c5.64,2.5,11.616,3.855,17.62,5.253     c3.976,0.925,6.855-0.056,9.456-2.645c19.555-19.462,39.086-38.947,58.582-58.469c2.94-2.943,3.353-6.643,2.744-10.636     C262.758,628.715,260.538,624.062,261.055,618.955z",80,false,"v2"],
			["M411.223,583.677c-3.313,2.814-7.998,5.854-11.956,5.711c-11.073-0.396-21.756,5.479-32.94,1.662     c-2.487-0.849-5.306-0.689-7.844-1.433c-2.808-0.822-4.624,0.197-6.699,1.952c-6.422,5.429-14.246,6.256-21.869,4.375     c-11.931-2.946-23.614-6.905-35.385-10.491c-5.555-1.692-10.001-0.396-13.784,4.139c-5.756,6.902-11.671,13.681-17.732,20.315     c-2.546,2.787-3.385,5.579-1.958,9.048c0.964,0.717,0.437,2.956,2.548,2.501c-1.505-5.899,1.642-9.835,5.404-13.79     c4.816-5.063,9.363-10.396,13.802-15.795c2.76-3.354,5.831-4.451,10.092-3.393c12.44,3.089,24.451,7.747,36.893,10.614     c7.738,1.785,16.076,1.545,22.898-4.176c1.553-1.304,2.975-2.6,5.293-2.002c10.735,2.766,21.483,2.624,32.402,0.764     c5.684-0.968,11.189-1.293,16.596-4.181c3.207-1.713,6.267-3.759,9.418-5.633c-0.705-0.801-1.356-1.648-1.97-2.526     C413.329,582.027,412.257,582.799,411.223,583.677z",70,false,"v3"],
			//["M1205.751,603.867c-5.438-6.076-10.651-12.367-16.335-18.203c-4.552-4.677-8.542-4.839-14.48-1.631     c-6.539,3.534-9.185,3.1-11.357-4.108c-2.601-8.618-8.694-11.609-16.423-13.037c-5.756-1.063-10.995-3.118-16.319-5.704     c-8.02-3.892-16.024-8.895-25.888-6.812c-3.188,0.674-6.658-0.288-9.767-1.587c-10.738-4.487-21.212-9.531-31.479-15.023     c-7.365-3.938-13.378-9.921-20.672-13.823c-11.312-6.056-21.715-13.573-32.956-19.708c-1.48-0.808-2.661-1.603-3.224-3.478     c-4.107-13.664-8.439-27.262-12.576-40.917c-3.982-13.14-12.279-27.976-28.844-30.315c-1.104-0.156-2.115-1.521-3.016-2.467     c-4.711-4.941-9.93-9.525-13.909-15.005c-5.041-6.941-11.089-7.399-18.376-5.171c-4.181,1.28-8.439,2.379-11.754-2.063     c-0.502-0.674-1.756-1.139-2.644-1.113c-6.282,0.184-12.575-1.581-18.864,0.446c-3.847,1.238-7.924,0.939-11.906,0.456     c-7.125-0.867-13.894,0.282-20.501,3.024c-4.601,1.91-9.387,3.368-14,5.249c-5.803,2.367-12.685,3.281-16.648,8.518     c-4.833,6.386-11.962,9.446-17.923,14.156c-2.777,2.193-5.72,2.051-8.518-0.62c-3.364-3.211-7.125-6.009-10.487-9.224     c-7.411-7.084-15.665-12.97-25.038-17.057c-7.107-3.101-13.914-7.311-21.96-7.721c-9.65-0.489-19.307-0.902-28.965-1.14     c-2.808-0.069-5.181-0.824-7.432-2.371c-5.212-3.581-10.777-6.731-15.607-10.766c-7.587-6.335-15.224-9.724-24.474-3.625     c-0.132,0.087-0.319,0.09-0.479,0.137c-4.145,1.211-7.503-0.354-8.306-4.521c-0.846-4.387-1.492-8.927-1.326-13.365     c0.288-7.685,0.579-7.669-6.941-10.304c-18.483-6.478-35.991-14.652-50.875-27.811c-7.302-6.454-15.951-10.824-25.297-13.619     c-9.844-2.943-17.305-0.234-22.035,8.642c-5.331,10.003-10.098,20.329-8.067,32.25c0.541,3.176-0.728,5.398-3.836,5.925     c-8.956,1.516-14.946,7.271-20.857,13.533c-7.051,7.47-7.104,7.204-1.786,16.31c2.295,3.928,5.571,7.851,4.86,12.74     c-2.107,14.504-9.888,25.486-22.898,31.892c-8.669,4.27-18.044,5.312-25.771-2.815c-4.108-4.32-8.906-5.693-14.785-5.241     c-5.046,0.388-10.118,0.555-15.104-1.806c-3.441-1.629-6.787-0.25-8.398,3.811c-1.53,3.857-3.346,7.599-4.846,11.465     c-0.969,2.496-1.901,4.358-5.245,3.982c-2.249-0.253-3.302,1.883-4.042,3.817c-2.398,6.271,0.114,13.296,5.994,16.776     c8.667,5.133,9.225,7.017,6.209,16.371c-2.509,7.784-2.753,15.642,0.387,23.455c1.737,4.323,3.368,8.896,0.98,13.468     c-6.057,11.593-7.726,23.843-6.44,36.749c0.509,5.104,0.444,10.299-0.257,15.459c-0.365,2.686-1.513,3.936-4.131,4.795     c-5.358,1.76-10.815,3.174-15.685,6.216c0.613,0.878,1.265,1.726,1.97,2.526c4.238-2.52,8.644-4.729,13.803-5.386     c5.092-0.649,6.928-4.272,7.041-9.28c0.106-4.661,0.394-9.298,0.042-13.973c-0.855-11.39-0.501-22.742,5.09-33.026     c2.997-5.512,3.356-10.996,1.121-16.313c-3.792-9.021-3.413-17.684-0.216-26.696c2.477-6.981,0.978-10.442-4.93-14.781     c-2.782-2.041-6.075-3.486-7.853-6.779c-2.086-3.862-1.156-10.206,1.894-10.249c4.306-0.062,5.627-2.372,6.833-5.794     c1.318-3.743,3.391-7.219,4.757-10.948c1.061-2.897,3.309-3.913,5.417-2.636c5.958,3.613,12.336,1.852,18.516,2.043     c3.384,0.104,5.994,0.939,8.511,3.195c2.807,2.516,5.345,5.761,9.104,6.711c6.576,1.661,13.267,1.629,19.569-1.291     c13.013-6.031,21.238-16.27,25.197-29.895c1.087-3.741,2.263-7.748,0.195-11.677c-2.315-4.398-4.098-9.097-7.306-12.99     c-0.872-1.06-1.808-1.996-0.296-3.324c7.222-6.349,12.27-15.432,23.25-16.962c4.645-0.648,7.771-4.058,6.828-9.371     c-1.928-10.882,2.446-20.246,7.034-29.487c4.063-8.179,9.511-10.54,18.55-8.381c9.225,2.203,17.405,6.669,24.597,12.735     c13.758,11.606,28.898,20.657,46.083,26.208c3.158,1.021,6.163,2.514,9.294,3.636c1.886,0.676,2.503,1.693,2.168,3.721     c-0.884,5.344,0.184,10.634,0.98,15.878c0.972,6.377,5.897,9.29,11.971,7.414c2.54-0.785,5.173-1.414,7.545-2.563     c3.102-1.507,5.553-0.838,8.218,1.029c7.224,5.06,14.711,9.751,21.813,14.972c3.441,2.528,6.925,3.335,11.079,3.417     c10.314,0.205,21.036-0.775,30.829,1.72c13.98,3.563,27.07,10.176,38.129,20.004c5.224,4.641,10.538,9.179,15.758,13.825     c2.236,1.992,4.847,2.803,7.334,1.235c6.718-4.235,14.19-7.548,19.341-13.857c2.189-2.683,4.542-4.949,7.713-6.062     c16.169-5.672,31.469-14.917,49.754-12.019c3.633,0.577,7.291-1.219,10.876-1.104c6.198,0.199,13.003-2.009,18.219,3.621     c1.178,1.273,2.879,1.444,4.654,0.936c2.717-0.778,5.479-1.397,8.229-2.058c4.629-1.113,8.494-0.745,11.918,3.48     c4.81,5.939,10.346,11.288,15.538,16.919c1.263,1.368,2.751,3.352,4.397,2.947c4.721-1.16,7.009,2.543,10.408,4.167     c8.52,4.069,12.295,12.241,14.771,19.795c4.175,12.74,9.062,25.352,11.933,38.495c1.564,7.165,5.3,11.381,11.696,15.236     c13.348,8.042,27.181,15.328,39.752,24.73c10.816,8.09,23.548,12.971,35.69,18.789c4.749,2.274,9.95,4.523,14.827,3.579     c7.659-1.486,10.517,3.228,13.896,8.013c0.479,0.677,0.937,1.373,1.352,2.091c3.184,5.507,7.192,10.372,11.514,15.008     c0.694,0.744,1.28,1.874,2.339,1.152c1.147-0.783,0.919-2.064,0.071-3.063c-1.4-1.651-2.842-3.271-4.329-4.842     c-4.387-4.635-7.02-10.403-10.494-15.762c4.275,0.878,8.206,2.122,11.768,4.276c4.31,2.604,8.775,4.835,13.839,5.359     c8.334,0.864,14.509,3.708,17.512,12.719c2.735,8.203,7.335,9.171,14.631,4.555c3.973-2.514,7.324-2.439,10.847,0.697     c3.251,2.894,6.076,6.148,8.871,9.454c3.244,3.838,6.527,7.642,10.196,11.931     C1206.713,606.525,1206.897,605.146,1205.751,603.867z",80],
			["M442.508,305.785c-1.53,3.857-3.347,7.6-4.847,11.465c-0.969,2.496-1.9,4.358-5.244,3.982		c-2.249-0.253-3.303,1.883-4.042,3.816c-2.398,6.271,0.113,13.297,5.993,16.776c8.667,5.133,9.226,7.018,6.209,16.371		c-2.509,7.784-2.753,15.642,0.388,23.455c1.736,4.323,3.368,8.896,0.979,13.468c-6.057,11.593-7.726,23.843-6.439,36.749		c0.509,5.104,0.443,10.3-0.257,15.459c-0.365,2.687-1.514,3.937-4.132,4.795c-5.357,1.761-10.814,3.175-15.685,6.217		c0.613,0.878,1.265,1.726,1.97,2.525c4.238-2.52,8.645-4.729,13.803-5.386c5.093-0.649,6.929-4.271,7.041-9.28		c0.106-4.661,0.395-9.298,0.042-13.973c-0.854-11.391-0.501-22.742,5.091-33.026c2.997-5.512,3.355-10.996,1.12-16.313		c-3.791-9.021-3.412-17.685-0.216-26.696c2.478-6.981,0.979-10.442-4.93-14.781c-2.782-2.041-6.075-3.486-7.853-6.779		c-2.087-3.861-1.156-10.206,1.894-10.249c4.306-0.062,5.627-2.372,6.833-5.794c1.318-3.742,3.391-7.219,4.757-10.947		c0.043-0.118,0.1-0.214,0.147-0.326c-0.76-0.917-1.388-1.875-2.016-2.786C442.894,304.914,442.688,305.33,442.508,305.785z",70,true,"v4"],
			["M659.367,232.692c-0.604-0.221-1.258-0.454-2.011-0.717		c-18.482-6.479-35.991-14.652-50.875-27.812c-7.302-6.454-15.951-10.823-25.297-13.619c-9.844-2.942-17.305-0.233-22.035,8.643		c-5.331,10.003-10.098,20.329-8.066,32.25c0.541,3.176-0.729,5.397-3.837,5.925c-8.955,1.516-14.945,7.271-20.856,13.533		c-7.051,7.47-7.104,7.204-1.786,16.31c2.295,3.929,5.571,7.852,4.86,12.74c-2.107,14.504-9.889,25.486-22.898,31.892		c-8.669,4.271-18.044,5.313-25.771-2.814c-4.108-4.32-8.906-5.693-14.785-5.241c-5.046,0.388-10.118,0.555-15.104-1.806		c-3.055-1.446-6.033-0.516-7.79,2.553c0.628,0.911,1.255,1.869,2.016,2.786c1.109-2.631,3.248-3.535,5.27-2.311		c5.958,3.613,12.336,1.853,18.517,2.043c3.384,0.104,5.993,0.939,8.511,3.195c2.807,2.516,5.345,5.761,9.104,6.711		c6.576,1.661,13.268,1.629,19.569-1.291c13.013-6.031,21.238-16.27,25.197-29.895c1.087-3.741,2.263-7.748,0.194-11.678		c-2.314-4.397-4.098-9.097-7.306-12.989c-0.872-1.061-1.808-1.996-0.296-3.324c7.222-6.349,12.27-15.432,23.25-16.962		c4.645-0.648,7.771-4.059,6.828-9.371c-1.928-10.882,2.445-20.246,7.034-29.487c4.063-8.179,9.511-10.54,18.55-8.381		c9.225,2.203,17.404,6.669,24.597,12.735c13.758,11.605,28.898,20.657,46.083,26.208c3.116,1.007,6.084,2.471,9.17,3.588		C659.289,235.024,659.148,233.821,659.367,232.692z",70,true,"ug"],
			["M816.89,312.552c-2.777,2.192-5.72,2.051-8.519-0.62c-3.363-3.211-7.125-6.01-10.486-9.225		c-7.411-7.084-15.665-12.97-25.038-17.057c-7.107-3.101-13.914-7.311-21.96-7.721c-9.65-0.489-19.308-0.902-28.965-1.141		c-2.809-0.068-5.182-0.823-7.433-2.371c-5.212-3.581-10.776-6.73-15.606-10.766c-7.587-6.335-15.225-9.724-24.475-3.625		c-0.132,0.087-0.318,0.09-0.479,0.137c-4.146,1.211-7.503-0.354-8.307-4.521c-0.846-4.387-1.491-8.927-1.325-13.365		c0.259-6.916,0.501-7.6-4.931-9.586c-0.22,1.129-0.078,2.332,0.035,3.414c0.042,0.015,0.082,0.033,0.124,0.048		c1.886,0.676,2.503,1.693,2.168,3.721c-0.884,5.345,0.185,10.635,0.98,15.878c0.972,6.377,5.896,9.29,11.971,7.414		c2.54-0.785,5.173-1.414,7.545-2.563c3.103-1.507,5.553-0.838,8.218,1.028c7.225,5.061,14.711,9.752,21.813,14.973		c3.44,2.527,6.925,3.335,11.079,3.417c10.313,0.205,21.036-0.775,30.829,1.72c13.979,3.563,27.069,10.176,38.129,20.004		c5.224,4.642,10.538,9.179,15.758,13.825c2.236,1.992,4.847,2.803,7.334,1.235c3.659-2.307,7.54-4.343,11.149-6.736		c0-1.375,0-2.75,0-4.125C823.3,308.158,819.943,310.139,816.89,312.552z",70,true,"v5"],
			["M966.431,306.521c-1.104-0.156-2.115-1.521-3.017-2.467		c-4.711-4.941-9.93-9.525-13.908-15.005c-5.041-6.941-11.089-7.399-18.376-5.172c-4.182,1.28-8.439,2.38-11.754-2.063		c-0.503-0.674-1.757-1.139-2.645-1.113c-6.282,0.185-12.575-1.581-18.864,0.446c-3.847,1.238-7.924,0.938-11.905,0.456		c-7.125-0.867-13.895,0.282-20.501,3.023c-4.602,1.91-9.388,3.368-14,5.249c-5.804,2.367-12.686,3.281-16.648,8.519		c-2.358,3.116-5.264,5.438-8.313,7.548c0,1.375,0,2.75,0,4.125c3.018-2,5.846-4.249,8.191-7.121		c2.189-2.683,4.542-4.949,7.713-6.062c16.169-5.673,31.469-14.917,49.754-12.02c3.633,0.577,7.291-1.219,10.876-1.104		c6.198,0.198,13.003-2.01,18.22,3.62c1.178,1.273,2.879,1.444,4.653,0.937c2.717-0.778,5.479-1.397,8.229-2.058		c4.629-1.113,8.494-0.745,11.918,3.479c4.81,5.939,10.346,11.288,15.538,16.919c1.263,1.368,2.751,3.353,4.396,2.947		c4.722-1.16,7.009,2.543,10.408,4.167c8.521,4.068,12.295,12.241,14.771,19.795c0.794,2.423,1.616,4.842,2.443,7.26		c0.103-2.321,0.307-4.614,0.692-6.964C990.003,321.548,981.757,308.685,966.431,306.521z",70,true,"v6"],
			["M1107.292,431.145c-0.445,0.066-0.891,0.132-1.344,0.228		c-3.188,0.674-6.657-0.288-9.767-1.588c-10.738-4.486-21.212-9.531-31.479-15.022c-7.364-3.938-13.378-9.921-20.672-13.823		c-11.312-6.056-21.715-13.573-32.956-19.708c-1.479-0.809-2.661-1.604-3.224-3.479c-4.107-13.664-8.439-27.262-12.576-40.917		c-0.298-0.982-0.622-1.974-0.97-2.969c-0.386,2.35-0.59,4.643-0.692,6.964c3.52,10.297,7.164,20.591,9.489,31.235		c1.564,7.165,5.3,11.381,11.696,15.235c13.348,8.042,27.181,15.328,39.752,24.73c10.815,8.09,23.548,12.971,35.689,18.789		c4.749,2.274,9.95,4.523,14.827,3.579c0.815-0.158,1.565-0.232,2.278-0.258C1107.226,433.152,1107.238,432.138,1107.292,431.145z",70,true,"v7"],			
			["M1206.75,480.867c-5.438-6.076-10.65-12.367-16.334-18.203		c-4.553-4.677-8.543-4.839-14.48-1.631c-6.539,3.534-9.186,3.101-11.357-4.107c-2.601-8.618-8.693-11.609-16.423-13.037		c-5.756-1.063-10.995-3.118-16.318-5.704c-7.652-3.713-15.293-8.423-24.545-7.039c-0.055,0.993-0.066,2.007,0.053,2.996		c5.984-0.211,8.598,3.994,11.618,8.271c0.479,0.678,0.937,1.373,1.352,2.092c3.184,5.507,7.192,10.371,11.514,15.008		c0.694,0.744,1.28,1.874,2.34,1.151c1.146-0.782,0.918-2.063,0.07-3.063c-1.399-1.651-2.842-3.271-4.329-4.842		c-4.387-4.636-7.02-10.403-10.493-15.763c4.274,0.878,8.205,2.122,11.768,4.276c4.31,2.604,8.775,4.835,13.839,5.359		c8.334,0.863,14.509,3.708,17.512,12.719c2.735,8.203,7.335,9.171,14.631,4.555c3.974-2.514,7.324-2.438,10.848,0.697		c3.251,2.894,6.076,6.147,8.871,9.454c3.243,3.838,6.526,7.642,10.195,11.931C1207.713,483.525,1207.897,482.146,1206.75,480.867z",70,true,"v8"]			
			];
			depot = [
			[-23,155,20,"dpt1"],
			[249,140,70,"dpt2"]
			]
			
			/*for (var i = 0; i < parkings.length; i++) {
				UpperRange = 100;
				LowerRange = 60;
				parkings[i].push(Math.floor(Math.random() * (UpperRange - LowerRange + 1)) + LowerRange);
				
			}*/
			
			//Create SVG element
			svg = d3.select("#container")
						.append("svg")
						.attr("width", w)
						.attr("height", h)
						.style("position","absolute")
						.style("top","0")
						.style("left","-1");
						
						

			var v1 = svg.selectAll("path")
			.data(paths)
			.enter()
			.append("path")
			.attr("fill-rule","evenodd")
			.attr("clip-rule","evenodd")
			.attr("fill","#555")
			.attr("class",function(d,i) { 
					var status = d[1];
					var c = "viaduct group-"+d[3]+" ";
					/*if (status < 40) { c += "glow-red"; } else
					if (status < 60) { c += "glow-yellow"; } else
					{ c += "glow-green"; }*/
					return c;
					})
			.attr("transform",function(d,i){return ((!d[2]) ? "translate(1, -123)" : "")})
			.attr("d",function(d,i) {
				return d[0];
			})/*
			.style("filter", function(d,i) {
				
				var status = d[1];
				if (status < 40) { return "url(#drop-shadow)" } else //delayed
				if (status < 60) { /*return "url(#drop-shadow)"* } else //critical
				{ return ""; }
				
				
			})*/
			.attr('id',function(d,i) {
				return "path"+i;
			});
			
						
			//Draw big circles
			var bigcircles = svg.selectAll(".bigcircle")
			    .data(stations)
			    .enter()
			    .append("circle")
				.attr("class",function(d,i) { 
					

					var status = d[2];
					var c = "bigcircle station group-"+d[3]+" ";

					//if (status < 40) { c += "glow-red"; } else
					//if (status < 60) { c += "glow-yellow"; } else
					//{ c += "glow-green"; }
					if ((typeof d[3] != "undefined") && (d[3] == 1)) {
					c += " legend "; 
					if (status < 40) { c += "glow-red"; } else
					if (status < 60) { c += "glow-yellow"; } else
					{ c += "glow-green"; }
					}
					return c;
					})/*
				.style("filter", function(d,i) {
				
				var status = d[2];
				if (status < 40) { return "url(#drop-shadow)" } else //delayed
				if (status < 60) { /*return "url(#drop-shadow)"* } else //critical
				{ return ""; }
				
				
				});*/

			bigcircles.attr("cx", function(d, i) {
						return d[0] + "%";
					})
				   .attr("cy", function(d, i) {
						return d[1] + "%";
				   })
				   .attr("r", function(d) {
						return station_circle_size;
				   });		
				   
			//Draw small circles
			var smallcircles = svg.selectAll(".smallcircle")
			    .data(stations)
			    .enter()
			    .append("circle")
				.attr("class","smallcircle");

			smallcircles.attr("cx", function(d, i) {
						return d[0] + "%";
					})
				   .attr("cy", function(d, i) {
						return d[1] + "%";
				   })
				   .attr("r", function(d) {
						return station_circle_size-3;
				   });
				   
				   
				   
				   
				   
				   
			var bigparking = svg.selectAll(".bigparking")
			    .data(parkings)
			    .enter()
			    .append("rect")       // attach a rectangle
				.attr("x", function(d, i) {
						return d[0] + "%";
					})         // position the left of the rectangle
				.attr("y", function(d, i) {
						return d[1] + "%";
				   })          // position the top of the rectangle
				.attr("height", parking_box_size)    // set the height
				.attr("width", parking_box_size)     // set the width
				.attr("rx", 3)         // set the x corner curve radius
				.attr("ry", 3)        // set the y corner curve radius
				.attr("class", function(d,i) { 
					var status = d[2];
					var blur = d[3];
					var c = "bigparking";
					if ((typeof blur != "undefined") && (blur)) c += " blurred"
					//if (status < 40) { c += "glow-red"; } else
					//if (status < 60) { c += "glow-yellow"; } else
					//{ c += "glow-green"; }
					return c;
				})
				
				
			var smallparking = svg.selectAll(".smallparking")
			    .data(parkings)
			    .enter()
			    .append("rect")       // attach a rectangle
				.attr("x", function(d, i) {
						return d[0]+0.17 + "%";
					})         // position the left of the rectangle
				.attr("y", function(d, i) {
						return d[1]+0.27 + "%";
				   })          // position the top of the rectangle
				.attr("height", parking_box_size-4)    // set the height
				.attr("width", parking_box_size-4)     // set the width
				.attr("rx", 2)         // set the x corner curve radius
				.attr("ry", 2)        // set the y corner curve radius
				.attr("class", function(d,i) {  
					var blur = d[3];
					var c = "smallparking";
					if ((typeof blur != "undefined") && (blur)) c += " blurred"
					return c;
				});
				
				
				
			var parkingletter = svg.selectAll(".parkingletter")
			.data(parkings)
			.enter()
			.append("text")         // append text
			//.style("fill", "black")   // fill the text with the colour black
			.attr("x", function(d, i) {
						return d[0]+0.17 + "%";
					})           // set x position of left side of text
			.attr("y", function(d, i) {
						return d[1]+0.27 + "%";
				   })           // set y position of bottom of text
			.attr("dx", "2.7px")           // set offset y position
			.attr("dy", "9px")           // set offset y position
			.attr("text-anchor", "start")  // set anchor y justification 
			.attr("class", function(d,i) {  
					var blur = d[3];
					var c = "parkingletter";
					if ((typeof blur != "undefined") && (blur)) c += " blurred"
					return c;
			})
			.text("P");          // define the text to display
				   
			
			
			//var depotgroup1 = svg.append('g');
			//var depotgroup2 = svg.append('g');
				   
			for (i = 0; i < depot.length; i++) {
				
				var g = svg.append('g')
									.attr('transform', 'scale(0.3,0.3)');
				
				var depotgroup = g.append('svg')
									.attr('x', depot[i][0] + "%")
									.attr('y', depot[i][1] + "%")
									
				
				bigdepot = depotgroup.selectAll(".bigdepot")
			    .data([depot[i]])
			    .enter()
			    .append("path")
				.attr("class", function(d,i) { 
					var status = d[2];
					var c = "bigdepot group-"+d[3];
					//if (status < 40) { c += "glow-red"; } else
					//if (status < 60) { c += "glow-yellow"; } else
					//{ c += "glow-green"; }
					return c;
				})
				.attr("transform","matrix(0.738729,0,0,0.70030265,168.70049,76.349168)")
				.attr("d","m 602.84375,430.03125 c -1.27276,0.19714 -2.39161,0.94937 -3.03125,2.0625 l -35.625,61.71875 c -0.74472,1.29698 -0.7568,2.91268 0,4.21875 0.75709,1.30666 2.16467,2.12617 3.6875,2.125 l 71.25,0 c 1.52287,0.001 2.9304,-0.81842 3.6875,-2.125 0.75682,-1.30608 0.74471,-2.92178 0,-4.21875 l -35.625,-61.71875 c -0.87568,-1.52499 -2.61057,-2.33402 -4.34375,-2.0625 z")
				.attr("fill","#ccc")
				
				smalldepot = depotgroup.selectAll(".smalldepot")
			    .data([1])
			    .enter()
			    .append("path")
				.attr("transform","matrix(0.51302074,0,0,0.48223833,304.89738,179.69186)")
				.attr("d","m 602.84375,430.03125 c -1.27276,0.19714 -2.39161,0.94937 -3.03125,2.0625 l -35.625,61.71875 c -0.74472,1.29698 -0.7568,2.91268 0,4.21875 0.75709,1.30666 2.16467,2.12617 3.6875,2.125 l 71.25,0 c 1.52287,0.001 2.9304,-0.81842 3.6875,-2.125 0.75682,-1.30608 0.74471,-2.92178 0,-4.21875 l -35.625,-61.71875 c -0.87568,-1.52499 -2.61057,-2.33402 -4.34375,-2.0625 z")
				.attr("fill","#ccc")
				
				dletter = depotgroup.selectAll(".dletter")
				.data([1])
				.enter()
				.append('text')
				.attr("class","parkingletter")
				.attr("x","575.36194")
				.attr("y","439.01147")
				.attr("transform","scale(1.0510781,0.95140407)")
				.attr("style",'font-size:29.947042px;font-style:normal;font-weight:bold;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Century Gothic')
				.text("D")
			}
			
			
				
				
			
			//Filter settings
			var defs = svg.append("defs");
			// create filter with id #drop-shadow
			// height=130% so that the shadow is not clipped
			var filter = defs.append("filter")
				.attr("id", "drop-shadow")
				.attr("height", "200%")
				.attr("width", "200%")
				.attr("y","-40%")
				.attr("x","-40%");
				
			// SourceAlpha refers to opacity of graphic that this filter will be applied to
			// convolve that with a Gaussian with standard deviation 3 and store result
			// in blur
			filter.append("feGaussianBlur")
				.attr("in", "SourceGraphic")
				.attr("stdDeviation", 5)
				.attr("result", "blur");

			// translate output of Gaussian blur to the right and downwards with 2px
			// store result in offsetBlur
			filter.append("feOffset")
				.attr("in", "blur")
				.attr("dx", 0)
				.attr("dy", 0)
				.attr("result", "offsetBlur");
			


			// overlay original SourceGraphic over translated blurred opacity by using
			// feMerge filter. Order of specifying inputs is important!
			var feMerge = filter.append("feMerge");

			feMerge.append("feMergeNode")
				.attr("in", "offsetBlur")
			feMerge.append("feMergeNode")
				.attr("in", "SourceGraphic");
				   
				   
				   
				   
			$('#onschedule').on('click', function(){
				focus(0);
			});	   
			$('#critical').on('click', function(){
				focus(1);
			});	   
			$('#delayed').on('click', function(){
				focus(2);
			});
			//Turn on everything
			turnonall();
			
			
			
			
			/*var v1 = parseFloat(data['V1']);
			var v2 = parseFloat(data['V2']);
			var v3 = parseFloat(data['V3']);
			var v4 = parseFloat(data['V4']);
			var v5 = parseFloat(data['V5']);
			var v6 = parseFloat(data['V6']);
			var v7 = parseFloat(data['V7']);
			var v8 = parseFloat(data['V8']);*/
			
			for (var i = 1; i < 9; i++){
				var d = parseFloat(data['V'+i]);
				processVariance('v'+i, d);
			}
			processVariance('dpt1', parseFloat(data['DPT1']));
			processVariance('dpt2', parseFloat(data['DPT2']));
			processVariance('sbk-s-01', parseFloat(data['SBK-S-01']));
			processVariance('sbk-s-02', parseFloat(data['SBK-S-02']));
			processVariance('sbk-s-03', parseFloat(data['SBK-S-03']));
			processVariance('sbk-s-04', parseFloat(data['SBK-S-04']));
//            processVariance('sbk-s-05', parseFloat(data['SBK-S-05']));
			processVariance('sbk-s-05', parseFloat(data['SBK-S-05']));
			processVariance('sbk-s-06', parseFloat(data['SBK-S-06']));
			processVariance('sbk-s-07', parseFloat(data['SBK-S-07']));
			processVariance('sbk-s-08', parseFloat(data['SBK-S-08']));
			processVariance('sbk-s-09', parseFloat(data['SBK-S-09']));
			processVariance('sbk-s-10', parseFloat(data['SBK-S-10']));
			processVariance('sbk-s-11', parseFloat(data['SBK-S-11']));
			processVariance('sbk-s-12', parseFloat(data['SBK-S-12']));
			processVariance('sbk-s-13', parseFloat(data['SBK-S-13']));
			processVariance('sbk-s-14', parseFloat(data['SBK-S-14']));
			groupGoGreen('ug');
			
			
			
	/*		
			$("#awarded").highcharts({
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: 0,
                            plotShadow: false,
                            type: 'column',
                            events: {
                                load: function(event) {
                                    $('.highcharts-legend-item').last().append('<br/><div style="width:100px;"><hr/><span style="float:left; color: rgb(117,165,27);">--------</span><span style="float:right; ">Ontime</span></div>\n\
                                        <br/><div style="width:100px;"><hr/><span style="float:left; color: rgb(246,145,41);">--------</span><span style="float:right; ">Middle</span></div>\n\
                                        <br/><div style="width:100px;"><hr/><span style="float:left; color: rgb(198,54,77);">--------</span><span style="float:right;">Alert</span></div>');
                                }
                            },
							backgroundColor:'rgba(255, 255, 255, 0.1)'
                        },
                        title: {
                            text: 'Awarded',
                            align: 'left',
                            style: {
                                color: '#DCF8FD'
                            }
                        },
                        xAxis: {
                            categories: ['Advanced Works', 'Underground', 'Elevated Guideways', 'Elevated Stations', 'Systems', 'Multi Storey Carparks', 'Depots', 'Others']
                        },
                        yAxis: {
                            min: 0,
                            max: 100,
                            allowDecimals: false,
                            title: {
                                text: ''
                            },
                            gridLineColor: 'rgba(0,0,0,.0)'
                        },
                        credits: {
                            enabled: false
                        },
                        legend: {
                            enabled: false
//                            layout: 'vertical',
//                            align: 'right',
//                            width: 170,
//                            verticalAlign: 'middle',
//                            floating: true,
//                            borderWidth: 1,
//                            useHTML: true,
//                            backgroundColor: '#133959',
//                            //fontColor: '#000',
//                            shadow: true,
//                            y: -30
                        },
                        title: {
                            pointFormat: 'Percentage: <b>{point.y:.0f}%</b>'
                        },
                        plotOptions: {
                            series: {
                                point: {
                                    events: {
                                        click: function() {
                                            /*var test = $('#report');
                                            var test1 = $('#report1');
                                            test.show();
                                            test1.hide();
                                        }
                                    }
                                },
                                stacking: 'normal',
                                dataLabels: {enabled: true, align: "top", format: '{y} %', style: {
                                        fontWeight: 'bold',
                                        color: 'white'
                                                //textShadow: '0px 1px 2px black'
                                    }}
                            },
                            bar: {
                                borderColor: false,
                                allowPointSelect: true,
                                cursor: 'pointer'
                            }
                        },
                        series: [{
                                //showInLegend: false,
                                name: 'Awarded',
                                data: [{y: 100, color: '#5C8C02'}, {y: 65, color: '#5C8C02'}, {y: 60, color: '#5C8C02'}, {y: 50, color: '#5C8C02'}, {y: 20, color: 'rgb(198, 54, 77)'}, {y: 40, color: '#F69129'}, {y: 40, color: '#F69129'}, {y: 10, color: 'rgb(198, 54, 77)'}]

                            }]
                    });*/
			
			
			//doPoll();
			
				   
});

/*
function doPoll(){
				$.post('./json.php?get&random='+ Math.random(), function(data) {
					//console.log(data);  //
					setTimeout(doPoll,5000);
					if (data.color == "0") colorit(0);
					else if (data.color == "1") colorit(1);
					else if (data.color == "2") colorit(2);
				});
			}
*/


		</script>
		<!-- <script src="./lib/cssParser.js"></script>
		<script src="./lib/css-filters-polyfill.js"></script> -->
	</body>
</html>