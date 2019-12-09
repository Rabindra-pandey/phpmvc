						<?php
							$channvalues = [];
							$channtexts = [];

							/*$regChannValues = [];
							$regChannTexts = [];*/

							$recAllocValues = [];
							$recAllocTexts = [];

							if(count($data['channelCounts'])>0){
								foreach($data['channelCounts'] as $key=>$val){
									$channvalues[] = $val['channelcount'];
									$channtexts[] = $val['channel'];
								}
							}

							foreach($data['resAllocVsAsgTkt'] as $key=>$val){
								$recAllocTexts[] = ucwords(str_replace('_', ' ', $key));										
								$recAllocValues[] = $val;
							}
								
								
						?>					

					
						<div class="row">
                       		<div class="col-lg-6">
                                <div class="au-card m-b-30">
                                    <div class="au-card-inner">
                                        <h3 class="title-2 m-b-40">Channel wise jobs percentage</h3>
                                        <div class="mypiechart">	
											<canvas id="myPieCanvas" width="300" height="300"></canvas>
										</div>
                                    </div>
                                </div>
                            </div>
                       		<div class="col-lg-6">
                                <div class="au-card m-b-30">
                                    <div class="au-card-inner">
                                        <h3 class="title-2 m-b-40">Resource Allocation with Respect to Assigned Ticket</h3>
                                        <div class="mypiechart">	
											<canvas id="myPieCanvas2" width="300" height="300"></canvas>
										</div>
                                    </div>
                                </div>
                            </div>
                       		<div class="col-lg-12">
                                <div class="au-card m-b-30">
                                    <div class="au-card-inner">
                                        <h3 class="title-2 m-b-40">Region &amp; channel wise jobs count graph</h3>
                                        <div class="mybarchart">	
											<canvas id="myBarCanvas" width="700" height="500"></canvas>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
					
						<script type="application/javascript">
							
							var pieval = <?php echo json_encode($channvalues) ?>;
							var pieText = <?php echo json_encode($channtexts) ?>;
							
							var pieval2 = <?php echo json_encode($recAllocValues) ?>;
							var pieText2 = <?php echo json_encode($recAllocTexts) ?>;
							
							var peidatalength = pieval.length;
							var piecolr = ['#5c5fff', '#bb48e6', '#f12bc3', '#ff1f9c', '#ff3c75', '#ff6250', '#ff862d', '#ffa600', '#4CAF75', '#ff7354', '#ff8044'];
							
							pieval = pieval.map((item)=>{
								return parseInt(item, 10);
							});
							
							piecolr = piecolr.filter((item, idx)=>{
								return idx<peidatalength ? item : '';
							});
							
							var peidatalength2 = pieval2.length;
							pieval2 = pieval2.map((item)=>{
								return parseInt(item, 10);
							});
							
							piecolr2 = piecolr.filter((item, idx)=>{
								return idx<peidatalength2 ? item : '';
							});
							
							var obj = {
										rpieid: 'myPieCanvas',
										values: pieval,
										colors: piecolr,
										animation: true,
										animationSpeed: 30,
										fillTextData: true,
										fillTexts: pieText,
										fillTextColor: '#fff',
										fillTextAlign: 1.30,
										fillTextPosition: 'inner',
										doughnutHoleSize: 50, 
										doughnutHoleColor: '#fff', 
										offset: 1
									  };  
							
							var obj2 = {
										rpieid: 'myPieCanvas2',
										values: pieval2,
										colors: piecolr2,
										animation: true,
										animationSpeed: 30,
										fillTextData: true,
										fillTexts: pieText2,
										fillTextColor: '#fff',
										fillTextAlign: 1.30,
										fillTextPosition: 'inner',
										doughnutHoleSize: 50, 
										doughnutHoleColor: '#fff', 
										offset: 1
									  }; 							
							
							generatePieGraph(obj);	 
							generatePieGraph(obj2);	
							
							var barval = <?php echo json_encode($data['regChannCounts']) ?>;
							var mybarData = [];
							for(var i=0; i<Object.keys(barval).length; i++){
								var obj = {};
								obj[Object.keys(barval)[i]] = getIntegerVal(Object.values(barval)[i]);
								mybarData.push(obj);
							}
							
							function getIntegerVal(obj){
								var finalObj = {};
								for(ob in obj){
									finalObj[ob] = parseInt(obj[ob], 10);
								}
								return finalObj;
							}
							
							var barcolor = ['#5c5fff', '#bb48e6', '#f12bc3', '#ff1f9c', '#ff3c75', '#ff6250', '#ff862d', '#ffa600', '#4CAF75', '#ff7354', '#ff8044', '#ff6250', '#ff862d', '#ffa600', '#4CAF75', '#ff7354', '#ff8044', '#bb48e6', '#f12bc3', '#ff1f9c', '#ff3c75', '#ff6250', '#ff862d', '#ffa600', '#4CAF75', '#ff7354', '#ff8044', '#ff6250', '#ff862d', '#ffa600', '#4CAF75', '#ff7354', '#ff8044'];
							
							var barObj = {
										barId: 'myBarCanvas',
										barData: mybarData,
										barColour: barcolor,
										barStroke: 40,
										barSpaces: 100,
										barOuterPadding: 80,
										barXAxisTextRotate: 20,
										barCanvasWidth: 600,
										barCanvasHeight: 500,
										barGroupParentName: true										
									};
							
							generateBarGraph(barObj);
						</script>
