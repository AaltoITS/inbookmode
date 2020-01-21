<!DOCTYPE html>

<html lang="en">
    <title>All Users | Aalto University</title>
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

                    <!-- BEGIN CONTENT BODY -->

                    <div class="page-content">

                    <div class="page-bar">

                        <h1 class="page-title">All Users</h1><hr/>

                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">

                            <thead>

                                <tr>

                                    <th style="display: none"></th>

                                    <!-- <th>Id</th> -->

                                    <th>Username</th>

                                    <th>Email</th>

                                    <th>Profile Pic</th>

                                    <th>Books</th>

                                    <th>Role</th>

                                    <th>Status</th>

                                    <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php $x = 0; foreach($fetch_data as $result_data) { if($result_data->role != 'Admin'){ ?>
                                
                                <tr>

                                    <td style="display: none"></td>

                                    <!-- <td><?php echo $result_data->id; ?></td> -->

                                    <td><?php echo $result_data->username; ?></td>

                                    <td><?php echo $result_data->email; ?></td>

                                    <td><img alt="" style="width:60px; height:70px;" src="<?php echo base_url(); ?>uploads/<?php echo $result_data->profile_pic; ?>"></td>

                                    <td><?php echo count($fetch_book_data[$x]); $x++; ?></td>

                                    <td><?php echo $result_data->role; ?></td>

                                    <td><?php echo $result_data->status; ?></td>

                                    <td><?php if($result_data->status != 'Active') { ?><a href="<?php echo base_url() ?>all_users/approve/<?php echo $result_data->id ?>" onclick="isconfirm_approve();"><span class="glyphicon glyphicon-ok" title="approve"></span></a>  <?php if($result_data->role != 'Reader') { ?> | <?php } } if($result_data->status != 'Inactive') { ?> <a href="<?php echo base_url(); ?>all_users/reject/<?php echo $result_data->id ?>" onclick="isconfirm_reject();"><span class="glyphicon glyphicon-remove" title="reject"></span></a><?php if($result_data->role != 'Reader') { ?> | <?php } } if($result_data->role != 'Reader') { ?> <a href="<?php echo base_url() ?>all_users/login/<?php echo $result_data->id ?>" ><span class="glyphicon glyphicon-log-in" title="login as user"></span></a><?php } ?></td>

                                </tr>

                            <?php } } ?>

                            </tbody>

                        </table>

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

</body>

<script>

function isconfirm_approve(){



if(!confirm('Are you sure want to Activate this user ?')){

event.preventDefault();

return;

}

return true;

}

function isconfirm_reject(){



if(!confirm('Are you sure want to Deactivate this user ?')){

event.preventDefault();

return;

}

return true;

}
</script>