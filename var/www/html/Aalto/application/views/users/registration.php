<!DOCTYPE html>

<html lang="en">  

<head>

<title>Register | Aalto University</title>
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/images/logo.png"/>
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />

<link href="<?php echo base_url(); ?>assets/css/login_form.css" rel='stylesheet' type='text/css' />

</head>

<body>

 <div class="container">

        <div class="row">

            <div class="col-md-4 col-md-offset-8">

                <div class="panel-login">

                    <div class="panel-heading">

                        <div class="row">

                            <img src="<?php echo base_url(); ?>assets/images/logo.png" alt="">

                        </div>

                    </div>

                    <div class="panel-body">

                        <div class="row">

                            <div class="col-lg-12">



            <?php

                if(!empty($success_msg)){

                    echo '<p class="statusMsg" style="margin: 0px 25px 10px; color: blue;">'.$success_msg.'</p>';

                }elseif(!empty($error_msg)){

                    echo '<p class="statusMsg" style="margin: 0px 25px 10px; color: red;">'.$error_msg.'</p>';

                }

            ?>



    <form action="" method="post" class="form-horizontal">

        <div class="form-group">

            <input type="text" class="form-control" name="username" placeholder="Username" required="" >

          <?php echo form_error('username','<span class="help-block statusMsg" style="color: red;">','</span>'); ?>

        </div>

        <div class="form-group">

            <input type="email" class="form-control" name="email" placeholder="Email" required="" >

          <?php echo form_error('email','<span class="help-block statusMsg" style="color: red;">','</span>'); ?>

        </div>

        <div class="form-group">

          <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}$" title="Password contain minimum 8 characters, including uppercase, lowercase, number and special character" class="form-control" name="password" placeholder="Password" required="">

          <?php echo form_error('password','<span class="help-block statusMsg" style="color: red;">','</span>'); ?>

        </div>

        <div class="form-group">

          <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}$" title="Password contain minimum 8 characters, including uppercase, lowercase, numbers and special character" class="form-control" name="conf_password" placeholder="Confirm password" required="">

          <?php echo form_error('conf_password','<span class="help-block statusMsg" style="color: red;">','</span>'); ?>

        </div>

        <div class="form-group">

          <input type="submit" name="regisSubmit" class="form-control btn btn-login" value="Register">

        </div>

    </form>

    <p class="footInfo">&nbsp&nbsp&nbsp&nbsp&nbsp Already have an account ? <a href="<?php echo base_url(); ?>login"><b>Login here</b></a></p>              

  </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.min.js" ></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" ></script>

</body>

</html>