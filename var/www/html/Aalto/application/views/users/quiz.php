<!DOCTYPE html>
<html lang="en">
<title>Quizzes | Aalto University</title>
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
                <h1 class="page-title">Quizzes</h1><hr/>

        <div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">
            <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>
        </div>
        <div style="width:100%;background-color: #ffb2b2;margin-bottom: 18px;" class="hide-it">
            <?php if((!empty($this->session->flashdata('error_msg')))) {echo $this->session->flashdata('error_msg');} ?>
        </div>

            <button class="btn btn-primary add_button" data-toggle="modal" data-target="#modalForm" data-name="" data-id="0">Add New Quiz</button>
                <br/><br/>
                    <div id="modalForm" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel">New Quiz</h4>
                                </div>

                                <form action="" method="post" >
                                    <div class="modal-body" id="modal-content">
                                        <div class="form-body form-horizontal">
                                            <input class="form-control business_skill_id" type="hidden" name="id">
                                            <input class="form-control business_skill_name" type="hidden" name="db_quiz">

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Quiz Name :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control business_skill_name" name="quiz" placeholder="Quiz Name" required="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Description :</label>
                                                <div class="col-md-8">
                                                    <!-- <input type="text" class="form-control business_skill_desc" name="desc" placeholder="Description" required="" /> -->
                                                    <textarea style="resize:vertical" class="form-control business_skill_desc" name="desc" placeholder="Description" required ></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Time :</label>
                                                <div class="col-md-2">
                                                    <input type="number" value="0" class="form-control business_skill_hour" name="hour" min="0" max="23" onkeydown="return false" /> Hours
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" value="0" class="form-control business_skill_min" name="minutes" min="0" max="59" onkeydown="return false" /> Minutes
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <input type="submit" name="addQuizSubmit" class="btn btn-primary" value="Save Changes">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                        <thead>
                            <tr>
                                <th style="display: none"></th>
                                <th>QuizId</th>
                                <th>Quiz Name</th>
                                <th>Questions</th>
                                <th>Marks</th>
                                <th>Assigned Users</th>
                                <th>Attempted User</th>
                                <th>Created Date</th>
                                <th>Action</th>
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
                                <td><a href="<?php echo base_url() ?>quiz/all_question/<?php echo $result_data->QuizId ?>" ><span class="glyphicon glyphicon-eye-open" title="view questions"></span></a> | <a href="<?php echo base_url() ?>quiz/assign_quiz/<?php echo $result_data->QuizId ?>" ><span class="glyphicon glyphicon-check" title="assign quiz"></span></a> | <a class="edit_button" data-toggle="modal" data-target="#modalForm" data-time="<?php echo $result_data->Time;?>" data-desc="<?php echo $result_data->Description;?>" data-name="<?php echo $result_data->Title;?>" data-id="<?php echo $result_data->QuizId; ?>"><span class="glyphicon glyphicon-pencil" title="edit"></a> | <a href="<?php echo base_url(); ?>quiz/delete_quiz/<?php echo $result_data->QuizId ?>" onclick="isconfirm();"><span class="glyphicon glyphicon-trash" title="delete"></a></td>
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

<script>
function isconfirm(){
    if(!confirm('Are you sure want to delete this quiz ?')){
    event.preventDefault();
        return;
    }
    return true;
}

$(document).on( "click", '.add_button',function(e) {
    $(".business_skill_name").val('');
     $(".business_skill_desc").val('');
    $(".business_skill_hour").val(0);
    $(".business_skill_min").val(0);
});

$(document).on( "click", '.edit_button',function(e) {
    var id = $(this).data('id');
    var name = $(this).data('name');
    var desc = $(this).data('desc');
    var time = $(this).data('time');
    var strArray = time.split(":");

    $(".business_skill_id").val(id);
    $(".business_skill_name").val(name);
    $(".business_skill_desc").val(desc);
    $(".business_skill_hour").val(strArray[0]);
    $(".business_skill_min").val(strArray[1]);
});
</script>