<html>

<head>

        <meta charset="utf-8" />

        <!-- <title>Dashboard | Aalto University</title> -->

        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta content="width=device-width, initial-scale=1" name="viewport" />

        <meta content="" name="description" />

        <meta content="" name="author" />



        

        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />

        <link href="<?php echo base_url(); ?>assets/css/bootstrap-switch.min.css" rel='stylesheet' type='text/css' />

        <link href="<?php echo base_url(); ?>assets/css/components.min.css" rel='stylesheet' type='text/css' />

        <link href="<?php echo base_url(); ?>assets/css/plugins.min.css" rel='stylesheet' type='text/css' />

        <link href="<?php echo base_url(); ?>assets/css/layout.min.css" rel='stylesheet' type='text/css' />

        <link href="<?php echo base_url(); ?>assets/css/darkblue.min.css" rel='stylesheet' type='text/css' />

        <link href="<?php echo base_url(); ?>assets/css/font-awesome.min.css" rel='stylesheet' type='text/css' />

        <link href="<?php echo base_url(); ?>assets/datatables/datatables.min.css" rel='stylesheet' type='text/css' />

        <link href="<?php echo base_url(); ?>assets/datatables/plugins/bootstrap/datatables.bootstrap.css" rel='stylesheet' type='text/css' />

        <link href="<?php echo base_url(); ?>assets/css/custom.css" rel='stylesheet' type='text/css' />

</head>

    <!-- BEGIN LOGO -->

    <body>

        <div class="page-logo">

            <a href="<?php echo base_url(); ?>dashboard">

                <img src="<?php echo base_url(); ?>assets/images/logo.png" height="50" alt="logo" class="logo-default" />

            </a>



            <ul class="nav navbar-nav pull-left">

                    <!-- BEGIN USER LOGIN DROPDOWN -->

                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                    <?php if($user['role'] == 'Author') { ?>

                    <li class="dropdown">

                        <a href="<?php echo base_url(); ?>dashboard" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" style="background-color: #206EA2;color: #fff;">

                            <span class="username username-hide-on-mobile">My Catalog</span>

                                <i class="fa fa-angle-down"></i>

                        </a>

                        <ul class="dropdown-menu" style="max-height: 500px; overflow-y: auto;">

                            <li>

                                <a href="<?php echo base_url(); ?>create_newbook">

                                Create New Book </a>

                            </li>

                        <?php foreach($fetch_data as $result_data) { ?>    

                            <li>

                                <a href="<?php echo base_url(); ?>dashboard/index/<?php echo $result_data->id; ?>">

                                <?php echo $result_data->title; ?></a>

                            </li>

                        <?php } ?>    

                        </ul>

                    </li>

                    <?php } ?>

                    <!-- END USER LOGIN DROPDOWN -->

                </ul>

        </div>
<?php if($user['role'] == 'Author') { ?>
<ul class="navbar-brand" style="color: #fff;">

    <?php echo $this->session->userdata('Book_Title'); ?>

</ul>
<?php } ?>
    <!-- END LOGO -->

        <!-- BEGIN RESPONSIVE MENU TOGGLER -->

        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">

            <span></span>

        </a>



        <!-- END RESPONSIVE MENU TOGGLER -->

            <!-- BEGIN TOP NAVIGATION MENU -->

            <div class="top-menu">

                <ul class="nav navbar-nav pull-right">

                    <!-- BEGIN USER LOGIN DROPDOWN -->

                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                    <li class="dropdown dropdown-user">

                        <a href="<?php echo base_url(); ?>dashboard" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">

                            <img alt="" class="img-circle" src="<?php echo base_url(); ?>/uploads/<?php echo $user['profile_pic']; ?>" />

                                <span class="username username-hide-on-mobile"><?php echo $user['username']; ?></span>

                                <i class="fa fa-angle-down"></i>

                        </a>

                        <ul class="dropdown-menu dropdown-menu-default">

                            <li>

                                <a href="<?php echo base_url(); ?>profile">

                                    <span class="glyphicon glyphicon-user"></span> My Profile </a>

                            </li>

                            <li>

                                <a href="<?php echo base_url(); ?>logout">

                                    <span class="glyphicon glyphicon-log-out"></span> Log Out </a>

                            </li>

                        </ul>

                    </li>

                    <!-- END USER LOGIN DROPDOWN -->

                </ul>

            </div>

            <!-- END TOP NAVIGATION MENU -->

    </body>

</html>