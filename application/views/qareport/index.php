

						<div class="row">
							<div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Report Generation</div>
                                    <div class="card-body">
                                        <form action="<?= APPS_URL.'qareport/generatereport'; ?>" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                            	<div class="col-lg-3">
                                            		<div class="form-group">
		                                                <label for="startdate" class="control-label mb-1">Start Date</label>
		                                                <input autocomplete="off" id="startdate" name="startdate" class="form-control" placeholder="dd-mm-yyyy" type="text" required>
		                                            </div>
                                            	</div> 
                                            	<div class="col-lg-3">
                                            		<div class="form-group">
		                                                <label for="enddate" class="control-label mb-1">End Date</label>
		                                                <input autocomplete="off" id="enddate" name="enddate" class="form-control" placeholder="dd-mm-yyyy" type="text" required>
		                                            </div>
                                            	</div>          
                                            	<div class="col-lg-3">
                                            		<div class="form-group">
		                                                <label for="region" class="control-label mb-1">Region</label>
		                                                <select name="region" id="region" class="form-control">
		                                                	<option value="">----Select option----</option>
		                                                	<?php 
																$regionCount = count($data['regions']);
																for ($i=0; $i < $regionCount; $i++) { 
																	echo "<option value='".$data['regions'][$i]['region']."'>".$data['regions'][$i]['region']."</option>";
																}				
															?>
		                                                </select>
		                                            </div>
                                            	</div> 
                                            	<div class="col-lg-3">
                                            		<div class="form-group">
		                                                <label for="channel" class="control-label mb-1">Channel</label>
		                                                <select name="channel" id="channel" class="form-control">
		                                                	<option value="">----Select option----</option>
		                                                	<?php 
																$channelCount = count($data['channels']);
																for ($i=0; $i < $channelCount; $i++) { 
																	echo "<option value='".$data['channels'][$i]['channel']."'>".$data['channels'][$i]['channel']."</option>";
																}				
															?>
		                                                </select>
		                                            </div>
                                            	</div> 
                                            </div>
                                            <div class="form-group">
                                                <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                                    Generate
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">Report Upload</div>
                                    <div class="card-body">
                                       	<?php
                                    		if(!empty($data['querystring']) && array_key_exists("succ", $data['querystring'])){
												echo '<div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
														<span class="badge badge-pill badge-success">Success</span> 
														'.urldecode($data['querystring']['succ']).'
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">×</span>
														</button>
													</div>';
											}
                                    		if(!empty($data['querystring']) && array_key_exists("error", $data['querystring']) && !empty($data['querystring']['error'])){
												echo '<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
														<span class="badge badge-pill badge-danger">Error</span> 
														'.urldecode($data['querystring']['error']).'
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">×</span>
														</button>
													</div>';
											}
                                    	?>  
                                        <form action="<?= APPS_URL.'qareport/reportupload'; ?>" method="post" enctype="multipart/form-data" onSubmit="return confirm('Make sure you have entered status as <?= implode(' or ', JOB_STATUS) ?> and Active & Cancel column as capital A or C');">
                                        	<div class="row">
                                        		<div class="col-lg-12">
		                                            <div class="form-group">
		                                            	<label for="uploadfile" class="control-label mb-1">Choose Report File</label>
														<div class="input-group input-file" name="uploadfile" id="uploadfile">
															<span class="input-group-btn">
												        		<button class="btn btn-secondary btn-choose" type="button">Choose</button>
												    		</span>
												    		<input type="text" class="form-control" placeholder='Choose a file...' />
												    		<span class="input-group-btn">
												       			 <button class="btn btn-danger btn-reset" type="button">Reset</button>
												    		</span>
														</div>
													</div>
		                                        </div> 
                                        	</div>
                                            <div class="form-group">
                                                <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                                    Upload
                                                </button>
                                            </div>
                                        </form>
										<p><i class="colr-red">** </i>Uploaded file header must be same as report generated header and status column must be matched with <?= implode(' or ', JOB_STATUS) ?> and also 'Active' &amp; 'Cancel' column as capital A or C only.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
