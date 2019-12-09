<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>PLI Calculator</title>
  		<link rel="stylesheet" href="<?= APPS_URL; ?>assets/bootstrap/css/bootstrap.min.css"/>
	    <link rel="stylesheet" href="<?= APPS_URL; ?>assets/css/material-icons.css" />

	    <link href="<?= APPS_URL; ?>/assets/css/theme.css" rel="stylesheet" media="all">
  		<link rel="stylesheet" href="<?= APPS_URL; ?>assets/css/styles.css"/>
  		<style type="text/css">
			.page-wrapper{padding: 0px;}
		</style>
	</head>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="<?= APPS_URL; ?>">
								<img src="<?= APPS_URL; ?>assets/images/qmetrix.png" alt="PLI Calculator" />
							</a>
                        </div>
                        <div class="login-form">
                           	<?php
								if(!empty($data['querystring']) && $data['querystring']['key']=='error'){
									echo '<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
											<span class="badge badge-pill badge-danger">Error</span>
											'.urldecode($data['querystring']['val']).'
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>';
								}
							?> 
                            <form action="<?= APPS_URL; ?>login/loginuser" method="post" onSubmit="return checkUser();">
                                <div class="row form-group">
                                    <div class="col col-md-12">
                                    	<label for="empid">User Id</label>
										<div class="input-group">
											<input type="text" id="empid" name="empid" placeholder="Enter your employee id" class="form-control">
											<div class="input-group-addon"><i class="material-icons">account_circle</i></div>
										</div>
									</div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-12">
                                    	<label for="empid">Password</label>
										<div class="input-group">
											<input type="password" id="password" name="password" placeholder="Enter your password" class="form-control">
											<div class="input-group-addon"><i class="material-icons">https</i></div>
										</div>
									</div>
                                </div>
                                <div class="form-group">
                                    <label for="user_type">User Type</label>
                                    <select class="form-control" id="user_type" name="user_type">
										<option value="">Select User Type</option>																	
										<option value="admin">Admin</option>																	
										<option value="pm">PM/SME</option>																	
										<option value="qa">QA</option>																	
									</select>
                                </div>
                                <input class="au-btn au-btn--block au-btn--green m-b-20" type="submit" name="userlogin" value="sign in">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

		<script src="<?= APPS_URL; ?>assets/js/jquery.min.js"></script>
		<script src="<?= APPS_URL; ?>assets/js/custom.js"></script>
	</body>
</html>