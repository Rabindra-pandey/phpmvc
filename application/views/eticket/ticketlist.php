
						<div class="row">
							<div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Ticket Lists in QA Bucket</div>
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
																<!--<th>Region</th>-->
																<th>Channel</th>
																<th>Draft</th>
																<th>Complexity</th>
																<th>Received Date</th>
																<th>Delivery Dt.</th>
																<th class="text-center">Anno.</th>
																<th class="text-center">Intl. Ann.</th>
																<th class="text-center">Status</th>
																<th class="text-center">Action</th>
															</tr>
														</thead>
														<tbody>
															<?php 
																/*<td>'.$val['region'].'</td>*/
																if(!empty($data['lists'])){
																	$comb = ''; 
																	foreach($data['lists'] as $val){
																		$comb .= '<tr><td class="text-center">'.$val['ticket_no'].'<br>('.$val['region'].')</td>
																				<td>'.$val['country'].'</td>
																				<td>'.$val['designer_name'].'</td>
																				<td>'.$val['qa_name'].'</td>
																				<td>'.$val['channel'].'</td>
																				<td class="text-center">'.$val['draft_no'].'</td>
																				<td>'.$val['complexity'].'</td>
																				<td>'.date('d-m-Y H:i', strtotime($val['job_received_date'])).'</td>
																				<td>'.date('d-m-Y', strtotime($val['job_delivery_date'])).'</td>
																				<td class="text-center">'.$val['annotated_pages'].'</td>
																				<td class="text-center">'.$val['qc_page_count'].'</td>
																				<td class="text-center">'.$val['job_status'].'</td>
																				<td class="text-center"><a title="Edit" href="'.APPS_URL.'eticket/edit/'.$val['id'].'"><i class="material-icons">replay</i></a> <a title="Delete" href="'.APPS_URL.'eticket/delete/'.$val['id'].'" onclick="return confirm(\'Do you want to delete this ticket?\');"><i class="material-icons">delete</i></a></td></tr>';
																	}
																	echo $comb;
																}else{
																	echo "<tr><td colspan='12' class='text-center'>No data found</td></tr>";
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
