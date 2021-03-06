
						<div class="row">
							<div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        Approved ticket lists by date filter
                                    </div>
                                    <div class="card-body card-block">
                                        <form action="" method="post" class="form-inline">
                                            <div class="form-group">
                                                <label for="startdate" class="pr-1 form-control-label">Start Date:</label>
                                                <input type="text" id="startdate" name="startdate" placeholder="Start date in dd-mm-yyyy" required="" class="form-control" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label for="enddate" class="pl-5 pr-1 form-control-label">End Date:</label>
                                                <input type="text" id="enddate" name="enddate" placeholder="End date in dd-mm-yyyy" required="" class="form-control" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary ml-5">
													Search
												</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">Approved Ticket Lists</div>
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
																<th>QA Name</th>
																<th>Channel</th>
																<th>Draft</th>
																<th>Complexity</th>
																<th>Received Date</th>
																<th class="text-center">Delivery Date</th>
																<th class="text-center">Anno. Pages</th>
																<th class="text-center">Status</th>
																<th class="text-center">Action</th>
															</tr>
														</thead>
														<tbody>
															<?php
																if(!empty($data['lists'])){
																	$comb = ''; 
																	foreach($data['lists'] as $val){
																		$isEscalated = $val['is_escalated']=='Y' ? "<i class=\"material-icons colr-red valign-mid\">star</i>" : "";
																		//$isRejected = $val['job_status_tbl_status']=='Not Approved' ? "<i class=\"material-icons colr-red valign-mid\">star_half</i>" : "";																		
																		$comb .= '<tr><td class="text-center">'.$val['ticket_no'].' '.$isEscalated.'<br>('.$val['region'].')</td>
																				<td>'.$val['country'].'</td>
																				<td>'.$val['designer_name'].'</td>
																				<td>'.$val['qa_name'].'</td>
																				<td>'.$val['channel'].'</td>
																				<td class="text-center">'.$val['draft_no'].'</td>
																				<td>'.$val['complexity'].'</td>
																				<td>'.date('d-m-Y H:i', strtotime($val['job_received_date'])).'</td>
																				<td class="text-center">'.date('d-m-Y', strtotime($val['job_delivery_date'])).'</td>
																				<td class="text-center">'.$val['annotated_pages'].'</td>
																				<td class="text-center">'.$val['job_status'].'</td>
																				<td class="text-center"><a title="Edit" href="'.APPS_URL.'eticket/edit/approvedticket/'.$val['id'].'"><i class="material-icons">replay</i></a> <a title="Delete" href="'.APPS_URL.'eticket/delete/'.$val['id'].'" onclick="return confirm(\'Do you want to delete this ticket?\');"><i class="material-icons">delete</i></a></td></tr>';
																	}
																	echo $comb;
																}else{
																	echo "<tr><td colspan='12' class='text-center'>No data found</td></tr>";
																}
															?>
														</tbody>
													</table>
												</div>
												<div class="col-md-12">
													<ul class="list-style-none">
														<li><small><i class="material-icons colr-red valign-mid font-20">star</i> Job is escalated</small></li>
														<!--<li><small><i class="material-icons colr-red valign-mid font-20">star_half</i> Job is rejected</small></li>-->
													</ul>
												</div>
											</div>
										</div>
                                    </div> 
                                </div>
                            </div>
                        </div>
