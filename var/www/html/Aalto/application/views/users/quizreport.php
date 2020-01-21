<!DOCTYPE html>
<html lang="en">
<title>Quiz Report | Aalto University</title>
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
        <div class="clearfix"></div>
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
                <h1 class="page-title">Quiz Report</h1><hr/>

                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                        <thead>
                            <tr>
                                <th style="display: none"></th>
                                <th>QuizId</th>
                                <th>Quiz Name</th>
                                <th>Questions</th>
                                <th>Marks</th>
                                <th>Assigned User</th>
                                <th>Attempted User</th>
                                <th>Created Date</th>
                                <th>View Attempted User</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $x = 0; foreach($fetch_data as $result_data) { 
                            if(!empty($result_data->assigned_users)) {
                                $userArray = explode(',', $result_data->assigned_users);
                                $userCount = count($userArray);
                            } else {
                                $userCount = 0;
                            }

                            if(!empty($result_data->questions)) {
                                $queArray = explode(',', $result_data->questions);
                                $queCount = count($queArray);
                            } else {
                                $queCount = 0;
                            }
                        ?>
                            <tr>
                                <td style="display: none"></td>
                                <td><?php echo $result_data->QuizUniqueId; ?></td>
                                <td><?php echo $result_data->Title; ?></td>
                                <td><?php echo $queCount; ?></td>
                                <td><?php echo $marks_sum[$x][0]->Marks; ?></td>
                                <td><?php echo $userCount; ?></td>
                                <td><?php echo count($count_attempt[$x]); $x++; ?></td>
                                <td><?php echo $result_data->created_at; ?></td>
                                <td><a href="<?php echo base_url() ?>quizreport/atmpt_user/<?php echo $result_data->QuizId ?>" ><span class="glyphicon glyphicon-eye-open" title="view attempted users"></span></a></td>
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