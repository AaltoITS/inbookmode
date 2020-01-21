<!DOCTYPE html>

<html lang="en">
    <title>Settings | Aalto University</title>
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

                    <h1 class="page-title">Settings</h1><hr/>

                    

<div style="width:100%;background-color: #DFF2BF;margin-bottom: 18px;" class="hide-it">

    <?php if((!empty($this->session->flashdata('success')))) {echo $this->session->flashdata('success');} ?>

</div>

                      <div class="col-lg-12">

                        <form action="" method="post" class="form-horizontal">

                            <div class="form-group">

                              <label class="col-lg-2 control-label">Disable Comments</label>

                              <div class="col-lg-10">

                                <div class="radio">

                                  <label>

                                    <input type="radio" name="comments" id="optionsRadios1" <?php if((!empty($db_settings['comments'])) && ($db_settings['comments'] =="Yes")) {echo 'checked="checked"';} ?> value="Yes" required>

                                    Yes, I want to automatically disable comments, trackbacks and pinbacks on all front matter, chapters and back matter.

                                  </label>

                                </div>

                                <div class="radio">

                                  <label>

                                    <input type="radio" name="comments" id="optionsRadios2" <?php if((!empty($db_settings['comments'])) && ($db_settings['comments'] =="No")) {echo 'checked="checked"';} ?> value="No">

                                   No.

                                  </label>

                                </div>

                              </div>

                            </div>

                            <div class="form-group">

                              <div class="col-lg-10 col-lg-offset-2">

                                <input type="submit" name="settingSubmit" class="btn btn-primary" value="Submit">

                              </div>

                            </div>

                        </form>

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

</body>

<?php

/*if(!empty($SettingError['success'])){

  echo '<script language="javascript">';

  echo 'alert("Settings save successfully")';

  echo '</script>';

  //redirect('setting_page');

} 

if(!empty($SettingError['update'])){

  echo '<script language="javascript">';

  echo 'alert("Settings update successfully")';

  echo '</script>';

  //redirect('setting_page');

}*/

?>