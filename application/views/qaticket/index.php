
						<div class="row">
							<div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Assigned Ticket</div>
                                    <div class="card-body">
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
                                    	<div class="row m-t-30">
											<div class="col-md-12">
												<div class="table-responsive m-b-40">
													<table id="ticketlist" class="table table-borderless table-data3">
														<thead>
															<tr>
																<th>Ticket No.</th>
																<th>Country</th>
																<th>Designer Name</th>
																<th>Region</th>
																<th>Channel</th>
																<th>Draft</th>
																<th>Complexity</th>
																<th class="text-center">Received Date</th>
																<th class="text-center">Delivery Date</th>
																<th class="text-center">Anno. Pg</th>
																<th class="text-center">Internal Anno. Pg</th>
																<th class="text-center">Status</th>
																<th class="text-center">Action</th>
															</tr>
														</thead>
														<tbody>
															<?php
																if(!empty($data['lists'])){
																	$comb = ''; 
																	foreach($data['lists'] as $val){
																		$statusChangeBtn = !empty($val['draft_no']) && !empty($val['complexity']) && !empty($val['annotated_pages']) && !empty($val['job_status']) ? '<a href="#" class="colr-blue" title="Change status" onclick="populatemodal('.$val['id'].', \''.$val['ticket_no'].'\', \''.$val['job_status'].'\')"><i class="material-icons">create</i></a>' : '';
																		$comb .= '<tr><td>'.$val['ticket_no'].'</td>
																				<td>'.$val['country'].'</td>
																				<td>'.$val['designer_name'].'</td>
																				<td>'.$val['region'].'</td>
																				<td>'.$val['channel'].'</td>
																				<td class="text-center">'.$val['draft_no'].'</td>
																				<td>'.$val['complexity'].'</td>
																				<td class="text-center">'.date('d-m-Y H:i', strtotime($val['job_received_date'])).'</td>
																				<td class="text-center">'.date('d-m-Y', strtotime($val['job_delivery_date'])).'</td>
																				<td class="text-center">'.$val['annotated_pages'].'</td>
																				<td class="text-center">'.$val['qc_page_count'].'</td>
																				<td class="text-center">'.$val['job_status'].'</td>
																				<td class="text-center"><a href="'.APPS_URL.'qaticket/qaedit/'.$val['id'].'" class="colr-blue" title="Edit Form"><i class="material-icons">remove_red_eye</i></a> '.$statusChangeBtn.'</td></tr>'; //data-toggle="modal" data-target="#mediumModal"
																	}
																	echo $comb;
																}else{
																	echo "<tr><td colspan='9' class='text-center'>No data found</td></tr>";
																}
															?>
														</tbody>
													</table>
													<input type="hidden" id="apps_url" value="<?= APPS_URL; ?>">
													<input type="hidden" id="jobstatus" value="<?= join(',', JOB_STATUS); ?>">
												</div>
											</div>
										</div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
