

						<div class="row">
							<div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Upload files</div>
                                    <div class="card-body">
                                    	<?php
                                    		if(!empty($data['error'])){
                                    			echo '<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
														<span class="badge badge-pill badge-danger">Error</span>
														'.$data['error'].'
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">×</span>
														</button>
													</div>';
                                    		}else if(!empty($data['succ'])){
                                    			echo '<div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
														<span class="badge badge-pill badge-success">Success</span>
														'.$data['succ'].'
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">×</span>
														</button>
													</div>';
                                    		}
                                    	?>                                    	
                                        <form action="<?= APPS_URL.'upload/upload_files'; ?>" method="post" enctype="multipart/form-data" onsubmit="return checkUpload()">
                                            <div class="row">
                                            	<div class="col-lg-8 offset-1">
		                                            <div class="form-group">
		                                            	<label for="rft_data" class="control-label mb-1">Choose RFT Data File</label>
														<div class="input-group input-file" name="rft_data" id="rft_data">
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
		                                        <div class="col-lg-2">
                                            		<div class="form-group">
		                                                <label for="uploadedmonth" class="control-label mb-1">RFT for the month of</label>
		                                                <input autocomplete="off" id="uploadedmonth" name="uploadedmonth" class="datepickerformonth form-control" placeholder="mm-yyyy" data-date-format="mm-yyyy" type="text">
		                                            </div>
                                            	</div>                                          	
                                            </div>
                                            <div class="form-group offset-1">
                                            	<input name="override" type="hidden" value="true" disabled="disabled">
                                                <button name="submit_upload" id="submit_upload" type="submit" class="btn btn-lg btn-info">
                                                    Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
