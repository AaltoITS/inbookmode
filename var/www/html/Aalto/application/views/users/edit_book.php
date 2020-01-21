<!DOCTYPE html>

<html lang="en">
    <title>Edit Book | Aalto University</title>
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

</style>

                    <!-- BEGIN CONTENT BODY -->

                    <div class="page-content">

                    <div class="page-bar">

                    <h1 class="page-title">Edit Book Information</h1><hr/>

<div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>

</div>

<div style="width:100%;background-color: #ffb2b2;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('error_msg')))) {echo $this->session->flashdata('error_msg');} ?>

</div>

                    <div class="col-lg-8" style="margin-left: -14px;">

                       <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#Book1">

                                        General Book Information

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="Book1" class="collapse in">

                                    <div class="panel-body">

                                    <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">

                                    <input type="hidden" name="book_id" value="<?php if((isset($book_details['id'])) && (!empty($book_details['id']))) {echo $book_details['id'];} ?>">
                                    <input type="hidden" name="db_title" value="<?php if((isset($book_details['title'])) && (!empty($book_details['title']))) {echo $book_details['title'];} ?>">

                                        <div class="form-group">

                                            <label for="title">Title</label>

                                            <input type="text" class="form-control" name="title" value="<?php if(!empty($this->session->flashdata('myData')['title'])) { echo $this->session->flashdata('myData')['title']; } else if((isset($book_details['title'])) && (!empty($book_details['title']))) {echo $book_details['title'];} ?>" required>

                                        </div>

                                        <div class="form-group">

                                            <label for="sub_title">Subtitle</label>

                                            <input type="text" class="form-control" name="sub_title" value="<?php if(!empty($this->session->flashdata('myData')['sub_title'])) { echo $this->session->flashdata('myData')['sub_title']; } else if((isset($book_details['sub_title'])) && (!empty($book_details['sub_title']))) {echo $book_details['sub_title'];} ?>">

                                        </div>

                                        <div class="form-group">

                                            <label for="author">Author</label>

                                            <input type="text" class="form-control" name="author" value="<?php if(!empty($this->session->flashdata('myData')['author'])) { echo $this->session->flashdata('myData')['author']; } else if((isset($book_details['author'])) && (!empty($book_details['author']))) {echo $book_details['author'];} ?>" required>

                                        </div>

                                        <div class="field_wrapper">

                                        <div class="form-group">

                                            <label for="c_author">Contributing Authors</label>

                                    <?php if((isset($book_details['c_author'])) && (!empty($book_details['c_author']))) { $first_array = 0; foreach(explode(',', $book_details['c_author']) as $value){ ?>

                                        <div>

                                            <input type="text" class="form-control" placeholder="Additional author" name="c_author[]" value="<?php echo $value; ?>">

                                            <?php if($first_array > '0') { ?>

                                            <a href="javascript:void(0);" style="color: #ff0000;" class="remove_button" title="Remove field">- Remove</a><?php } ?><br/>

                                        </div>

                                    <?php $first_array = $first_array + 1; ?>

                                    <?php } } else { ?>

                                            <input type="text" class="form-control" placeholder="Additional author" name="c_author[]">

                                    <?php } ?>

                                            <br/>

                                            <a href="javascript:void(0);" class="add_button" title="Add field">+ Add New</a>

                                        </div>

                                        </div>

                                        <div class="form-group">

                                            <label for="author">Would you like your book to be visible to the public ?</label><br/>

                                            <input type="radio" name="privacy_status" <?php if((!empty($book_details['privacy_status'])) && ($book_details['privacy_status'] =="Public")) {echo 'checked="checked"';} ?> value="Public">Yes 

                                            <input type="radio" name="privacy_status" <?php if((!empty($book_details['privacy_status'])) && ($book_details['privacy_status'] =="Private")) {echo 'checked="checked"';} ?> value="Private">No 

                                        </div>

                                        <div class="form-group">

                                            <label for="sub_title">Tags</label>

                                            <input type="text" class="form-control" placeholder="Enter Tags Here" name="tags" value="<?php if(!empty($this->session->flashdata('myData')['tags'])) { echo $this->session->flashdata('myData')['tags']; } else if((isset($book_details['tags'])) && (!empty($book_details['tags']))) {echo $book_details['tags'];} ?>">

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#Book2">

                                        Cover Image

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="Book2" class="collapse in">

                                    <div class="panel-body">

                                        <div class="form-group">

                                    <?php if((isset($book_details['cover_image'])) && (!empty($book_details['cover_image']))) { ?>

                                            <img alt="" style="width:60px; height:70px;" src="<?php echo base_url(); ?>uploads/<?php echo $book_details['cover_image']; ?>">

                                    <?php } else { ?>

                                            <img alt="" style="width:60px; height:70px;" src="<?php echo base_url(); ?>uploads/default-book.jpg">

                                    <?php } ?>

                                        </div>

                                        (upload jpg, png, gif only. width 768px, height 1024px and size 1000 kb.)

                                        <div class="form-group">

                                            <input type="hidden" name="db_cover_image" value="<?php if((isset($book_details['cover_image'])) && (!empty($book_details['cover_image']))) {echo $book_details['cover_image'];} ?>">

                                            <input type="file" class="form-control" name="cover_image" accept="image/png, image/jpeg, image/gif">

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4" style="margin-left: -14px;">

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

                                        <input type="submit" name="editbookSubmit" class="btn btn-primary" value="Save" style="width: 125px;">

                                        </form>

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

<script>

$(document).ready(function(){

    var maxField = 10; //Input fields increment limitation

    var addButton = $('.add_button'); //Add button selector

    var wrapper = $('.field_wrapper'); //Input field wrapper

    var fieldHTML = '<div class="form-group"><input type="text" class="form-control" placeholder="Additional author" name="c_author[]" required><a href="javascript:void(0);" style="color: #ff0000;" class="remove_button" title="Remove field">- Remove</a></div>'; //New input field html 

    var x = 1; //Initial field counter is 1

    $(addButton).click(function(){ //Once add button is clicked

        if(x < maxField){ //Check maximum number of input fields

            x++; //Increment field counter

            $(wrapper).append(fieldHTML); // Add field html

        }

        else

        {

            alert("Maximum 10 Fields");

        }

    });

    $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked

        e.preventDefault();

        $(this).parent('div').remove(); //Remove field html

        x--; //Decrement field counter

    });

});

</script>

</body>