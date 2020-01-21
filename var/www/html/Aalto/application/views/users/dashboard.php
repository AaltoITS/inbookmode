<!DOCTYPE html>

<html lang="en">
    <title>Dashboard | Aalto University</title>
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

                    <h1 class="page-title">Dashboard</h1><hr/>

<div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>

</div>

                    <?php if($user['role'] == 'Admin') { ?>

                    <div class="row">

                    <style>

a {

   color: #fff;

   font-size: 16px;

}

a:hover {

    color: blue;

}

</style>

                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                                <div class="dashboard-stat dashboard-stat-v2 blue" >

                                    <div class="visual">

                                        <i class="fa fa-comments"></i>

                                    </div>

                                    <div class="details">

                                        <div class="number">

                                            <span data-counter="counterup" ><?php echo count($fetch_active_users) + count($fetch_active_rader); ?></span>

                                        </div>

                                        <div class="desc"> Active Users </div>

                                    </div>

                                    <div style="padding: 90px 0px 0px 0px;">

                                        <a href="<?php echo base_url(); ?>all_users">View Details</a>

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                                <div class="dashboard-stat dashboard-stat-v2 red" >

                                    <div class="visual">

                                        <i class="fa fa-bar-chart-o"></i>

                                    </div>

                                    <div class="details">

                                        <div class="number">

                                            <span data-counter="counterup" ><?php echo count($fetch_deactive_users) + count($fetch_deactive_reader); ?></span>

                                        </div>

                                        <div class="desc"> Inactive Users </div>

                                    </div>

                                    <div style="padding: 90px 0px 0px 0px;">

                                        <a href="<?php echo base_url(); ?>all_users">View Details</a>

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                                <div class="dashboard-stat dashboard-stat-v2 green" >

                                    <div class="visual">

                                        <i class="fa fa-shopping-cart"></i>

                                    </div>

                                    <div class="details">

                                        <div class="number">

                                            <span data-counter="counterup" ><?php echo count($fetch_books); ?></span>

                                        </div>

                                        <div class="desc"> Total Books </div>

                                    </div>

                                    <div style="padding: 90px 0px 0px 0px;">

                                        <a href="<?php echo base_url(); ?>catalog">View Details</a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                    <?php if($user['role'] == 'Author') { ?>

                    <?php if (!empty($this->session->userdata('Book_Title'))) {?>

                    <div class="col-lg-8" style="margin-left: -14px;">

                        <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#Book">

                                    Book title -  <?php echo $this->session->userdata('Book_Title'); ?>

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>



                                <div id="Book" class="collapse in">

                                    <div class="panel-body">

                                    <b>Front Matter</b><br/>

                                    <?php foreach($fetch_frontmatter as $result_frontmatter) { ?>

                                    <a href="<?php echo base_url() ?>frontmatter/edit_frontmatter/<?php echo $result_frontmatter->id; ?>"><?php echo $result_frontmatter->title; ?></a><br/>

                                    <?php } ?><br/>



                                    <b>Main Body</b><br/>

                                    <?php foreach($fetch_chapter as $result_chapter) { ?>

                                    <a href="<?php echo base_url() ?>chapter/edit_chapter/<?php echo $result_chapter->id; ?>"><?php echo $result_chapter->title; ?></a><br/>

                                    <?php } ?><br/>



                                    <b>Part</b><br/>

                                    <?php foreach($fetch_part as $result_part) { ?>

                                    <a href="<?php echo base_url() ?>part/edit_part/<?php echo $result_part->id ?>"><?php echo $result_part->title; ?></a><br/>

                                    <?php } ?><br/>



                                    <b>Back Matter</b><br/>

                                    <?php foreach($fetch_backmatter as $result_backmatter) { ?>

                                    <a href="<?php echo base_url() ?>backmatter/edit_backmatter/<?php echo $result_backmatter->id ?>"><?php echo $result_backmatter->title; ?></a><br/>

                                    <?php } ?><br/>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                    <?php if(count($fetch_ref) > 0) { ?>
                    <div class="col-lg-8" style="margin-left: -14px;">

                        <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#ref">

                                      References

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>



                                <div id="ref" class="collapse in">

                                    <div class="panel-body">

                                        <?php $count = count($fetch_ref); if($count > '5'){ $count = '5'; } for ($x = 0; $x < $count; $x++) {
                                        if((isset($fetch_ref[$x]->thumbnail)) && (!empty($fetch_ref[$x]->thumbnail))) { ?>

                                            <a href="<?php echo $fetch_ref[$x]->link; ?>" target="_blank"><img alt="" style="width:60px; height:60px;" src="<?php echo base_url(); ?>reference_images/<?php echo $fetch_ref[$x]->thumbnail; ?>"></a>&nbsp&nbsp&nbsp<?php echo $fetch_ref[$x]->title; ?><br/><br/>

                                        <?php }  } ?>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <?php } } }?>

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

//highlight dashboard menu default
$(document).ready(function () {
    //alert('hiii');
    $(".home").addClass("active");
    $(".home").addClass("open");
});
</script>

</body>