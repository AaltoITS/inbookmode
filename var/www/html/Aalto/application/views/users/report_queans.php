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
                    <h1 class="page-title"><a href="<?php echo base_url().'quizreport/atmpt_user/'.$sel_quiz['0']->QuizId;?>">Quiz Report</a> / Questions & Answers / <?php echo $sel_quiz['0']->Title; ?> / <?php echo $sel_user['0']->username; ?></h1><hr/>

                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                        <thead>
                            <tr>
                                <th style="display: none"></th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Marks</th>
                                <th>Obtained Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($fetch_data as $result_data) {
                                $findme   = '**';
                                $pos = strpos($result_data->answer, $findme);
                                $ans = '';
                                if($pos === false) {
                                    $ans = $result_data->answer;
                                } else {
                                    $splittedstring = explode("**", $result_data->answer);
                                    $countAns = count($splittedstring) - 1;
                                    for($i=0; $i < $countAns; $i++) {
                                        if($i == ($countAns-1)) {
                                            $ans .= $splittedstring[$i];
                                        }
                                        else {
                                            $ans .= $splittedstring[$i].', ';
                                        }
                                    }
                                }
                        ?>
                            <tr>
                                <td style="display: none"></td>
                                <td><?php echo strip_tags($result_data->Question); ?></td>
                                <td><?php echo $ans; ?></td>
                                <td><?php echo strip_tags($result_data->Marks); ?></td>
                                <td title="Double click for giving obtained marks" ondblclick="this.contentEditable=true; this.className='inEdit';" onblur="this.contentEditable=false; this.className=''; saveData(event,'<?php echo $result_data->Id; ?>','<?php echo $result_data->Marks; ?>',$(this).html() )" ><?php echo $result_data->obtained_marks; ?></td>
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
function saveData(e,id,mark,obt_mark) {
    //console.log('mark***'+mark+'obt_mark***'+obt_mark);
    //if(e.keyCode === 13){
    if (obt_mark.match(/^-?\d+$/)) {
        var yourString = parseInt(obt_mark);
        var Quemark = parseInt(mark);
        if (confirm('Are you sure you want to save this marks into the database?')) {
        e.preventDefault();
            if(yourString < 0) {
                alert('obtained marks were not less than zero');
            } else {
                if(yourString > Quemark) {
                    alert('obtained marks were not more than marks');
                } else {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>quizreport/save_marks/",
                        data: { 'id': id,'obt_mark' :yourString },
                        success: function(response){ 
                            alert(response);
                        }
                    });
                }
            } 
        } 
    } else {
        alert("please insert number only");
    } 
    //}  
}
</script>