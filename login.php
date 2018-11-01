<!-- Login Page-->
 <?php
 require_once('classes/main.php');
 $main = new main();
 // Sanitize get and post requests
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
// CSRF validation
   if( isset($_POST['submit']) && ($_POST['csrf'] == $_SESSION['csrf']))
     {
       if($main->register($_POST)){
         echo "<script type='text/javascript'>alert('User Registered');</script>";
       }
     }
   if( isset($_POST['login-submit'])  && ($_POST['csrf'] == $_SESSION['csrf']))
     {
       if ($main->authenticate($_POST)){
            $_SESSION['user'] = true;
            header('Location:index.php');
       }
     }
     else if(isset($_POST['login-submit'])){
       echo ("<script type='text/javascript'>alert('Invalid Attempt !!');</script>");
     }

 ?>
 
<script src="jq/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link href="css/login.css" rel="stylesheet">

<script>
    $(function() {

        $('#login-form-link').click(function(e) {
            $("#login-form").delay(100).fadeIn(100);
            $("#register-form").fadeOut(100);
            $('#register-form-link').removeClass('active');
            $(this).addClass('active');
            e.preventDefault();
        });
        $('#register-form-link').click(function(e) {
            $("#register-form").delay(100).fadeIn(100);
            $("#login-form").fadeOut(100);
            $('#login-form-link').removeClass('active');
            $(this).addClass('active');
            e.preventDefault();
        });

    });
</script>
<script>
    var validate = function() {
        if (document.getElementById('password').value ==
            document.getElementById('confirm-password').value) {
            document.getElementById('message').style.color = 'green';
            document.getElementById('message').innerHTML = 'Matching';
        } else {
            document.getElementById('message').style.color = 'red';
            document.getElementById('message').innerHTML = 'Passord should be same as above';
        }
    }
</script>

<div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="active" id="login-form-link">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="#" id="register-form-link">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="loginemail" id="loginemail" tabindex="1" class="form-control" placeholder="Email" value="" required>
									</div>
									<div class="form-group">
										<input type="password" name="loginpassword" id="loginpassword" tabindex="2" class="form-control" placeholder="Password" required>
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf'];?>">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In" required>
											</div>
										</div>
									</div>
								</form>
								<form id="register-form" action="" method="post" role="form" style="display: none;">
									<div class="form-group">
										<input type="text" name="firstname" id="firstname" tabindex="1" class="form-control" placeholder="First Name" value="" required>
									</div>
                  <div class="form-group">
                    <input type="text" name="lastname" id="lastname" tabindex="1" class="form-control" placeholder="Last Name" value="" required>
                  </div>
									<div class="form-group">
										<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="" required>
									</div>
                  <div class="form-group">
                    <textarea name="address" id="address" tabindex="1" class="form-control" rows="4" cols="50" placeholder="Address" value="" required></textarea>
                  </div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" onkeyup="validate()" required>
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf'];?>">
                  </div>
									<div class="form-group">
										<input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password" onkeyup="validate()" required>
                    <span id='message'></span>
                  </div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="submit" id="submit" tabindex="4" class="form-control btn btn-register" value="Register Now" required>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
