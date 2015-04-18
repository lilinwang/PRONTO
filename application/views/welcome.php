<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Radiology Protocols</title>
	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">	

</head>

<body>
    <div id="wrapper">
		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Radiology Protocols</a>								
            </div>
            <!-- /.navbar-header -->            
			<ul class="nav navbar-top-links navbar-right">
				<li id="sign-in-tab" ><a href="#">Sign in</a></li>
				<li id="sign-up-tab" ><a href="#">Sign up</a></li>		
			</ul>
		</nav>			
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default" id="sign-in-form-outer">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">						
                        <form role="form" id="sign-in-form" method="post" class="form-signin" action="login_web">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="User name" name="user_name" id="sign-in-user-name" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" id="sign-in-password" type="password" value="">
                                </div>
                                <div class="prompt"><?php echo $login_prompt;?></div>
								<input type="submit" class="btn btn-lg btn-success btn-block" value="sign in">
                            </fieldset>
                        </form>
                    </div>					                    
                </div>
				<div class="login-panel panel panel-default" id="sign-up-form-outer">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign Up</h3>
                    </div>
                    <div class="panel-body">						      	
                        <form role="form" id="sign-up-form" action="sign_up_web" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="User name" name="user_name" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
								<div class="form-group">
                                    <input class="form-control" placeholder="Password confirm" name="password_confirm" type="password" value="">
                                </div>
                                <div class="prompt"><?php echo $sign_up_prompt; ?></div>
								<input type="submit" class="btn btn-lg btn-success btn-block" value="sign up">
                            </fieldset>
                        </form>
                    </div>					                   
                </div>
            </div>			
        </div>
    </div>

<!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
	
    <script type="text/javascript">
        $(function() {
            $("#sign-in-tab").click(function(){
                $("#sign-in-form-outer").show();
                $("#sign-up-form-outer").hide();
				$("#upload-form-outer").hide();
            });
            $("#sign-up-tab").click(function(){
                $("#sign-in-form-outer").hide();
                $("#sign-up-form-outer").show();
				$("#upload-form-outer").hide();
            });
			var signup="<?php echo $sign_up_prompt;?>";
			if (signup!=""){
				$("#sign-up-tab").trigger("click");
			}else{
				$("#sign-in-tab").trigger("click");
			}
            
		});
	</script>

</body>

</html>
