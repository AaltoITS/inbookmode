<!DOCTYPE html>

<html lang="en">
    <title>Chapter | Aalto University</title>
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

#BimProjectList a{
    float: right;
    display: block;
    margin: 15px 0;
    font-size: 18px;
    padding-right: 30px;
}

#pageTitle{
    float: left;
    display: block;
    padding-left: 0;
}
</style>

                    <!-- BEGIN CONTENT BODY -->

                    <div class="page-content">

                    <div class="page-bar">
                    <div id="pageTitle" class="col-lg-7">
                         <?php if((isset($chapter_details['id'])) && (!empty($chapter_details['id']))) {?>

                        <h1 class="page-title">Edit Chapter</h1>
                        

                        <?php } else { ?>

                        <h1 class="page-title">Add New Chapter</h1>

                        <?php } ?>
                    </div>
                    <div id="BimProjectList" class="col-lg-4">
                        <a href="<?php echo base_url(); ?>bimsurferwebgl" target="_blank">View 3d model's list</a>
                    </div>
                    
                    <div class="clearfix"> </div>
                    <hr style="margin: 0 0 20px 0;" />
                    
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

                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title Here" value="<?php if((isset($chapter_details['title'])) && (!empty($chapter_details['title']))) {echo $chapter_details['title'];} ?>" required>
                            <input type="hidden" name="db_title" value="<?php if((isset($chapter_details['title'])) && (!empty($chapter_details['title']))) {echo $chapter_details['title'];} ?>" >
<a href="javascript:void(0);" OnClick="Title()"/>Insert Title</a>
                        </div>

                        <div class="field_wrapper">

                            <div class="form-group">

                                <label for="sub_title">Sub Title</label>

                            <?php if((isset($chapter_details['sub_title'])) && (!empty($chapter_details['sub_title']))) { foreach(explode('@#@', $chapter_details['sub_title']) as $value){ ?>

                                <div>

                                    <input type="text" class="form-control" id="chatinput0" name="sub_title[]" value="<?php echo $value; ?>">
                                    <a href="javascript:void(0);" style="color: #ff0000;" class="remove_button" title="Remove field">- Remove</a>

                                </div><br/>

                            <?php } } else { ?>

                                <input type="text" class="form-control" id="chatinput0" name="sub_title[]">

                            <?php } ?>

<?php if((isset($chapter_details['sub_title'])) && (!empty($chapter_details['sub_title']))) { ?>
                                <a href="javascript:void(0);" class="add_button" title="Add field">+ Add New</a>
<?php } else  { ?>
<a href="javascript:void(0);" OnClick="kk(0)">Insert Sub Title</a>&nbsp&nbsp&nbsp&nbsp<a href="javascript:void(0);" class="add_button" title="Add field">+ Add New</a>
<?php } ?>
                            </div>

                        </div>

                        <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#Chapter-Text">

                                        Chapter Text

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="Chapter-Text" class="collapse in">

                                    <div class="panel-body">

                                        <div class="form-group">

                                            <textarea id="elm1" class="printchatbox" name="pageBody" style="line-height: 20px">

                                                <?php if((isset($chapter_details['content'])) && (!empty($chapter_details['content']))) {echo $chapter_details['content'];} ?>

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

                                        <input type="submit" name="addchapterSubmit" class="btn btn-primary" value="Save" style="width: 125px;">

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

                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-target="#Instructions">
                                            Instruction for 3D model
                                        </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
                                    </h4>
                                </div>
                                <div id="Instructions" class="collapse in">
                                    <div class="panel-body">
                                        <div class="form-group">
                                        for 3d model you can insert link to thumbnail or text. type something in textarea or insert image which you want to link with 3D model, select that text or image and click on <img src="<?php echo base_url(); ?>assets/images/link.png"> icon  of editor, a Popup is appear, in 'General' tab type or paste your 3D model's url in 'Link url' section then  click on 'Advanced' tab and type 3D model's poid(Project Id) in 'Id' section after that click on 'Insert or Update' button.
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- added by avanish -->
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-target="#Instructions2">
                                            Instruction for 3D Model Element 
                                        </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
                                    </h4>
                                </div>
                                <div id="Instructions2" class="collapse in">
                                    <div class="panel-body">
                                        <div class="form-group">
                                        for 3d model element you can insert link to thumbnail or text. type something in textarea or insert image which you want to link with 3D model, select that text or image and click on <img src="<?php echo base_url(); ?>assets/images/link.png"> icon  of editor, a Popup is appear, in 'General' tab type or paste your 3D model element Guid (click on the above link <span style="color:blue;">view 3d model's list</span> for getting Guid of any 3d element) in 'Link url' section then click on 'Advanced' tab and type 3D model poid(Project Id) in 'Id' section after that click on 'Insert or Update' button.
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>

<input type="hidden" name="title_sr" id="title_sr" value="<?php if((isset($title_sr)) && (!empty($title_sr))) {echo $title_sr;} ?>" >
<input type="hidden" name="sub_title_sr" id="sub_title_sr" value="<?php if((isset($sub_title_sr)) && (!empty($sub_title_sr))) {echo $sub_title_sr;} ?>" >
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

        theme_advanced_resizing : true,

        theme_advanced_resize_horizontal : false,
        //Open Manager Options

        /*force_br_newlines : true,
        force_p_newlines : false,
        forced_root_block : '',*/

        file_browser_callback: "openmanager",

        //open_manager_upload_path: '../../../../uploads/',

        open_manager_upload_path: path,



        // Drop lists for link/image/media/template dialogs

        external_link_list_url : "lists/link_list.js",

        external_image_list_url : "lists/image_list.js",

        media_external_list_url : "lists/media_list.js",



    });

</script>

<script>

$(document).ready(function(){

    var addButton = $('.add_button'); //Add button selector

    var wrapper = $('.field_wrapper'); //Input field wrapper

    //var fieldHTML = '<div class="form-group"><input type="text" class="form-control" id="chatinput" name="sub_title[]" required><a href="javascript:void(0);" style="color: #ff0000;" class="remove_button" title="Remove field">- Remove</a></div>'; //New input field html 
var x = 1;
    $(addButton).click(function(){ //Once add button is clicked
       $(wrapper).append('<div class="form-group"><input type="text" class="form-control" id="chatinput'+x+'" name="sub_title[]" required><a href="javascript:void(0);" OnClick="kk('+x+')">Insert Sub Title</a>&nbsp&nbsp&nbsp&nbsp<a href="javascript:void(0);" style="color: #ff0000;" class="remove_button" title="Remove field">- Remove</a></div>'); // Add field html
x++;
    });

    $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked

        e.preventDefault();

        $(this).parent('div').remove(); //Remove field html

    });

});

</script>
<script type="text/javascript">

function kk(x) {
    //alert(x);
    var x = x;
    var title_sr = document.getElementById('title_sr').value;
    var sub_title_sr = document.getElementById('sub_title_sr').value;
    var input_val = document.getElementById('chatinput'+x).value;
    var body = $(tinymce.activeEditor.getBody());
    body.append(title_sr+'.'+sub_title_sr+' '+input_val);
    document.getElementById('sub_title_sr').value = parseInt(sub_title_sr) + 1;
}

function Title() {
    //alert(x);
    var title_sr = document.getElementById('title_sr').value;
    var input_val = document.getElementById('title').value;
    var body = $(tinymce.activeEditor.getBody());
    body.prepend(title_sr+'. '+input_val);
}
</script>
</body>