<!DOCTYPE html>

<html lang="en">
    <title>Activity Log | Aalto University</title>
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

                        <h1 class="page-title">Activity Log</h1><hr/>

                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">

                            <thead>

                                <tr>

                                    <th style="display: none"></th>

                                    <!-- <th>Id</th> -->

                                    <th>User</th>

                                    <th>IP Address</th>

                                    <th>Activity</th>

                                    <th>Log time</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php foreach($fetch_data as $result_data) { ?>

                                <tr>

                                    <td style="display: none"></td>

                                    <!-- <td><?php echo $result_data->id; ?></td> -->

                                    <td><?php echo $result_data->user_name; ?></td>

                                    <td><?php echo $result_data->ip_address; ?></td>

                                    <td><?php echo $result_data->activity; ?></td>

                                    <td><?php echo $result_data->created; ?></td>

                                </tr>

                            <?php } ?>

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