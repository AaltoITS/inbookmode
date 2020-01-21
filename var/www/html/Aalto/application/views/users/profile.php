<!DOCTYPE html>

<html lang="en">
    <title>My Profile | Aalto University</title>
    <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/images/logo.png"/>
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

        <div class="page-wrapper">

            <!-- BEGIN HEADER -->

            <div class="page-header navbar navbar-fixed-top">

                <!-- BEGIN HEADER INNER -->

                <div class="page-header-inner ">

                    <?php echo $common_details['topbar']; ?>

                </div>

                <!-- END HEADER INNER -->

            </div>

            <!-- END HEADER -->

            <!-- BEGIN HEADER & CONTENT DIVIDER -->

            <div class="clearfix"> </div>

            <!-- END HEADER & CONTENT DIVIDER -->

            <!-- BEGIN CONTAINER -->

            <div class="page-container">

                <!-- BEGIN SIDEBAR -->

                    <?php echo $common_details['sidebar']; ?>

                <!-- END SIDEBAR -->

                <!-- BEGIN CONTENT -->

                <div class="page-content-wrapper">

<style type="text/css">

    .form-horizontal .form-group {

    margin-left: 0px;

    margin-right: 0px;

}

</style>

                    <!-- BEGIN CONTENT BODY -->

                    <div class="page-content">

                    <div class="page-bar">

                    <h1 class="page-title">Edit Profile</h1><hr/>

<div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>

</div>

<div style="width:100%;background-color: #ffb2b2;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('error_msg')))) {echo $this->session->flashdata('error_msg');} ?>

</div>

                    <div class="col-lg-8" style="margin-left: -14px;">

                       <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#General-Info">

                                        General Information

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="General-Info" class="collapse in">

                                    <div class="panel-body">

                                    <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">

                                    <input type="hidden" name="book_id" value="<?php if((isset($user_data['0']->id)) && (!empty($user_data['0']->id))) {echo $user_data['0']->id;} ?>">

                                        <div class="form-group">

                                            <label for="username">Username</label>
                                            <input type="hidden" name="db_username" value="<?php if((isset($user_data['0']->username)) && (!empty($user_data['0']->username))) {echo $user_data['0']->username;} ?>">
                                            
                                            <input type="text" class="form-control" name="username" value="<?php if((isset($user_data['0']->username)) && (!empty($user_data['0']->username))) {echo $user_data['0']->username;} ?>" required>

                                            <span style="color: red;"><?php if((isset($username_error)) && (!empty($username_error))) {echo $username_error;} ?></span>

                                        </div>

                                        <div class="form-group">

                                            <label for="email">Email</label>

                                            <input type="hidden" name="db_email" value="<?php if((isset($user_data['0']->email)) && (!empty($user_data['0']->email))) {echo $user_data['0']->email;} ?>">

                                            <input type="email" class="form-control" name="email" value="<?php if((isset($user_data['0']->email)) && (!empty($user_data['0']->email))) {echo $user_data['0']->email;} ?>" required>

                                            <span style="color: red;"><?php if((isset($email_error)) && (!empty($email_error))) {echo $email_error;} ?></span>

                                        </div>

                                        <div class="form-group">

                                            <label for="password">Password</label>

                                            <input type="hidden" name="db_password" value="<?php if((isset($user_data['0']->password)) && (!empty($user_data['0']->password))) {echo $user_data['0']->password;} ?>">

                                            <input type="hidden" name="db_visible_password" value="<?php if((isset($user_data['0']->visible_password)) && (!empty($user_data['0']->visible_password))) {echo $user_data['0']->visible_password;} ?>">

                                            <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}$" title="Password contain minimum 8 characters, including uppercase, lowercase, numbers and special character" class="form-control" name="password" >

                                            <span style="color: red;"><?php if((isset($password_error)) && (!empty($password_error))) {echo $password_error;} ?></span>

                                        </div>

                                         <div class="form-group">

                                            <label for="password">Confirm Password</label>

                                            <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}$" title="Password contain minimum 8 characters, including uppercase, lowercase, numbers and special character" class="form-control" name="conf_password" >

                                        </div>

                                        <!-- <button type="submit" class="btn btn-default">Submit</button> -->

                                    </div>

                                </div>

                            </div>

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#Profile-Pic">

                                        Profile Picture

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="Profile-Pic" class="collapse in">

                                    <div class="panel-body">

                                        <div class="form-group">

                                    <?php if((isset($user_data['0']->profile_pic)) && (!empty($user_data['0']->profile_pic))) { ?>

                                            <img alt="" style="width:60px; height:70px;" src="<?php echo base_url(); ?>uploads/<?php echo $user_data['0']->profile_pic; ?>">

                                    <?php } ?>

                                        </div>

                                        (upload png, jpg only. width 768px, height 1024px and size 1000 kb.)

                                        <div class="form-group">

                                            <input type="hidden" name="db_profile_pic" value="<?php if((isset($user_data['0']->profile_pic)) && (!empty($user_data['0']->profile_pic))) {echo $user_data['0']->profile_pic;} ?>">

                                            <input type="file" class="form-control" accept="image/png, image/jpeg" name="profile_pic">

                                        </div>

                                        <!-- <button type="submit" class="btn btn-default">Submit</button> -->

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4" style="margin-left: -14px;">

                       <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#submit_btn">

                                        Save Information

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="submit_btn" class="collapse in">

                                  <div class="panel-body" style="display: flex; align-items: center;justify-content: center;">

                                        <input type="submit" name="profileSubmit" class="btn btn-primary" value="Save" style="width: 125px;">

                                        <!-- <button type="submit" class="btn btn-default">Save</button> -->

                                        </form>

                                  </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    </div>

                    </div>

                    <!-- END CONTENT BODY -->

                </div>

                <!-- END CONTENT -->

            </div>

            <!-- END CONTAINER -->

            <!-- BEGIN FOOTER -->

                <?php echo $common_details['footer']; ?>

            <!-- END FOOTER -->

        </div>

<script type="text/javascript">

    $('#accordion .accordion-toggle').click(function (e){
        $(this).siblings("i.indicator").toggleClass("glyphicon-chevron-up glyphicon-chevron-down");
    });

</script>

</body>