<!DOCTYPE html>
<html lang="en">
    <head>

        <!-- start: Meta -->
        <meta charset="utf-8">
        <title>PPMS</title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="keyword" content="">
        <!-- end: Meta -->

        <!-- start: Mobile Specific -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- end: Mobile Specific -->

        <!-- start: CSS -->
        <link href="assets/temp/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/temp/assets/css/style.min.css" rel="stylesheet">
        <link href="assets/temp/assets/css/retina.min.css" rel="stylesheet">
        <link href="assets/temp/assets/css/print.css" rel="stylesheet" type="text/css" media="print"/>
        <!-- end: CSS -->


        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
                
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
                <script src="assets/temp/assets/js/respond.min.js"></script>
                
        <![endif]-->

        <!-- start: Favicon and Touch Icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/temp/assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/temp/assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/temp/assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="assets/temp/assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="./favicon.ico">
        <!-- end: Favicon and Touch Icons -->	
        <style>
            #content 
            {
              /*  background-image: url('assets/temp/assets/img/mrt2.png');*/
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div id="content" class="col-sm-12 full">
                    <div class="row">
                        <div class="login-box" style="box-shadow: 0.3em 0.3em 0 0 rgba(128,128,128,0.5);">
                           <img src="assets/temp/assets/img/hw2.png" height="100px" width="200px" style="margin-left: 80px; margin-top: -10px; align: center; padding-bottom: 43px; padding-top: -10px;">
                            <div class="header" style="text-align: right; font-size: 16px;">
                                <strong style="color: #339999;">LOGIN</strong>
                            </div>
                            <form id="form" class="form-horizontal login" method="post" action="system-admin-manage-object-contractor.html">
                                <fieldset class="col-sm-12">
                                    <div class="form-group">
                                        <div class="controls row">
                                            <div class="input-group col-sm-12">	
                                                <input type="text" class="form-control" id="username" name="username" placeholder="Username"/>
                                                <span class="input-group-addon"><i class="icon-user"></i></span>
                                            </div>	
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="controls row">
                                            <div class="input-group col-sm-12">	
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password"/>
                                                <span class="input-group-addon"><i class="icon-key"></i></span>
                                            </div>	
                                        </div>
                                    </div>

                                    <!--div class="confirm">
                                        <input type="checkbox" name="remember"/>
                                        <label for="remember">Remember me</label>
                                    </div-->	

                                    <div class="row">
                                        <button id="submitbutton" type="button" class="btn btn-lg btn-primary col-xs-12">Login</button>
										<div id="login_status"></div>
										<div style="display:none" id="login_success" class="alert alert-success"> <b>Login Successful!</b> Redirecting to the dashboard...</div>
										<div style="display:none" id="login_failed" class="alert alert-danger"> <b>Login Failed!</b> Please check your username/password</div>
										
										<div style="font-size: 10px; text-align: justify; color: #868686">Best viewed using Internet Explorer 10+, Chrome, Mozilla Firefox and modern browsers with resolution 1024x768+.</div>
                                    </div>
                                </fieldset>	
                            </form>
                            <div class="clearfix"></div>				
                        </div>	
                    </div><!--/row-->
                </div><!--/content-->
            </div><!--/row-->			
        </div><!--/container-->


        <!-- start: JavaScript-->
        <!--[if !IE]>-->

        <script src="assets/temp/assets/js/jquery-2.0.3.min.js"></script>

        <!--<![endif]-->

        <!--[if IE]>
        
                <script src="assets/temp/assets/js/jquery-1.10.2.min.js"></script>
        
        <![endif]-->

        <!--[if !IE]>-->

        <script type="text/javascript">
			window.jQuery || document.write("<script src='assets/temp/assets/js/jquery-2.0.3.min.js'>" + "<" + "/script>");

			$(function() {
				$("#submitbutton").on("click", function(){
					var username = $("#username").val();
					var password = $("#password").val();
					login(username,password);
				});
				
				$('#username').keypress(function (e) {
					var key = e.which;
						if(key == 13)  // the enter key code
						{
							$("#password").focus();
						}
				})
				$('#password').keypress(function (e) {
					var key = e.which;
						if(key == 13)  // the enter key code
						{
							$("#submitbutton").click();
						}
					});
            })
			
			var succesMsg = '<div class="alert alert-success"> <b>Login Success!</b> Redirecting to the dashboard...</div>';
			var failedMsg = '<div class="alert alert-danger"> <b>Login Failed!</b> Please check your username/password</div>';
			var login = function(username,password){
				$.ajax({
					url: "/ppms/login",
					type: "POST",
					data: { username: username, password: password },
					success: function(data) {
					//console.log(data);
						if(data === '1'){
                            $('#login_failed').fadeOut();
							$('#login_success').fadeIn();
							setTimeout("location.href = 'dashboard';",1500);
						} else {
                            $('#login_failed').fadeOut();
							$('#login_failed').fadeIn().delay();
                            // $('#login_failed').stop(true,true).fadeIn().delay(2000).fadeOut();
                        }
					}
				});
			}
        </script>

        <!--<![endif]-->

        <!--[if IE]>
        
                <script type="text/javascript">
                window.jQuery || document.write("<script src='assets/temp/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
                </script>
                
        <![endif]-->
        <script src="assets/temp/assets/js/jquery-migrate-1.2.1.min.js"></script>
        <script src="assets/temp/assets/js/bootstrap.min.js"></script>




        <!-- page scripts -->
        <script src="assets/temp/assets/js/jquery.icheck.min.js"></script>

        <!-- theme scripts -->
        <script src="assets/temp/assets/js/custom.min.js"></script>
        <script src="assets/temp/assets/js/core.min.js"></script>

        <!-- inline scripts related to this page -->
        <script src="assets/temp/assets/js/pages/login.js"></script>

        <!-- end: JavaScript-->

    </body>
</html>