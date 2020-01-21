<!DOCTYPE html>

<html lang="en">
    <title>Valid Domains | Aalto University</title>
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

                        <h1 class="page-title">Valid Domains</h1><hr/>

                        

<div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>

</div>

<div style="width:100%;background-color: #ffb2b2;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('error_msg')))) {echo $this->session->flashdata('error_msg');} ?>

</div>

                        <button class="btn btn-primary edit_button" data-toggle="modal" data-target="#modalForm" data-name=""

                    data-id="0">Add New Domain</button>

                        <br/><br/>

<div id="modalForm" class="modal fade" tabindex="-1" role="dialog">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                 <button type="button" class="close" data-dismiss="modal"                    

                 aria-hidden="true">&times;</button>

                <h4 class="modal-title" id="myModalLabel">New Domain</h4>

            </div>

            <form action="" method="post" >

                <div class="modal-body" id="modal-content">

                    <div class="form-body form-horizontal">

                        <input class="form-control business_skill_id" type="hidden" name="id">

                        <input class="form-control business_skill_name" type="hidden" name="db_domain">

                        <div class="form-group">

                            <label class="col-md-3 control-label">Domain Name :</label>

                            <div class="col-md-8">

                                <input type="text" pattern="[a-z]+" title="lowercase characters only and Space not allowed" class="form-control business_skill_name" name="domain" placeholder="Domain Name" required="" />

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <input type="submit" name="adddomainSubmit" class="btn btn-primary" value="Save Changes">

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

                                    <th>Domain</th>

                                    <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php foreach($fetch_data as $result_data) { ?>

                                <tr>

                                    <td style="display: none"></td>

                                    <!-- <td><?php echo $result_data->id; ?></td> -->

                                    <td><?php echo $result_data->domain; ?></td>

                                    

                                    <td><a class="edit_button" data-toggle="modal" data-target="#modalForm" data-name="<?php echo $result_data->domain;?>" data-id="<?php echo $result_data->id; ?>"><span class="glyphicon glyphicon-pencil" title="edit"></a> | <a href="<?php echo base_url(); ?>domains/delete_domains/<?php echo $result_data->id ?>" onclick="isconfirm();"><span class="glyphicon glyphicon-trash" title="delete"></a></td>

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



if(!confirm('Are you sure want to delete this domain ?')){

event.preventDefault();

return;

}

return true;

}



$(document).on( "click", '.edit_button',function(e) {

    var name = $(this).data('name');

    var id = $(this).data('id');



    $(".business_skill_id").val(id);

    $(".business_skill_name").val(name);



    $(".business_skill_id").val(id);

    $(".business_skill_name").val(name);

});

</script>