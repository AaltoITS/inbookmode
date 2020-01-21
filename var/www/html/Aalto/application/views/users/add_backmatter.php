<!DOCTYPE html>

<html lang="en">
    <title>Backmatter | Aalto University</title>
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

    .mce-branding-powered-by{

        display: none;

}

</style>

                    <!-- BEGIN CONTENT BODY -->

                    <div class="page-content">

                    <div class="page-bar">

                    <?php if((isset($backmatt_details['id'])) && (!empty($backmatt_details['id']))) {?>

                    <h1 class="page-title">Edit Back Matter</h1><hr/>

                    <?php } else { ?>

                    <h1 class="page-title">Add New Back Matter</h1><hr/>

                    <?php } ?>

<div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>

</div>

<div style="width:100%;background-color: #ffb2b2;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('error_msg')))) {echo $this->session->flashdata('error_msg');} ?>

</div>

                    <input type="hidden" id="user_id" value="<?php echo $this->session->userdata('userId') ?>">

                    <div class="col-lg-7" style="margin-left: -14px;">

                    <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">

                        <div class="form-group">

                            <input type="text" class="form-control" name="title" placeholder="Enter Title Here" value="<?php if((isset($backmatt_details['title'])) && (!empty($backmatt_details['title']))) {echo $backmatt_details['title'];} ?>" required>
                            <input type="hidden" name="db_title" value="<?php if((isset($backmatt_details['title'])) && (!empty($backmatt_details['title']))) {echo $backmatt_details['title'];} ?>" >

                        </div>

                        <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#BM-Text">

                                        Back Matter Text

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="BM-Text" class="collapse in">

                                    <div class="panel-body">

                                        <div class="form-group">

                                            <textarea id="elm1" name="pageBody" style="line-height: 20px">

                                                <?php if((isset($backmatt_details['content'])) && (!empty($backmatt_details['content']))) {echo $backmatt_details['content'];} ?>

                                            </textarea>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4">

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

                                        <input type="submit" name="addbackmatterSubmit" class="btn btn-primary" value="Save" style="width: 125px;">

                                        <!-- <button type="submit" class="btn btn-default">Save</button> -->

                                        </form>

                                  </div>

                                </div>

                            </div>

                        </div>

                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-target="#Instructions">
                                            Instruction for page breaks
                                        </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
                                    </h4>
                                </div>
                                <div id="Instructions" class="collapse in">
                                    <div class="panel-body">
                                        <div class="form-group">
                                        for better view on windows app you have to put page break while authoring, click on <img src="<?php echo base_url(); ?>assets/images/preview.png"> icon, a Preview popup is displayed, in which you see 'Insert Page Break' in red color, return to the text editor and put page break where that text is appear by clicking <img src="<?php echo base_url(); ?>assets/images/page-break.png"> icon.
                                        </div>  
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

<script type="text/javascript" src="<?php echo base_url(); ?>/jscripts/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">

    var folder_path = '../../../../uploads/';

    var user_id = document.getElementById('user_id').value+'/';

    var path = folder_path.concat(user_id);

    

    tinyMCE.init({

        // General options

        mode : "textareas",

        theme : "advanced",

        height : "520",

        plugins : "openmanager,autolink,lists,style,layer,table,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,noneditable,xhtmlxtras,wordcount,advlist,anchor,pagebreak",

        // Theme options

        theme_advanced_buttons1 : "formatselect,fontselect,fontsizeselect,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull",

        theme_advanced_buttons2 : "bullist,numlistoutdent,|,indent,blockquoteundo,redo,|,link,unlink,anchor,code,|,insertdate,inserttime,preview,|,forecolor,backcolor,|,del,ins,|,sub,sup",

        theme_advanced_buttons3 : "hr,tablecontrols,|,charmap,iespell,|,openmanager,|,media,pagebreak",

        theme_advanced_toolbar_location : "top",

        theme_advanced_toolbar_align : "left",

        theme_advanced_statusbar_location : "bottom",

        theme_advanced_resizing : false,

        theme_advanced_resize_horizontal : false, 

        /*force_br_newlines : true,
        force_p_newlines : false,
        forced_root_block : '',*/

        //Open Manager Options

        file_browser_callback: "openmanager",

        //open_manager_upload_path: '../../../../uploads/',

        open_manager_upload_path: path,



        // Drop lists for link/image/media/template dialogs

        external_link_list_url : "lists/link_list.js",

        external_image_list_url : "lists/image_list.js",

        media_external_list_url : "lists/media_list.js",



    });

</script>

</body>