<!DOCTYPE html>

<html lang="en">
    <title>Reference | Aalto University</title>
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

                        <h1 class="page-title">References</h1><hr/>

                        

<div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>

</div>

<div style="width:100%;background-color: #ffb2b2;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('error_msg')))) {echo $this->session->flashdata('error_msg');} ?>

</div>

                        <button class="btn btn-primary edit_button" data-toggle="modal" data-target="#modalForm" data-title=""

                    data-id="0">Add New Reference</button>

                        <br/><br/>

<div id="modalForm" class="modal fade" tabindex="-1" role="dialog">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                 <button type="button" class="close" data-dismiss="modal"                    

                 aria-hidden="true">&times;</button>

                <h4 class="modal-title" id="myModalLabel">New Reference</h4>

            </div>

            <form action="" method="post" enctype="multipart/form-data" >

                <div class="modal-body" id="modal-content">

                    <div class="form-body form-horizontal">

                        <input class="form-control business_skill_id" type="hidden" name="id">

                        <input class="form-control skill_name" type="hidden" name="db_title">

                        <input class="form-control business_skill_thumbnail" type="hidden" name="db_thumbnail">

                        <div class="form-group">

                            <label class="col-md-3 control-label">Description :</label>

                            <div class="col-md-8">

                                <input type="text" class="form-control skill_name" name="title" placeholder="Description" required />
                                (maximum 100 characters.)
                            </div>

                        </div>

                        <div class="form-group">

                            <label class="col-md-3 control-label">Link :</label>

                            <div class="col-md-8">

                                <input type="text" class="form-control business_skill_link" name="link" placeholder="Link" required />

                            </div>

                        </div>

                        <div class="form-group">

                            <label class="col-md-3 control-label">Thumbnail :</label>

                            <div class="col-md-8">

                                <input type="file" class="form-control" name="thumbnail" accept="image/png, image/jpeg, image/gif">
                                (upload jpg, png, gif only. width 1024px, height 768px and size 1000 kb.)

                            </div>

                        </div>
                    
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <input type="submit" name="addrefSubmit" class="btn btn-primary" value="Save Changes">

                </div>

            </form>

        </div>

    </div>

</div>

                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">

                            <thead>

                                <tr>

                                    <th style="display: none"></th>

                                    <!-- <th>Id</th> -->

                                    <th>Description</th>

                                    <th>Link</th>

                                    <th>Thumbnail</th>

                                    <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php foreach($fetch_data as $result_data) { ?>

                                <tr>

                                    <td style="display: none"></td>

                                    <!-- <td><?php echo $result_data->id; ?></td> -->

                                    <td style="width: 30%"><?php echo $result_data->title; ?></td>

                                    <td><a href="<?php echo $result_data->link; ?>" target="_blank"><?php echo substr($result_data->link,0,50); ?></a></td>

                                    <td><a href="<?php echo $result_data->link; ?>" target="_blank"><img alt="" style="width:60px; height:60px;" src="<?php echo base_url(); ?>reference_images/<?php echo $result_data->thumbnail; ?>"></a></td>

                                    <td><a class="edit_button" data-toggle="modal" data-target="#modalForm" data-title="<?php echo $result_data->title;?>" data-id="<?php echo $result_data->id; ?>" data-link="<?php echo $result_data->link; ?>" data-thumb="<?php echo $result_data->thumbnail; ?>"><span class="glyphicon glyphicon-pencil" title="edit"></a> | <a href="<?php echo base_url(); ?>reference/delete_reference/<?php echo $result_data->id ?>" onclick="isconfirm();"><span class="glyphicon glyphicon-trash" title="delete"></a></td>

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



if(!confirm('Are you sure want to delete this reference ?')){

event.preventDefault();

return;

}

return true;

}



$(document).on( "click", '.edit_button',function(e) {

    var title = $(this).data('title');

    var id = $(this).data('id');

    var link = $(this).data('link');

    var thumb = $(this).data('thumb');

    $(".business_skill_id").val(id);

    $(".skill_name").val(title);



    $(".business_skill_id").val(id);

    $(".skill_name").val(title);

    $(".business_skill_link").val(link);
    
    $(".business_skill_thumbnail").val(thumb);
});

</script>