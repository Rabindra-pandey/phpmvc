
						<div class="row">
							<div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Edit Ticket</div>
                                    <div class="card-body"> 
                                       	<div id="error-display">
                                        <?php
                                    		if(!empty($data['querystring']) && $data['querystring']['key']=='succ'){
												echo '<div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
														<span class="badge badge-pill badge-success">Success</span> 
														'.urldecode($data['querystring']['val']).'
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">×</span>
														</button>
													</div>';
											}
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
										</div>                       
                                        <form id="eticketform" name="eticketform" action="<?= APPS_URL.'qaticket/saveticket'; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" onSubmit="return checkValidationOfEticket()" novalidate>
                                        	<div class="row">
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="ticket_no" class="form-control-label mt-2">Ticket Number<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<input type="text" id="ticket_no" name="eticket[ticket_no]" placeholder="Enter e-form ticket number" class="form-control" required value="<?= !empty($data['edit']['ticket_no']) ? $data['edit']['ticket_no'] : '' ?>" <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
														</div>
													</div>
												</div>
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="country" class="form-control-label mt-2">Country Name<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<input type="text" id="country_name" name="eticket[country]" placeholder="Enter the country name" class="form-control" required value="<?= !empty($data['edit']['country']) ? $data['edit']['country'] : '' ?>" <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
														</div>
													</div>
												</div>
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="designer_id" class="form-control-label mt-2">Designer Name<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<input type="hidden" id="designer_name" name="eticket[designer_name]" value="<?= !empty($data['edit']['designer_name']) ? $data['edit']['designer_name'] : '' ?>">
															<select class="form-control selectpicker" id="designer_id" name="eticket[designer_id]" data-live-search="true" required onChange="getSelectedText('designer_id', 'designer_name')" <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
																<option value="">Select designer name</option>	
																<?php
																	foreach($data['designerList'] as $val){
																		if($val['team']!='QC Pool'){
																			$edit_designer_id = !empty($data['edit']['designer_id']) && $data['edit']['designer_id']==$val['employee_id'] ? 'selected' : '';
																			
																			echo '<option data-tokens="'.$val['first_name'].' '.$val['last_name'].'" value="'.$val['employee_id'].'" '.$edit_designer_id.'>'.$val['first_name'].' '.$val['last_name'].'</option>';
																		}
																	}
																?>																
															</select>
														</div>
													</div>
												</div>
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="qa_id" class="form-control-label mt-2">QA Name<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<input type="hidden" id="qa_name" name="eticket[qa_name]" value="<?= !empty($data['edit']['qa_name']) ? $data['edit']['qa_name'] : '' ?>">
															<select class="form-control selectpicker" id="qa_id" name="eticket[qa_id]" data-live-search="true" required onChange="getSelectedText('qa_id', 'qa_name')" <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
																<option value="">Select QA name</option>	
																<?php
																	foreach($data['designerList'] as $val){
																		if($val['team']=='QC Pool'){
																			$edit_qa_id = !empty($data['edit']['qa_id']) && $data['edit']['qa_id']==$val['employee_id'] ? 'selected' : '';
																			
																			echo '<option data-tokens="'.$val['first_name'].' '.$val['last_name'].'" value="'.$val['employee_id'].'" '.$edit_qa_id.'>'.$val['first_name'].' '.$val['last_name'].'</option>';
																		}
																	}
																?>																
															</select>
														</div>
													</div>
												</div>
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="region" class="form-control-label mt-2">Region<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<select class="form-control" id="region" name="eticket[region]" required <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
																<option value="">Select region</option>
																<?php 
																	foreach($data['regions'] as $val){
																		$edit_region = !empty($data['edit']['region']) && strtolower($data['edit']['region'])==strtolower($val['region']) ? 'selected' : '';
																		
																		echo '<option value="'.$val['region'].'" '.$edit_region.'>'.$val['region'].'</option>';
																	}
																?>																		
															</select>
														</div>
													</div>
												</div>
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="channel" class="form-control-label mt-2">Channel<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<select class="form-control" id="channel" name="eticket[channel]" required <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?> onchange="showhideqcpagecount(this.value); getComplexity(this.value)">
																<option value="">Select channel</option>
																<?php
																	foreach($data['channels'] as $val){
																		$edit_channel = !empty($data['edit']['channel']) && strtolower($data['edit']['channel'])==strtolower($val['channel']) ? 'selected' : '';
																		
																		echo '<option value="'.$val['channel'].'" '.$edit_channel.'>'.$val['channel'].'</option>';
																	}
																?>																	
															</select>
														</div>
													</div>
												</div>
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="job_type" class="form-control-label mt-2">Job Type<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<select class="form-control selectpicker" id="job_type" name="eticket[job_type]" data-live-search="true" required <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
																<option value="">Select job type</option>	
																<?php
																	foreach(JOB_TYPES as $val){
																		$edit_job_type = !empty($data['edit']['job_type']) && $data['edit']['job_type']==$val ? 'selected' : '';
																		echo '<option data-tokens="'.$val.'" value="'.$val.'" '.$edit_job_type.'>'.$val.'</option>';
																	}
																?>																
															</select>
														</div>
													</div>
												</div>                                            
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="draft_no" class="form-control-label mt-2">Draft Number<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<select class="form-control selectpicker" id="draft_no" name="eticket[draft_no]" data-live-search="true" required <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
																<option value="">Select draft number</option>	
																<?php
																	foreach(DRAFT_NO as $val){
																		$edit_draft_no = !empty($data['edit']['draft_no']) && $data['edit']['draft_no']==$val ? 'selected' : '';
																		
																		echo '<option data-tokens="'.$val.'" value="'.$val.'" '.$edit_draft_no.'>'.$val.'</option>';
																	}
																?>																
															</select>
														</div>
													</div>
												</div>												
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="new_amends" class="form-control-label mt-2">New Amends/DC Miss</label>
														</div>
														<div class="col-12 col-md-8">
															<input type="text" id="new_amends" name="eticket[new_amends]" placeholder="Enter the value" class="form-control" value="<?= !empty($data['edit']['new_amends']) ? $data['edit']['new_amends'] : '' ?>" <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
														</div>
													</div>
												</div>
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="hubloc" class="form-control-label mt-2">Hub/LOC</label>
														</div>
														<div class="col-12 col-md-8">
															<select class="form-control" id="hubloc" name="eticket[job_comes_from]" <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
																<option value="">Select Hub/LOC</option>																	
																<option value="Hub" <?php if(!empty($data['edit']['job_comes_from']) && $data['edit']['job_comes_from']=='Hub') echo 'selected'; ?>>Hub</option>	
																<option value="LOC" <?php if(!empty($data['edit']['job_comes_from']) && $data['edit']['job_comes_from']=='LOC') echo 'selected'; ?>>LOC</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="complexity" class="form-control-label mt-2">Complexity<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<select class="form-control selectpicker" id="complexity" name="eticket[complexity]" data-live-search="true" required <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
																<?php																	
																	$complexityOption = '<option value="">Select complexity</option>';
																	if(!empty($data['edit']['channel'])){
																		$complexityOption .= '<optgroup label="'.strtoupper($data['edit']['channel']).'">';
																		$finKey = strtolower($data['edit']['channel']);
																		foreach(COMPLEXITY[$finKey] as $comval){
																			$edit_complexity = !empty($data['edit']['complexity']) && $data['edit']['complexity']==$comval ? 'selected' : '';

																			$complexityOption .= '<option data-tokens="'.$comval.'" value="'.$comval.'" '.$edit_complexity.'>'.$comval.'</option>';
																		}
																		$complexityOption .= '</optgroup>';
																	}else{
																		foreach(COMPLEXITY as $key=>$val){
																			$complexityOption .= '<optgroup label="'.strtoupper($key).'">';
																			foreach($val as $comval){
																				$edit_complexity = !empty($data['edit']['complexity']) && $data['edit']['complexity']==$comval ? 'selected' : '';

																				$complexityOption .= '<option data-tokens="'.$comval.'" value="'.$comval.'" '.$edit_complexity.'>'.$comval.'</option>';
																			}
																			$complexityOption .= '</optgroup>';
																		}
																	}
																	echo $complexityOption;
																?>																
															</select>
														</div>
													</div>
												</div>                                                                                       
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="received_date" class="form-control-label mt-2">Job Received Date</label>
														</div>
														<div class="col-12 col-md-8">		
															<div class="input-group">
																<input name="eticket[job_received_date]" id="received_date" class="form-control" placeholder="dd-mm-yyyy h:m:s" type="text" readonly value="<?= !empty($data['edit']['job_received_date']) ? date('d-m-Y H:i:s', strtotime($data['edit']['job_received_date'])) : date('d-m-Y H:i:s') ?>">
																<div class="input-group-addon">
																	<i class="material-icons">date_range</i>
																</div>
															</div>				
														</div>
													</div>
												</div>                                                                                         
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="delivery_date" class="form-control-label mt-2">Job Delivery Date<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">		
															<div class="input-group">
																<input name="eticket[job_delivery_date]" id="delivery_date" class="form-control" placeholder="dd-mm-yyyy" type="text" value="<?= !empty($data['edit']['job_delivery_date']) ? date('d-m-Y', strtotime($data['edit']['job_delivery_date'])) : '' ?>" autocomplete="off" readonly>
																<div class="input-group-addon">
																	<i class="material-icons">date_range</i>
																</div>
															</div>				
														</div>
													</div>
												</div>                                                                                   
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="total_pages" class="form-control-label mt-2">Total Pages<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<input type="text" id="total_pages" name="eticket[total_pages]" placeholder="Enter total pages" class="form-control" required value="<?= !empty($data['edit']['total_pages']) ? $data['edit']['total_pages'] : '' ?>" <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
														</div>
													</div>
												</div>                                                                                 
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="annotated_pages" class="form-control-label mt-2">Annotated Pages<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<input type="text" id="annotated_pages" name="eticket[annotated_pages]" placeholder="Enter annonated pages" class="form-control" required value="<?= !empty($data['edit']['annotated_pages']) ? $data['edit']['annotated_pages'] : '' ?>" <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
														</div>
													</div>
												</div>                                                                                  
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="status" class="form-control-label mt-2">Status<sup class="danger">*</sup></label>
														</div>
														<div class="col-12 col-md-8">
															<select class="form-control" id="status" name="eticket[job_status]" required <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
																<option value="">Select status</option>	
																<?php
																	foreach(JOB_STATUS as $key=>$val){
																		if($val=='Open' || $val=='WIP' || $val=='Recheck'){
																			$edit_job_status = !empty($data['edit']['job_status']) && $data['edit']['job_status']==$val ? 'selected' : '';

																			echo '<option value="'.$val.'" '.$edit_job_status.'>'.$val.'</option>	';
																		}
																	}
																?>
															</select>
														</div>
													</div>
												</div>                                                                                  
												<div class="form-group col-md-6 <?php if(strtolower($data['edit']['channel'])=='ipad'){}else{ echo 'hide';} ?> qcpagecount">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="qc_page_count" class="form-control-label mt-2">QC Page Count</label>
														</div>
														<div class="col-12 col-md-8">
															<input type="text" id="qc_page_count" name="eticket[qc_page_count]" placeholder="Enter pages count" class="form-control" required value="<?= !empty($data['edit']['qc_page_count']) ? $data['edit']['qc_page_count'] : '' ?>" <?= !empty($data['readyonly']) ? $data['readyonly'] : '' ?>>
														</div>
													</div>
												</div>     
												<div class="form-group col-md-6 <?php if(strtolower($data['edit']['channel'])=='ipad'){}else{ echo 'hide';} ?> qcpagecount"></div>
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="comments" class="form-control-label mt-5">Comments</label>
														</div>
														<div class="col-12 col-md-8">
															<textarea name="eticket[comments]" id="comments" rows="4" placeholder="Enter the comments here..." class="form-control"><?= !empty($data['edit']['comments']) ? $data['edit']['comments'] : '' ?></textarea>
														</div>
													</div>
												</div>                                                                            
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="suggestions" class="form-control-label mt-5">Suggestions</label>
														</div>
														<div class="col-12 col-md-8">
															<textarea name="eticket[suggestions]" id="suggestions" rows="4" placeholder="Enter the suggestions here..." class="form-control"><?= !empty($data['edit']['suggestions']) ? $data['edit']['suggestions'] : '' ?></textarea>
														</div>
													</div>
												</div>                                                                          
												<div class="form-group col-md-6">
													<div class="row"> 
														<div class="col col-md-4 text-right">
															<label for="quality_code" class="form-control-label mt-4">Quality Code</label>
														</div>
														<div class="col-12 col-md-8">
															<textarea name="eticket[quality_codes]" id="quality_code" rows="3" placeholder="Enter the quality code here..." class="form-control"><?= !empty($data['edit']['quality_codes']) ? $data['edit']['quality_codes'] : '' ?></textarea>
														</div>
													</div>
												</div>      
											</div>  
                                            <div class="row">                                 
												<div class="form-group offset-5">
													<input type="hidden" value="<?= APPS_URL; ?>" name="app_url" id="app_url">
													<input type="hidden" value="<?= $_SESSION['userdata']['role']; ?>" name="user_role" id="user_role">
													<input type="hidden" value="<?= $data['edit']['id'] ?>" name="editedId" id="editedId">
													<a href="<?= APPS_URL.'qaticket'; ?>" title="back to assigned page"><input type="button" class="btn btn-lg btn-info" value="Back"></a>
													<input name="submit_eticket" id="submit_eticket" type="submit" class="btn btn-lg btn-info" value="Save">
												</div>
                                       		</div>
                                        </form>
                                    </div> 
                                </div>
                            </div>
                        </div>
