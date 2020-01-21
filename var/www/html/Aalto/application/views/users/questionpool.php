<?php
function getQuestionType($type) {
    switch ($type) {
    case 0:
        return "Answer in single line or paragraph";
        break;
    case 1:
        return "Multiple choice question";
        break;
    case 2:
        return "True or False";
        break;
    case 3:
        return "Image related question";
        break;
    case 4:
        return "Calculate the value type question";
        break;
    case 5:
        return "Fill in the blanks";
        break;
    case 6:
        return "Scan QR code for question";
        break;
    case 7:
        return "Scan QR code as answer";
        break;
    default:
        return "";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <title>Question Pool | Aalto University</title>
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
                    <h1 class="page-title">Question Pool / Questions & Answers</h1><hr/>

                    <div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">

                        <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>

                    </div>

                    <div style="width:100%;background-color: #ffb2b2;margin-bottom: 18px;" class="hide-it">

                        <?php if((!empty($this->session->flashdata('error_msg')))) {echo $this->session->flashdata('error_msg');} ?>

                    </div>

                    <button class="btn btn-primary add_button" data-toggle="modal" data-target="#modalForm" data-name="" data-id="0" >Add New</button>
                    <br/><br/>

                    <div id="modalForm" class="modal fade" tabindex="-1" role="dialog">
                        <!-- <div class="modal-dialog modal-lg"> -->
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel">New Question & Answer</h4>
                                </div>
                                <form action="" method="post" enctype="multipart/form-data" >
                                    <div class="modal-body" id="modal-content">
                                        <div class="form-body form-horizontal">
                                            <input class="form-control question_id" type="hidden" name="id">
                                             <div class="form-group">
                                                <label class="col-md-3 control-label">Question Type :</label>
                                                <div class="col-md-8">
                                                    <select class="form-control question_type" id="mySelect" name="question_type" onchange="jsFunction(this.value);" required >
                                                        <option value="">Please Select Question Type</option>
                                                        <option value="0">Answer in single line or paragraph</option>
                                                        <option value="1">Multiple choice question</option>
                                                        <option value="2">True or False</option>
                                                        <option value="3">Image related question</option>
                                                        <option value="4">Calculate the value type question</option>
                                                        <option value="5">Fill in the blanks</option>
                                                        <option value="6">Scan QR code for question</option>
                                                        <option value="7">Scan QR code as answer</option>
                                                    </select>
                                                    <input type="hidden" class="db_question_type" name="db_question_type" value="" />
                                                </div>
                                            </div>
                                            <div id="box"></div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <input type="submit" name="QueSubmit" class="btn btn-primary" value="Save Changes">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                        <thead>
                            <tr>
                                <th style="display: none"></th>
                                <th>Id</th>
                                <th>Question Type</th>
                                <th>Question</th>
                                <th>Marks</th>
                                <th>Author</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($fetch_allQue as $result_data) {
                            $questionType = getQuestionType($result_data->qType);
                        ?>
                            <tr>
                                <td style="display: none"></td>
                                <td><?php echo strip_tags($result_data->QAId); ?></td>
                                <td><?php echo $questionType; ?></td>
                                <td><?php echo strip_tags($result_data->Question); ?></td>
                                <td><?php echo strip_tags($result_data->Marks); ?></td>
                                <td><?php echo strip_tags($result_data->username); ?></td>
                                <td><?php if($result_data->AuthorId == $this->session->userdata('userId')) { ?>
                                    <a class="edit_button" data-toggle="modal" data-target="#modalForm" data-type="<?php echo $result_data->qType;?>" data-id="<?php echo $result_data->QAId; ?>"><span class="glyphicon glyphicon-pencil" title="edit"></a> | <a href="<?php echo base_url(); ?>questions/delete_question/<?php echo $result_data->QAId ?>" onclick="isconfirm();"><span class="glyphicon glyphicon-trash" title="delete"></a><?php } else { echo ""; } ?></td>
                                
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
function isconfirm() {
    if(!confirm('Are you sure want to delete this Question ?')) {
        event.preventDefault();
        return;
    }
    return true;
}

$(document).on( "click", '.add_button',function(e) {
    var id = $(this).data('id');
    var selquiz = $(this).data('selquiz');
    $(".question_id").val(id);
    $(".question_type").val('');
    $(".db_question_type").val('');
    document.getElementById("mySelect").disabled=false;
    $("#box").html('');
});

$(document).on( "click", '.edit_button',function(e) {
    var q_type = $(this).data('type');
    var quiz = $(this).data('quiz');
    var id = $(this).data('id');
    $(".question_id").val(id);
    $(".question_type").val(q_type);
    $(".db_question_type").val(q_type);
    document.getElementById("mySelect").disabled=true;

    $.ajax({
        type: 'POST',
        url: "<?php echo base_url(); ?>questions/get_view_ajax/",
        data: { type: q_type, questionId: id },
        success:function(msg) {
            $("#box").html(msg);
        }
    });

});

function jsFunction(q_type) {
    //alert(value);
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url(); ?>questions/get_view_ajax/",
        data: { type: q_type, questionId: 0 },
        success:function(msg) {
            $("#box").html(msg);
        }
    });
}
</script>
<script type="text/javascript">
    var table = $('#example').DataTable({
        language: {
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            infoEmpty: "No records found",
            infoFiltered: "(filtered1 from _MAX_ total records)",
            lengthMenu: "Show _MENU_",
            search: "Search:",
            zeroRecords: "No matching records found",
        },
        bStateSave: !0,
        lengthMenu: [
            [5, 15, 20, -1],
            [5, 15, 20, "All"]
        ],
        pageLength: 5,
        pagingType: "bootstrap_full_number",
        columnDefs: [{
            orderable: false,
            targets: [1]
        }]
    });
</script>