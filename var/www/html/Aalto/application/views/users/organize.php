<!DOCTYPE html>

<html lang="en">
    <title>Organize | Aalto University</title>
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

<style>

table {

    font-family: arial, sans-serif;

    border-collapse: collapse;

    width: 100%;

}



td, th {

    border: 1px solid #dddddd;

    text-align: left;

    padding: 8px;

}

</style>

                    <!-- BEGIN CONTENT BODY -->

                    <div class="page-content">

                    <div class="page-bar">

                    <h1 class="page-title">Book Information</h1><hr/>

                    <div class="col-lg-12" style="margin-left: -14px;">

                        <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#front_matter">

                                        Front Matter

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="front_matter" class="collapse in ">

                                    <div class="panel-body">



                                        <table>

                                            <tr>

                                                <th>Title</th>

                                                <th>Author</th>

                                                <th>Action</th>

                                            </tr>

                                            <?php foreach($fetch_frontmatter as $result_frontmatter) { ?>

                                            <tr>

                                                <td><?php echo $result_frontmatter->title; ?></td>

                                                <td><?php echo $fetch_author['0']->author; ?></td>

                                                <td><a href="<?php echo base_url() ?>frontmatter/edit_frontmatter/<?php echo $result_frontmatter->id ?>"><span class="glyphicon glyphicon-pencil" title="edit"></a> | <a href="<?php echo base_url(); ?>frontmatter/delete_frontmatter/<?php echo $result_frontmatter->id ?>" onclick="isconfirm_front();"><span class="glyphicon glyphicon-trash" title="delete"></a></td>

                                            </tr>

                                            <?php } ?>

                                        </table>

                                        <br/>

                                        <button type="button" class="btn btn-primary" style="width: 135px; float: right;" onclick="window.location='<?php echo site_url("frontmatter/add_frontmatter");?>'">Add Front Matter</button>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#main_body">

                                        Main Body

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="main_body" class="collapse in ">

                                    <div class="panel-body">

                                        <table>

                                            <tr>

                                                <th>Title</th>

                                                <th>Author</th>

                                                <th>Action</th>

                                            </tr>

                                            <?php foreach($fetch_chapter as $result_chapter) { ?>

                                            <tr>

                                                <td><?php echo $result_chapter->title; ?></td>

                                                <td><?php echo $fetch_author['0']->author; ?></td>

                                                <td><a href="<?php echo base_url() ?>chapter/edit_chapter/<?php echo $result_chapter->id ?>"><span class="glyphicon glyphicon-pencil" title="edit"></a> | <a href="<?php echo base_url(); ?>chapter/delete_chapter/<?php echo $result_chapter->id ?>" onclick="isconfirm_main();"><span class="glyphicon glyphicon-trash" title="delete"></a></td>

                                            </tr>

                                            <?php } ?>

                                        </table>

                                        <br/>

                                        <button type="button" class="btn btn-primary" style="width: 135px; float: right;" onclick="window.location='<?php echo site_url("chapter/add_chapter");?>'">Add Chapter</button>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#part">

                                        Part

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="part" class="panel-collapse collapse in ">

                                    <div class="panel-body">

                                        <table>

                                            <tr>

                                                <th>Title</th>

                                                <th>Author</th>

                                                <th>Action</th>

                                            </tr>

                                            <?php foreach($fetch_part as $result_part) { ?>

                                            <tr>

                                                <td><?php echo $result_part->title; ?></td>

                                                <td><?php echo $fetch_author['0']->author; ?></td>

                                                <td><a href="<?php echo base_url() ?>part/edit_part/<?php echo $result_part->id ?>"><span class="glyphicon glyphicon-pencil" title="edit"></a> | <a href="<?php echo base_url(); ?>part/delete_part/<?php echo $result_part->id ?>" onclick="isconfirm_part();"><span class="glyphicon glyphicon-trash" title="delete"></a></td>

                                            </tr>

                                            <?php } ?>

                                        </table>

                                        <br/>

                                        <button type="button" class="btn btn-primary" style="width: 135px; float: right;" onclick="window.location='<?php echo site_url("part/add_part");?>'">Add Part</button>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="panel-group" id="accordion">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                  <h4 class="panel-title">

                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#back_matter">

                                        Back Matter

                                    </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>

                                  </h4>

                                </div>

                                <div id="back_matter" class="collapse in ">

                                    <div class="panel-body">

                                        <table>

                                            <tr>

                                                <th>Title</th>

                                                <th>Author</th>

                                                <th>Action</th>

                                            </tr>

                                            <?php foreach($fetch_backmatter as $result_backmatter) { ?>

                                            <tr>

                                                <td><?php echo $result_backmatter->title; ?></td>

                                                <td><?php echo $fetch_author['0']->author; ?></td>

                                                <td><a href="<?php echo base_url() ?>backmatter/edit_backmatter/<?php echo $result_backmatter->id ?>"><span class="glyphicon glyphicon-pencil" title="edit"></a> | <a href="<?php echo base_url(); ?>backmatter/delete_backmatter/<?php echo $result_backmatter->id ?>" onclick="isconfirm_back();"><span class="glyphicon glyphicon-trash" title="delete"></a></td>

                                            </tr>

                                            <?php } ?>

                                        </table>

                                        <br/>

                                        <button type="button" class="btn btn-primary" style="width: 135px; float: right;" onclick="window.location='<?php echo site_url("backmatter/add_backmatter");?>'">Add Back Matter</button>

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

function isconfirm_front(){



if(!confirm('Are you sure want to delete this front matter ?')){

event.preventDefault();

return;

}

return true;

}

function isconfirm_main(){



if(!confirm('Are you sure want to delete this chapter ?')){

event.preventDefault();

return;

}

return true;

}

function isconfirm_part(){



if(!confirm('Are you sure want to delete this part ?')){

event.preventDefault();

return;

}

return true;

}

function isconfirm_back(){



if(!confirm('Are you sure want to delete this back matter ?')){

event.preventDefault();

return;

}

return true;

}
</script>

</body>