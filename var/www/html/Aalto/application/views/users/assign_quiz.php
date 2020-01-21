<!DOCTYPE html>
<html lang="en">
    <title>Assign Quiz | Aalto University</title>
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
            <div class="clearfix"></div>
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
                    <h1 class="page-title"><a href="<?php echo base_url() ?>quiz">Quizzes</a> / Assign Quiz / <?php echo $sel_quiz['0']->Title; ?></h1><hr/>

                    <div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">

                        <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>

                    </div>

                    <div style="width:100%;background-color: #ffb2b2;margin-bottom: 18px;" class="hide-it">

                        <?php if((!empty($this->session->flashdata('error_msg')))) {echo $this->session->flashdata('error_msg');} ?>

                    </div>
                <?php $myArray = explode(',', $sel_quiz['0']->assigned_users); ?>
                <form id="frm-example" method="POST">
                    <input type="hidden" name="quiz_id" value="<?php echo $sel_quiz['0']->QuizId; ?>">
                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example">
                        <thead>
                            <tr>
                                <th style="display: none"></th>
                                <th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                                <th>Username / Email</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $x = 0; foreach($fetch_data as $result_data) { if($result_data->role != 'Admin'){ ?>
                                <tr>
                                    <td style="display: none"></td>
                                    <!-- <td><?php //echo '<input type="checkbox" name="userMapping[]" value="'.$result_data->id.'">';?></td> -->
                                    <td><input type="checkbox" class="checkSingle" name="userMapping[]" value="<?php echo $result_data->id; ?>" <?php if(in_array($result_data->id, $myArray)) { echo 'checked="checked"'; }?> /></td>
                                    <td><?php echo $result_data->username; ?> / <?php echo $result_data->email; ?></td>
                                    <td><?php echo $result_data->role; ?></td>
                                    <td><?php echo $result_data->status; ?></td>
                                    
                                </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                    <button class="btn btn-primary">Submit</button>
                </form>
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
<script type="text/javascript">
    var table = $('#example').DataTable({
        language: {
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            infoEmpty: "No records found",
            infoFiltered: "(filtered1 from _MAX_ total records)",
            lengthMenu: "Show _MENU_",
            search: "Search:",
            zeroRecords: "No matching records found",
        },
        bStateSave: !0,
        lengthMenu: [
            [5, 15, 20, -1],
            [5, 15, 20, "All"]
        ],
        pageLength: 5,
        pagingType: "bootstrap_full_number",
        columnDefs: [{
            orderable: false,
            targets: [1]
        }]
    });

    // Handle click on "Select all" control
    $('#example-select-all').on('click', function(){
      // Check/uncheck all checkboxes in the table
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    // Handle click on checkbox to set state of "Select all" control
    $('#example tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#example-select-all').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control 
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
    });

   $('#frm-example').on('submit', function(e) {
        var form = $(this);
        // Iterate over all checkboxes in the table
        table.$('input[type="checkbox"]').each(function(){
         // If checkbox doesn't exist in DOM
         if(!$.contains(document, this)){
            // If checkbox is checked
            if(this.checked){
               // Create a hidden element 
               $(form).append(
                  $('<input>')
                     .attr('type', 'hidden')
                     .attr('name', this.name)
                     .val(this.value)
               );
            }
         } 
        });
        var formData = form.serialize(); 
        // Output form data to a console 
        //console.log("Form submission"+formData);
        // Prevent actual form submission
        //e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>quiz/get_assign_user/',
            data: formData,
            success: function(data) {
                //alert(data);
                if(data == "not_selected") {
                    alert('Please select atleast one user');
                } else {
                    alert('Quiz Assigned successfully');
                    location.reload(true);
                }
            }
        });
        return false;
    });

    $(document).ready(function() {
        //to show main checkbox checked if all checkboxces were checked
        var isAllChecked = 0;
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).each(function() {
        //$(".checkSingle").each(function() {
        if(!this.checked)
            isAllChecked = 1;
        });              
        if(isAllChecked == 0) {
            $("#example-select-all").prop("checked", true);
        }     
    });
</script>