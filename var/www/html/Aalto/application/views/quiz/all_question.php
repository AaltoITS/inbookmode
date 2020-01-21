<!-- <input type="hidden" name="q_type" value="0" /> -->
<input type="hidden" id="user_id" value="<?php echo $this->session->userdata('userId') ?>">
<input type="hidden" name="db_question" value="<?php if((isset($question_data['Question'])) && (!empty($question_data['Question']))) { echo $question_data['Question'];} ?>">
<div class="form-group">
	<label class="col-md-3 control-label">Question :</label>
	<div class="col-md-8">
		<textarea style="resize:vertical" class="form-control" name="question" placeholder="Question" required ><?php if((isset($question_data['Question'])) && (!empty($question_data['Question']))) { echo $question_data['Question'];} ?></textarea>
		<!-- <textarea id="elm1" class="form-control printchatbox" name="question" placeholder="Question" style="line-height: 20px" required ><?php if((isset($question_data['Question'])) && (!empty($question_data['Question']))) { echo $question_data['Question'];} ?></textarea> -->
        <?php if($question_type == 5) { ?>
        Note :- put 4 underscore ( ____ ) for every fill in the blank.
        <?php } ?>
    </div>
</div>
<?php if(($question_type == 3) || ($question_type == 6)) { ?>
<div class="form-group">
    <label class="col-md-3 control-label">Image :</label>
    <div class="col-md-8">
    <?php if((isset($question_data['img'])) && (!empty($question_data['img']))) { ?>
        <img alt="" style="width:70px; height:80px; margin-bottom: 10px;" src="<?php echo base_url(); ?>QueImg/<?php echo $question_data['img']; ?>">
    <?php } ?>
        <input type="hidden" name="db_que_image" value="<?php if((isset($question_data['img'])) && (!empty($question_data['img']))) {echo $question_data['img'];} ?>">
        <input type="file" class="form-control" name="que_image" accept="image/png, image/jpeg, image/gif">
        (upload jpg, png, gif only. width 768px, height 1024px and size 1000 kb.)
    </div>
</div>
<?php } ?>
<div class="form-group">
    <label class="col-md-3 control-label">Marks :</label>
    <div class="col-md-8">
        <input type="number" class="form-control" name="marks" placeholder="Marks" min="1" max="1000" value="<?php if((isset($question_data['Marks'])) && (!empty($question_data['Marks']))) { echo $question_data['Marks'];} else { echo "1"; } ?>" required />
    </div>
</div>
<div class="form-group">
    <label class="col-md-3 control-label">Answer :</label>
    <div class="col-md-8">
        <textarea style="resize:vertical" class="form-control" name="answer" placeholder="Answer" required ><?php if((isset($question_data['Answer'])) && (!empty($question_data['Answer']))) { echo $question_data['Answer'];} ?></textarea>
        <!-- <input type="text" class="form-control" name="answer" placeholder="Answer" value="<?php if((isset($question_data['Answer'])) && (!empty($question_data['Answer']))) { echo $question_data['Answer'];} ?>" required /> -->
        <?php if($question_type == 5) { ?>
        Note :- put comma ( , ) after every fill in the blank's answer.
        <?php } ?>
    </div>
</div>

<!-- <script type="text/javascript" src="<?php echo base_url(); ?>/jscripts/tiny_mce/tiny_mce.js"></script>
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
</script> -->