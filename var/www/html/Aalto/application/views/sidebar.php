<!-- BEGIN SIDEBAR -->

                <div class="page-sidebar-wrapper">

                    <!-- BEGIN SIDEBAR -->

                    <div class="page-sidebar navbar-collapse collapse">

                        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">

                            <br/><br/>

                            <li class="nav-item start home">

                                <a href="javascript:;" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-home"></span>

                                    <span class="title">Dashboard</span>

                                    <span class="arrow home"></span>

                                </a>

                                <ul class="sub-menu">

                                    <li class="nav-item start home">

                                        <a href="<?php echo base_url(); ?>dashboard/index/<?php echo $this->session->userdata('Book_Id') ?>" class="nav-link ">

                                            <span class="title">Home</span>

                                        </a>

                                    </li>

                                    <li class="nav-item start ">

                                        <a href="<?php echo base_url(); ?>catalog" class="nav-link ">

                                            <span class="title">My Catalog</span>

                                        </a>

                                    </li>

                                </ul>

                            </li>

                            <?php if($user['role'] == 'Author') { ?>

                            <li class="nav-item  ">

                                <a href="<?php echo base_url() ?>create_newbook/edit_book/<?php echo $this->session->userdata('Book_Id') ?>" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-book"></span>

                                    <span class="title">Book Info</span>

                                </a>

                            </li>

                            <li class="nav-item  ">

                                <a href="<?php echo base_url() ?>reference" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-link"></span>

                                    <span class="title">Reference</span>

                                </a>

                            </li>

                            <li class="nav-item  ">

                                <a href="<?php echo base_url() ?>url_3DModel" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-cloud-upload"></span>

                                    <span class="title">3D Model Upload</span>

                                </a>

                            </li>

                            <!-- <li class="nav-item  ">

                                <a href="<?php echo base_url() ?>IfcFbx" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-wrench"></span>

                                    <span class="title">Ifc to Fbx</span>

                                </a>

                            </li> -->

                            <li class="nav-item ">

                                <a href="javascript:;" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-file"></span>

                                    <span class="title">Text Matter</span>

                                    <span class="arrow"></span>

                                </a>

                                <ul class="sub-menu">

                                    <li class="nav-item  ">

                                        <a href="<?php echo base_url() ?>organize" class="nav-link ">

                                            <span class="title">Organize</span>

                                        </a>

                                    </li>

                                    <li class="nav-item  ">

                                        <a href="<?php echo base_url() ?>part/add_part" class="nav-link ">

                                            <span class="title">Add New Part</span>

                                        </a>

                                    </li>

                                    <li class="nav-item  ">

                                        <a href="<?php echo base_url() ?>chapter/add_chapter" class="nav-link ">

                                            <span class="title">Add New Chapter</span>

                                        </a>

                                    </li>

                                    <li class="nav-item  ">

                                        <a href="<?php echo base_url() ?>frontmatter/add_frontmatter" class="nav-link ">

                                            <span class="title">Add New Front Matter</span>

                                        </a>

                                    </li>

                                    <li class="nav-item  ">

                                        <a href="<?php echo base_url() ?>backmatter/add_backmatter" class="nav-link ">

                                            <span class="title">Add New Back Matter</span>

                                        </a>

                                    </li>

                                </ul>

                            </li>

                            <?php } ?>

                            <?php if($user['role'] == 'Admin') { ?>

                            <li class="nav-item  ">

                                <a href="javascript:;" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-user"></span>

                                    <span class="title">Users</span>

                                    <span class="arrow"></span>

                                </a>

                                 <ul class="sub-menu">

                                    <li class="nav-item  ">

                                        <a href="<?php echo base_url(); ?>all_users" class="nav-link ">

                                            <span class="title">All Users</span>

                                        </a>

                                    </li>

                                </ul>

                            </li>

                            <?php } ?>

                            <?php if($user['role'] == 'Author') { ?>

                            <li class="nav-item  ">

                                <a href="<?php echo base_url(); ?>setting_page" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-cog"></span>

                                    <span class="title">Settings</span>

                                </a>

                            </li>

                            <!-- <li class="nav-item  ">

                                <a href="<?php echo base_url() ?>quiz" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-list-alt"></span>

                                    <span class="title">Quizzes</span>

                                </a>

                            </li> -->

                            <li class="nav-item ">

                                <a href="javascript:;" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-list-alt"></span>

                                    <span class="title">Quiz</span>

                                    <span class="arrow"></span>

                                </a>

                                <ul class="sub-menu">

                                    <li class="nav-item ">

                                        <a href="<?php echo base_url() ?>quiz" class="nav-link nav-toggle">

                                            <span class="title">Quizzes</span>

                                        </a>

                                    </li>

                                    <li class="nav-item ">

                                        <a href="<?php echo base_url() ?>quizreport" class="nav-link nav-toggle">

                                            <span class="title">Reports</span>

                                        </a>

                                    </li>

                                    <li class="nav-item ">

                                        <a href="<?php echo base_url() ?>questionpool" class="nav-link nav-toggle">

                                            <span class="title">Question Pool</span>

                                        </a>

                                    </li>

                                </ul>

                            </li>

                            <?php } ?>

                            <li class="nav-item  ">

                                <a href="<?php echo base_url() ?>activity_log" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-tasks"></span>

                                    <span class="title">Activity Log</span>

                                </a>

                            </li>

                            <?php if($user['role'] == 'Admin') { ?>

                            <li class="nav-item  ">

                                <a href="<?php echo base_url() ?>domains" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-check"></span>

                                    <span class="title">Valid Domains</span>

                                </a>

                            </li>

                            <li class="nav-item  ">

                                <a href="<?php echo base_url() ?>statistic" class="nav-link nav-toggle">

                                    <span class="glyphicon glyphicon-list-alt"></span>

                                    <span class="title">Session of Books</span>

                                </a>

                            </li>

                            <?php } ?>

                        </ul>

                        <!-- END SIDEBAR MENU -->

                    </div>

                    <!-- END SIDEBAR -->

                </div>

                <!-- END SIDEBAR -->