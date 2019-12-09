
						<div class="row">
							<div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">New ticket from resource allocation tool</div>
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
																<th>Designer Name</th>
																<th class="text-center">Region</th>
																<th class="text-center">Channel</th>
																<th>Received Date</th>
																<th class="text-center">Action</th>
															</tr>
														</thead>
														<tbody>
															<?php
																if(!empty($data['lists'])){
																	$comb = ''; 
																	foreach($data['lists'] as $val){
																		$comb .= '<tr><td>'.$val['eform_ticket'].'</td>
																				<td>'.$val['emp_name'].'</td>
																				<td class="text-center">'.$val['region'].'</td>
																				<td class="text-center">'.$val['channel'].'</td>
																				<td>'.date('d-m-Y H:i', $val['end_date']).'</td>
																				<td class="text-center"><a href="'.APPS_URL.'eticket/edit/newticketentry/'.$val['resource_allocation_id'].'" title="Edit"><i class="material-icons">replay</i></a> <a href="'.APPS_URL.'eticket/qareject/'.$val['resource_allocation_id'].'" onclick="return confirm(\'Do you want to delete this ticket?\');" title="Delete"><i class="material-icons">delete</i></a></td></tr>';
																	}
																	echo $comb;
																}else{
																	echo "<tr><td colspan='9' class='text-center'>No data found</td></tr>";
																}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
                                    </div> 
                                </div>
                            </div>
                        </div>
