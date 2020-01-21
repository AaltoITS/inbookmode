<!DOCTYPE html>

<html lang="en">
    <title>3D Model | Aalto University</title>
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

                        <h1 class="page-title">3D Models</h1><hr/>

                        

<div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>

</div>

<div style="width:100%;background-color: #ffb2b2;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('error_msg')))) {echo $this->session->flashdata('error_msg');} ?>

</div>

                        <button class="btn btn-primary edit_button" data-toggle="modal" data-target="#modalForm" data-desc=""

                    data-id="0">Add New 3D Model</button>

                        <br/><br/>

                        <div id="modalForm" class="modal fade" tabindex="-1" role="dialog">

                            <div class="modal-dialog">

                                <div class="modal-content">

                                    <div class="modal-header">

                                         <button type="button" class="close" data-dismiss="modal"                    

                                         aria-hidden="true">&times;</button>

                                        <h4 class="modal-title" id="myModalLabel">New 3D Model</h4>

                                    </div>

                                    <form action="" method="post" id="uploadform" enctype="multipart/form-data" >

                                        <div class="modal-body" id="modal-content">

                                            <div class="form-body form-horizontal">

                                                <input class="form-control business_skill_id" type="hidden" name="id">

                                                <input class="form-control skill_name" type="hidden" name="db_title">

                                                <input class="form-control business_skill_link" type="hidden" name="db_3dmodel">

                                                <div class="form-group">

                                                    <label class="col-md-3 control-label">Project Name :</label>

                                                    <div class="col-md-8">

                                                        <input type="text" class="form-control skill_name" name="desc" placeholder="Project Name" required />
                                                    </div>

                                                </div>

                                                <div class="form-group">

                                                    <label class="col-md-3 control-label">3D Model :</label>

                                                    <div class="col-md-8">

                                                        <input type="file" class="form-control" name="3dmodel" accept=".ifc" required />
                                                        (upload ifc only.)

                                                    </div>

                                                </div>

                                                <!-- <div class="form-group">

                                                    <label class="col-md-3 control-label">poid :</label>

                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control skill_poid" name="poid" placeholder="poid" required />
                                                    </div>

                                                </div> -->

                                                <div class="form-group">

                                                    <label class="col-md-3 control-label">Dimension :</label>

                                                    <div class="col-md-8">
                                                        <select class="form-control skill_dm" name="dimension" required >
                                                            <option value="">Please Select Dimension</option>
                                                            <option value="m">Meter</option>
                                                            <option value="cm">Centimeter</option>
                                                            <option value="mm">Milimeter</option>
                                                        </select>
                                                        (please choose dimensions correctly or the model will not be viewable.)
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

                                    <th>Project Name</th>

                                    <th>URL</th>

                                    <th>Dimension</th>

                                    <th>poid(Project Id)</th>

                                    <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php foreach($fetch_data as $result_data) { ?>

                                <tr>

                                    <td style="display: none"></td>

                                    <!-- <td><?php echo $result_data->id; ?></td> -->

                                    <td style="width: 30%"><?php echo $result_data->description; ?></td>

                                    <td><?php echo base_url().'3DModel/'.$result_data->file; ?></td>
                                    <?php if($result_data->dimension == 'm') {
                                        $dimension = 'Meter';
                                    } else if ($result_data->dimension == 'cm') {
                                        $dimension = 'Centimeter';
                                    } else {
                                        $dimension = 'Milimeter';
                                    } ?>
                                    <td ><?php echo $dimension; ?></td>

                                    <td ><?php echo $result_data->poid; ?></td>

                                    <td><a href="<?php echo base_url(); ?>url_3DModel/delete_3DModel/<?php echo $result_data->id ?>" onclick="isconfirm();"><span class="glyphicon glyphicon-trash" title="delete"></a></td>

                                </tr>

                            <?php } ?>

                            </tbody>

                        </table>

                    </div>

                    </div><!-- END CONTENT BODY -->

                    
                    <!-- START Processing bar -->
                    <div id="WaitDialog" class="modal" data-backdrop="static" data-keyboard="false" style="margin-top: 100px;">
                        <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h3 id="append_heading"></h3>
                            <h5>please do not refresh the browser</h5>
                          </div>
                          <div class="modal-body">
                            <div class="progress progress-striped active">
                              <div class="progress-bar" style="width: 100%;"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END Processing bar -->

                </div><!-- END CONTENT -->

            </div>

            <!-- END CONTAINER -->

            <!-- BEGIN FOOTER -->

                <?php echo $common_details['footer']; ?>

            <!-- END FOOTER -->

        </div>

</body>

<script>

function isconfirm(){
    if(!confirm('Are you sure want to delete this 3D Model ?')){
        event.preventDefault();
        return;
    }
    return true;
}

/*function isconfirmConvert(){
    if(!confirm('Are you sure want to convert this 3D Model ?')){
        event.preventDefault();
        return;
    }
    $('#WaitDialog').modal('show');
    $("#append_heading").text('Converting...');
    return true;
}*/

$('#uploadform').submit(function() {
    $('#modalForm').modal('hide');
    $('#WaitDialog').modal('show');
    $("#append_heading").text('Uploading...');
});
</script>