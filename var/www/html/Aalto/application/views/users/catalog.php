<!DOCTYPE html>

<html lang="en">
    <title>Catalog | Aalto University</title>
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

                        <h1 class="page-title">My Catalog</h1><hr/>

                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">

                            <thead>

                                <tr>

                                    <th style="display: none"></th>

                                    <!-- <th>Id</th> -->

                                    <th>Book title</th>

                                    <th>Author</th>

                                    <th>Cover</th>

                                    <th>Privacy Status</th>

                                    <?php if($user['role'] == 'Author') { ?>

                                    <th>Action</th>

                                    <?php } ?>

                                </tr>

                            </thead>

                            <tbody>

                            <?php foreach($fetch_data as $result_data) { ?>

                                <tr>

                                    <td style="display: none"></td>

                                    <!-- <td><?php echo $result_data->id; ?></td> -->

                                    <td><?php echo $result_data->title; ?></td>

                                    <td><?php echo $result_data->author; ?></td>

                                    <td><img alt="" style="width:60px; height:70px;" src="<?php echo base_url(); ?>uploads/<?php echo $result_data->cover_image; ?>"></td>

                                    <td><?php echo $result_data->privacy_status; ?></td>

                                    <?php if($user['role'] == 'Author') { ?>

                                    <td><a href="<?php echo base_url() ?>create_newbook/edit_book/<?php echo $result_data->id ?>"><span class="glyphicon glyphicon-pencil" title="edit"></span></a> | <a href="<?php echo base_url(); ?>create_newbook/delete_book/<?php echo $result_data->id ?>" onclick="isconfirm();"><span class="glyphicon glyphicon-trash" title="delete"></a> | <span class="glyphicon glyphicon-print" title="print" style="cursor: pointer; color: #337ab7;" onclick="printPDF(<?php echo $result_data->id; ?>)" >
                                    </td>

                                    <?php } ?>
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



if(!confirm('Are you sure want to delete this book?')){

event.preventDefault();

return;

}

return true;

}

</script>

<script>
function printPDF(BOOKID){

var id = (new Date()).getTime();
    var printWindow = window.open(window.location.href + '?printBook=true', id, "toolbar=1,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=1,width=900,height=590,left = 225,top = 65");

jQuery.ajax({
        url:  "<?php echo base_url(); ?>/app_user/get_book_data_pdf/"+BOOKID,
        type:'GET',
    success:function(data){
//var printWindow = window.open('', '', 'height=600,width=900');
    printWindow.document.write('<html><head><style>@media print {@page { } .pagebreak { page-break-before: always; } }</style>');
    printWindow.document.write('<body >');
    printWindow.document.write(data);
    printWindow.document.write('</body></html>');
    printWindow.focus();
    printWindow.document.close();
        setTimeout(function() {
        printWindow.print();
        printWindow.close();
        }, 1500);
}
    });

}
</script>