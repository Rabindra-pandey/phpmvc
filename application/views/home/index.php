

						<div class="row">
							<div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Report List</div>
                                    <div class="card-body">
                                        <form action="<?= APPS_URL.'home/getplidata'; ?>" method="post" enctype="multipart/form-data" onsubmit="return checkValidation()">
                                            <div class="row">
                                            	<div class="col-lg-3">
		                                            <div class="form-group">
		                                                <label for="quarter" class="control-label mb-1">Choose Quarter</label>
		                                                <select name="quarter" id="quarter" class="form-control">
		                                                    <option value="">----Select option----</option>
															<?php 
																$jsonDecode = json_decode($data['quarterlyDate']);
																$strings = '';
																$curTag = false;
																$prevTag = false;
																for ($i=0; $i < count($jsonDecode); $i++) { 
																	if($jsonDecode[$i]->year=='Current Year'){ 								
																		if($curTag==false) { $strings .= "<optgroup label='Current Year'>"; $curTag = true;}
																		$strings .= "<option value='".$jsonDecode[$i]->date."'>".$jsonDecode[$i]->date."</option>";
																	}else{
																		if($prevTag==false) { $strings .= "<optgroup label='Previous Year'>"; $prevTag = true;}
																		$strings .= "<option value='".$jsonDecode[$i]->date."'>".$jsonDecode[$i]->date."</option>";
																	}
																}	
																echo $strings;				
															?>	
		                                                </select>
		                                            </div>
		                                        </div>
		                                        <div class="col-lg-1 text-center">
		                                        	<div class="form-group">
		                                        		<label class="control-label mb-1">&nbsp;</label>
		                                        		<h2 class="mb-4">OR</h2>
		                                        	</div>
		                                        </div>
                                            	<div class="col-lg-3">
                                            		<div class="form-group">
		                                                <label for="fromdate" class="control-label mb-1">Start Date</label>
		                                                <input autocomplete="off" id="fromdate" name="fromdate" class="datepickerformonth form-control" placeholder="mm-yyyy" data-date-format="mm-yyyy" type="text">
		                                            </div>
                                            	</div> 
                                            	<div class="col-lg-3">
                                            		<div class="form-group">
		                                                <label for="todate" class="control-label mb-1">End Date</label>
		                                                <input autocomplete="off" id="todate" name="todate" class="datepickerformonth form-control" placeholder="mm-yyyy" data-date-format="mm-yyyy" type="text">
		                                            </div>
                                            	</div>                                            	
                                            </div>
                                            <div class="row">
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
                                                    Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        	if(!empty($data['pktdata'])){
                        ?>
                        <div class="row">
                        	<div class="col-lg-12">
                                <div class="table-responsive table--no-card m-b-30">
                                    <table class="table table-borderless table-striped table-earning">
                                    	<thead>
											<tr>
												<th class="align-middle" width="15%">User Info</th>
												<th class="align-middle" width="25%">PKT Data</th>
												<th class="align-middle" width="15%">PKT Eligible Scores</th>
												<th class="align-middle" width="30%">Attendance Data</th>
												<th class="align-middle" width="15%">Attendance Qualify Scores</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												$pktPercentageData = 10;
												$attendancePercentageData = 5;

												foreach ($data['pktdata'] as $key => $value) {
													$percentages = explode('|', $value['percentages']);
													$completed_dates = explode('|', $value['completed_dates']);
													$countPerLength = count($percentages);
													$perString = '';
													$rowSpan = '';
													$quarterly_percentage = ($value['pktcnt'] > 2) ? $value['total_percentage']/$value['pktcnt'] : $value['total_percentage']/3;									
													$pkteligibleScore = number_format(ceil($quarterly_percentage) >=90 ? $pktPercentageData : $quarterly_percentage/$pktPercentageData, 2, '.', '');

													$attendanceReport = '';
													$ulData = 0;
													if(!empty($data['plidata'][$value['empid']])){
														foreach ($data['plidata'][$value['empid']] as $ky => $val) {
															$attendanceReport .= $ky=='uul' ? '<strong>Unapporve Unschedule Leave: </strong>'.$data['plidata'][$value['empid']]['uul'] : '';
															$attendanceReport .= $ky=='ul' ? '<br><strong>Unapproved Leave: </strong>'.$data['plidata'][$value['empid']]['ul'] : '';
															if($ky=='ul'){
																$ulData = $data['plidata'][$value['empid']]['ul'];
															}
														}
													}
													if($countPerLength>1){
														$rowSpan = 'rowspan="'.$countPerLength.'"';
														for($j=1; $j<$countPerLength; $j++){
															$perString .= '<tr><td><strong>Date:</strong> '.date('M, Y', strtotime($completed_dates[$j])).'<br><strong>Score:</strong> '.ceil($percentages[$j]).'% </tr>';
														}									
													}

													$dayCnt = $data['num_of_days'] - ($data['num_sat_and_sun'] + $data['num_of_holiday']);
													$attendancePerc = number_format((($dayCnt - $ulData)/$dayCnt)*$attendancePercentageData, 2, '.', '');

													//echo $perString;
													echo '<tr><td class="align-middle" '.$rowSpan.'><strong>'.$value['emp_name'].'</strong> ('.$value['empid'].')<br><strong>Region: </strong> '.$value['region'].'<br><strong>Channel: </strong> '.$value['channel'].'</td><td><strong>Date:</strong> '.date('M, Y', strtotime($completed_dates[0])).'<br><strong>Score:</strong> '.ceil($percentages[0]).'%</td><td class="align-middle" '.$rowSpan.'>'.$pkteligibleScore.'% </td><td class="align-middle" '.$rowSpan.'>'.$attendanceReport.'</td><td class="align-middle" '.$rowSpan.'>'.$attendancePerc.'%</td></tr>'.$perString;
												}
												/*number_format($value['total_percentage'], 2, '.', '')*/							
											?>						
										</tbody>
                                    </table>
                                </div>
                            </div>
                        <?php if(isset($data['pagi']) && $data['setPagi']==true){ ?>
							<div class="col-lg-12">
								<nav aria-label="Page navigation example">
									<?= $data['pagi']; ?>
								</nav>
							</div>
						<?php } ?>
                        </div>
                    <?php } ?>
