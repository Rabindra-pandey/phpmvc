<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>PLI Calculator</title>
  		<link rel="stylesheet" href="<?= APPS_URL; ?>assets/bootstrap/css/bootstrap.min.css"/>
  		<link rel="stylesheet" href="<?= APPS_URL; ?>assets/css/datepicker.css" />
  		<link rel="stylesheet" href="<?= APPS_URL; ?>assets/css/dataTables.bootstrap.min.css" />

	    <link rel="stylesheet" href="<?= APPS_URL; ?>assets/css/material-icons.css" />
	    <link rel="stylesheet" href="<?= APPS_URL; ?>assets/bootstrap/css/bootstrap-select.min.css" />
  		<link rel="stylesheet" href="<?= APPS_URL; ?>assets/css/jquery-ui.min.css"/>

	    <link href="<?= APPS_URL; ?>/assets/css/theme.css" rel="stylesheet" media="all">
  		<link rel="stylesheet" href="<?= APPS_URL; ?>assets/css/styles.css"/>
  		
		<script src="<?= APPS_URL; ?>assets/js/mychart.js"></script>
	</head>
	<body class="animsition">
    	<div class="page-wrapper">

	        <!-- MENU SIDEBAR-->
	        <aside class="menu-sidebar d-none d-lg-block">
	            <div class="logo">
	                <a href="<?= APPS_URL; ?>">
	                    <img src="<?= $data['active_role']==1 ? APPS_URL.'assets/images/logo.png' : APPS_URL.'assets/images/qmetrix.png' ?>" alt="QMetrix" />
	                </a>
	            </div>
	            <div class="menu-sidebar__content js-scrollbar1">
	                <nav class="navbar-sidebar">
	                    <ul class="list-unstyled navbar__list">
                        	<?php if($data['active_role']==1){ ?>
	                        <li <?= ($data['activelink']=='home' || $data['activelink']=='') ? 'class="active"' : ''; ?>>
	                            <a class="js-arrow" href="<?= APPS_URL.'home'; ?>">
	                                <i class="material-icons">dashboard</i>Dashboard
	                            </a>
	                        </li>
	                        <li <?= $data['activelink']=='upload' ? 'class="active"' : ''; ?>>
	                            <a class="js-arrow" href="<?= APPS_URL.'upload'; ?>">
	                                <i class="material-icons">cloud_upload</i>Upload
	                            </a>
	                        </li>
	                        <?php } if($data['active_role']==2){ ?>
	                        <li <?= $data['activelink_seg2']=='dashboard' ? 'class="active"' : ''; ?>>
	                            <a class="js-arrow" href="<?= APPS_URL.'eticket/dashboard'; ?>">
	                                <i class="material-icons">dashboard</i>Dashboard
	                            </a>
	                        </li>
	                        <li <?= $data['activelink']=='eticket' &&  $data['activelink_seg2']=='' ? 'class="active"' : ''; ?>>
	                            <a class="js-arrow" href="<?= APPS_URL.'eticket'; ?>">
	                                <i class="material-icons">assignment</i>Create New Ticket
	                            </a>
	                        </li>
	                        <li <?= $data['activelink_seg2']=='ticketfromdesigner' ? 'class="active"' : ''; ?>>
	                            <a class="js-arrow" href="<?= APPS_URL.'eticket/ticketfromdesigner'; ?>">
	                                <i class="material-icons">person_add</i>Resource Alloc. Tickets
	                            </a>
	                        </li>
	                        <li <?= $data['activelink_seg2']=='ticketlist' ? 'class="active"' : ''; ?>>
	                            <a class="js-arrow" href="<?= APPS_URL.'eticket/ticketlist'; ?>">
	                                <i class="material-icons">view_list</i>Ticket Lists
	                            </a>
	                        </li>
	                        <li <?= $data['activelink_seg2']=='rejectedticket' ? 'class="active"' : ''; ?>>
	                            <a class="js-arrow" href="<?= APPS_URL.'eticket/rejectedticket'; ?>">
	                                <i class="material-icons">error</i>Rejected Tickets
	                            </a>
	                        </li>
	                        <li <?= $data['activelink_seg2']=='approvedticket' ? 'class="active"' : ''; ?>>
	                            <a class="js-arrow" href="<?= APPS_URL.'eticket/approvedticket'; ?>">
	                                <i class="material-icons">check_circle</i>Approved Tickets
	                            </a>
	                        </li>
	                        <li <?= $data['activelink']=='qareport' &&  $data['activelink_seg2']=='' ? 'class="active"' : ''; ?>>
	                            <a class="js-arrow" href="<?= APPS_URL.'qareport'; ?>">
	                                <i class="material-icons">cloud_download</i>Report
	                            </a>
	                        </li>
	                        <?php } if($data['active_role']==3){ ?>	                       
	                        <li <?= $data['activelink']=='qaticket' ? 'class="active"' : ''; ?>>
	                            <a class="js-arrow" href="<?= APPS_URL.'qaticket'; ?>">
	                                <i class="material-icons">view_list</i>Assigned Ticket
	                            </a>
	                        </li>
	                        <?php } ?>
	                    </ul>
	                </nav>
	            </div>
	        </aside>
	        <!-- END MENU SIDEBAR-->

	        <div class="page-container">
	            <!-- HEADER DESKTOP-->
	            <header class="header-desktop">
	                <div class="section__content section__content--p30">
	                    <div class="container-fluid">
	                        <div class="header-wrap">
	                            <div class="header-button">                                
                                	<div class="account-wrap">                                		
										<div class="account-item clearfix js-item-menu">
											<div class="image">
												<i class="font-40 material-icons">face</i>
											</div>
											<div class="content">
												Welcome <strong><?= !empty($data['active_session']['username']) ? $data['active_session']['username'] : ''; ?></strong>
											</div>
										</div>
									</div>
	                            </div>
	                            <div class="header-button">                                
                                	<div class="account-wrap">  
	                                    <div class="account-item clearfix js-item-menu">
	                                        <a href="<?= APPS_URL; ?>logout" title="Logout"><i class="font-40 material-icons">exit_to_app</i></a>
	                                    </div>
									</div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </header>
	            <!-- HEADER DESKTOP-->

	            <!-- MAIN CONTENT-->
	            <div class="main-content">
	                <div class="section__content section__content--p30">
	                    <div class="container-fluid">