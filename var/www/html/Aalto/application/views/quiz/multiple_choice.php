<!-- <input type="hidden" name="q_type" value="1" /> -->
<input type="hidden" id="user_id" value="<?php echo $this->session->userdata('userId') ?>">
<input type="hidden" name="db_question" value="<?php if((isset($question_data['Question'])) && (!empty($question_data['Question']))) { echo $question_data['Question'];} ?>">
<div class="form-group">
	<label class="col-md-3 control-label">Question :</label>
	<div class="col-md-8">
		<textarea style="resize:vertical" class="form-control" name="question" placeholder="Question" required ><?php if((isset($question_data['Question'])) && (!empty($question_data['Question']))) { echo $question_data['Question'];} ?></textarea>
        <!-- <textarea id="elm1" class="form-control printchatbox" name="question" placeholder="Question" style="line-height: 20px" required ><?php if((isset($question_data['Question'])) && (!empty($question_data['Question']))) { echo $question_data['Question'];} ?></textarea> -->
    </div>
</div>
<?php
	if((isset($question_data['options'])) && (!empty($question_data['options']))) {
		$asdf = explode('@#@', $question_data['options']);
	}
?>
<div class="form-group">
    <label class="col-md-3 control-label">Options :</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="option_1" placeholder="Option a" value="<?php if((isset($asdf['0'])) && (!empty($asdf['0']))) { echo $asdf['0'];} ?>" required />
    </div>

    <div class="col-md-4">
        <input type="text" class="form-control" name="option_2" placeholder="Option b" value="<?php if((isset($asdf['1'])) && (!empty($asdf['1']))) { echo $asdf['1'];} ?>" required />
    </div>
</div>

<div class="form-group">
	<div class="col-md-3">
    </div>

    <div class="col-md-4">
        <input type="text" class="form-control" name="option_3" placeholder="Option c" value="<?php if((isset($asdf['2'])) && (!empty($asdf['2']))) { echo $asdf['2'];} ?>" required />
    </div>

    <div class="col-md-4">
        <input type="text" class="form-control" name="option_4" placeholder="Option d" value="<?php if((isset($asdf['3'])) && (!empty($asdf['3']))) { echo $asdf['3'];} ?>" required />
    </div>
</div>
<div class="form-group">
    <label class="col-md-3 control-label">Marks :</label>
    <div class="col-md-8">
        <input type="number" class="form-control" name="marks" placeholder="Marks" min="1" max="1000" value="<?php if((isset($question_data['Marks'])) && (!empty($question_data['Marks']))) { echo $question_data['Marks'];} else { echo "1"; } ?>" required />
    </div>
</div>
<?php
    if((isset($question_data['Answer'])) && (!empty($question_data['Answer']))) {
        $ans = explode('@#@', $question_data['Answer']);
    }
?>
<div class="form-group">
	<label class="col-md-3 control-label">Answer :</label>

	<div class="col-md-8">
        <input type="checkbox" name="answer[]" value="a" <?php if((isset($ans)) && (!empty($ans))) { if(in_array("a", $ans)) { echo "checked"; } }?> ><label>Option a</label>
        <input type="checkbox" name="answer[]" value="b" <?php if((isset($ans)) && (!empty($ans))) { if(in_array("b", $ans)) { echo "checked"; } }?>><label>Option b</label>
        <input type="checkbox" name="answer[]" value="c" <?php if((isset($ans)) && (!empty($ans))) { if(in_array("c", $ans)) { echo "checked"; } }?>><label>Option c</label>
        <input type="checkbox" name="answer[]" value="d" <?php if((isset($ans)) && (!empty($ans))) { if(in_array("d", $ans)) { echo "checked"; } }?>><label>Option d</label>
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