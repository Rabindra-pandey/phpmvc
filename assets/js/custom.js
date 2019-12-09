// Custom javaScript

	$(document).ready(function() {
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();
		today = dd + '-' + mm + '-' + yyyy;		
		
		$('.datepicker').datepicker({
	    	format: 'dd-mm-yyyy',
			templates: {
				leftArrow: '<i class="material-icons">keyboard_arrow_left</i>',
				rightArrow: '<i class="material-icons">keyboard_arrow_right</i>'
			},
	    });

		$('.datepickersetMin').datepicker({
	    	format: 'dd-mm-yyyy',
			startDate: today,
			templates: {
				leftArrow: '<i class="material-icons">keyboard_arrow_left</i>',
				rightArrow: '<i class="material-icons">keyboard_arrow_right</i>'
			},
	    });

	    $('.datepickerformonth').datepicker({
	      	format: 'mm-yyyy',
	      	viewMode: "months", 
    		minViewMode: "months",
			templates: {
				leftArrow: '<i class="material-icons">keyboard_arrow_left</i>',
				rightArrow: '<i class="material-icons">keyboard_arrow_right</i>'
			},
	    });

		$('#startdate').datepicker({
	    	format: 'dd-mm-yyyy',
			templates: {
				leftArrow: '<i class="material-icons">keyboard_arrow_left</i>',
				rightArrow: '<i class="material-icons">keyboard_arrow_right</i>'
			},
	    }).on('changeDate', function (selected) {
			var minDate = new Date(selected.date.valueOf());
			$('#enddate').datepicker('setStartDate', minDate);
		});
		
		$("#enddate").datepicker({
			format: 'dd-mm-yyyy',
			templates: {
				leftArrow: '<i class="material-icons">keyboard_arrow_left</i>',
				rightArrow: '<i class="material-icons">keyboard_arrow_right</i>'
			},
		}).on('changeDate', function (selected) {
		  var minDate = new Date(selected.date.valueOf());
		  $('#startdate').datepicker('setEndDate', minDate);
		});

	    bs_input_file();

		var availableCountry = ["Global","Region \/ Cluster \/ Hub","US","CANADA","AUSTRALIA","FRANCE","New Zealand","UK","SPAIN","GERMANY","ITALY","Albania","Armenia","Austria","Azerbaijan","Bangladesh","Belgium","Luxemburg","Bosnia and Herzegovina","Bulgaria","Cambodia","China","Croatia","Cyprus","Czech Republic","Denmark","Estonia","Georgia","Greece","Hong Kong","Hungary","Iceland","Ireland","Israel","Japan","Korea","Latvia","Lithuania","Macedonia","Malta","Gibraltar","Montenegro","Moldova","Netherlands","Norway","Poland","Portugal","Romania","Serbia","Singapore","Slovakia","Slovenia","Sweden","Switzerland","Taiwan","Uzbekistan","Venezuela","Finland","India","Laos","Myanmar","Paraguay","Bolivia","Brazil","Colombia","Mexico","Argentina","Uruguay","Chile","Ecuador","PERU","COSTA RICA","DOMINICAN REPUBLIC","EL SALVADOR","GUATEMALA","HONDURAS","JAMAICA","NICARAGUA","PANAMA","Russia","Turkey","United Arab Emirates","BAHRAIN","KUWAIT","OMAN","QATAR","EGYPT","MOROCCO","TUNISIA","UKRAINE","Belarus","Kazakhstan","ALGERIA","Jordan","Kenya","Lebanon","Nigeria","PAKISTAN","SAUDI ARABIA","Sri Lanka","Trinidad and Tobago","Iran","South Africa","Ghana","Ivory Coast","Indonesia","Malaysia","Philippines","Thailand","Vietnam","Aruba","Curacao","Guyana","Haiti","Suriname","Benin\u00a0","Botswana\u00a0","Cameroon","Democratic Republic of the Congo","Gabon\u00a0","Guinea\u00a0","Madagascar\u00a0","Malawi\u00a0","Mozambique\u00a0","Namibia\u00a0","Republic of the Congo\u00a0","Rwanda","Senegal\u00a0","Tanzania\u00a0","Togo","Uganda","Zambia","Papua New Guinea","Nordics Cluster","Macau"];

		$("#country_name").autocomplete({
			source: availableCountry
		});
		
		$('#ticketlist').DataTable({
			"pageLength": 10,
			"ordering": false,
			"drawCallback": function () {
				$('.dataTables_paginate > .pagination li').addClass('page-item');
				$('.dataTables_paginate > .pagination li a').addClass('page-link');
			}
		});
		
		$('button[aria-label="Close"]').on('click', function(){
			var url = window.location.href;
			url = url.split('?')[0];
			window.history.pushState({path:url},'',url);		
		})
	});

	/***
		Documents ready end here
	**/

	$('#quarter').on('change', function(){
		var quarter = $('#quarter').val();
		var fromdate = $('#fromdate').val();
		var todate = $('#todate').val();

		if(quarter!=''){
			$('#fromdate, #todate').attr('disabled', 'disabled');
		}else{
			$('#fromdate, #todate').removeAttr('disabled');
		}
	});

	$('#is_escalated').click(function(){
		if($(this).prop('checked')){
			$('#escalateComment').slideDown();
			$('#escalateComment').removeClass('hide');			
		}else{
			$('#escalateComment').slideUp();
			$('#escalation_type').removeClass('error');
		}
	});

	function checkValidation(){
		var quarter = $('#quarter').val();
		var fromdate = $('#fromdate').val();
		var todate = $('#todate').val();
		var region = $('#region').val();
		var channel = $('#channel').val();
		var isDisabledFrom = $('#fromdate').prop('disabled');	
		var isDisabledEnd = $('#todate').prop('disabled');	

		if(region!='' || channel!=''){
			if(quarter=='' && (isDisabledFrom==true && isDisabledEnd==true)){			
				alert('Please select the quartely list or start and end date');
				return false;
			}else if(isDisabledFrom==false && isDisabledEnd==false){
				if(todate=='' && fromdate==''){	
					alert('Please select the quarter list or enter the start and end date');
					return false;
				}
			}
		}

		return true;
	}

	function checkingFieldValueBlank(formData, fldName, selectBox=null){
		var index = formData.findIndex(item => item.name == fldName);		
		
		if(selectBox!=null){
			if(formData[index].name==fldName && formData[index].value==""){				
				$('[name="'+formData[index].name+'"]').addClass('error');
			}else{
				$('[name="'+formData[index].name+'"]').removeClass('error');
			}
		}else{
			if(formData[index].name==fldName && formData[index].value==""){
				$('[name="'+formData[index].name+'"]').addClass('error');
			}else{
				$('[name="'+formData[index].name+'"]').removeClass('error');
			}
		}
	} 

	function checkValidationOfEticket(){
		var formData = $('form#eticketform').serializeArray();
		
		checkingFieldValueBlank(formData, "eticket[ticket_no]");
		checkingFieldValueBlank(formData, "eticket[country]");
		checkingFieldValueBlank(formData, "eticket[designer_id]", "select");
		checkingFieldValueBlank(formData, "eticket[qa_id]", "select");
		checkingFieldValueBlank(formData, "eticket[region]");
		checkingFieldValueBlank(formData, "eticket[channel]");
		checkingFieldValueBlank(formData, "eticket[job_type]", "select");
		checkingFieldValueBlank(formData, "eticket[draft_no]", "select");
		checkingFieldValueBlank(formData, "eticket[complexity]", "select");
		checkingFieldValueBlank(formData, "eticket[total_pages]");
		checkingFieldValueBlank(formData, "eticket[annotated_pages]");
		checkingFieldValueBlank(formData, "eticket[job_status]");
		checkingFieldValueBlank(formData, "eticket[job_delivery_date]");
		
		var ticketno = $('#ticket_no').val();
		var jobstatus = $('#status').val();
		var draftno = $('#draft_no').val();
		var editedId = $('#editedId').val();
		var rejectedId = $('#rejectedId').val();
		if(ticketno!='' && jobstatus!='' && draftno!='' && editedId==''){
			checkTicketStatus(ticketno, jobstatus, draftno);
		}

		if($("#is_escalated").prop('checked')){
			checkingFieldValueBlank(formData, "eticket[escalation_type]");
		}	
		var checkTrue = true;
		if(rejectedId!=''){
			if(jobstatus!='Recheck'){
				alert("Please select status as 'Recheck'.");
				checkTrue = false;
			}
		}
		$('form#eticketform input').each(function(){
			if($(this).hasClass('error')){
				checkTrue = false;
			}
		});
		$('form#eticketform select').each(function(){
			if($(this).hasClass('error')){
				checkTrue = false;
			}
		});
		
		if(checkTrue==false){
			return false;
		}else{
			return true;
		}
	}

	function checkUser(){
		var checkTrue = true;
		if($('#empid').val()==''){				
			$('#empid').addClass('error');
			checkTrue = false;
		}else{
			$('#empid').removeClass('error');
		}
		if($('#password').val()==''){				
			$('#password').addClass('error');
			checkTrue = false;
		}else{
			$('#password').removeClass('error');
		}
		if($('#user_type').val()==''){				
			$('#user_type').addClass('error');
			checkTrue = false;
		}else{
			$('#user_type').removeClass('error');
		}
		
		if(checkTrue===true){
			return true;
		}
		return false;
	}

	function checkTicketStatus(ticketno, jobstatus, draftno){
		var getUrl = $('#app_url').val();		
		$.post(getUrl+'eticket/checkingTicketStatus', {ticketno: ticketno, jobstatus: jobstatus, draftno: draftno}, function(res){
			if(res!=false){
				var obj = JSON.parse(res);
				$('#ticket_no, #draft_no, #status').addClass('error');
				var errorDiv = '<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show"><span class="badge badge-pill badge-danger">Error</span> Given ticket no. already exists with \''+obj.job_status.trim()+'\' status and draft no. '+obj.draft_no.trim()+' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>';
				$('#error-display').html(errorDiv);
			}else{
				$('#error-display').children().remove();
				$('#ticket_no, #draft_no, #status').removeClass('error');
			}
		});
	}

	function getSelectedText(id, putInto){
		var getSelectedTxt = $('#'+id+' :selected').text();
		$('#'+putInto).val(getSelectedTxt);
	}

	function bs_input_file() {
		$(".input-file").before(
			function() {
				if ( ! $(this).prev().hasClass('input-ghost') ) {
					var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
					element.attr("name", $(this).attr("name"));
					element.change(function(){
						element.next(element).find('input').val((element.val()).split('\\').pop());
					});
					$(this).find("button.btn-choose").click(function(){
						element.click();
					});
					$(this).find("button.btn-reset").click(function(){
						element.val(null);
						$(this).parents(".input-file").find('input').val('');
					});
					$(this).find('input').css("cursor","pointer");
					$(this).find('input').mousedown(function() {
						$(this).parents('.input-file').prev().click();
						return false;
					});
					return element;
				}
			}
		);
	}

	function populatemodal(idx, ticketno, jobstatus){
		if(idx){
			var getStatus = $('#jobstatus').val();	
			var splitstatus = getStatus.split(',');
			//var opts = '';
			var opts = splitstatus.reduce((opt, item)=>{
				var selected = item==jobstatus ? 'selected' : '';
				if(item=='Open' || item=='WIP' || item=='Approved' || item=='Not Approved'){
					opt += '<option value="'+item+'" '+selected+'>'+item+'</option>';
				}
				return opt;
			}, '');
			var htmlelem = `<input type="hidden" id="tickInfoId" name="tickInfoId" value="${idx}">
							<input type="hidden" id="hiddenstatus" name="hiddenstatus" value="${jobstatus}">
							<div class="row form-group">
								<div class="col col-md-3">
									<label for="ticket_no" class=" form-control-label text-right">Ticket No.</label>
								</div>
								<div class="col-12 col-md-9">
									<input type="text" id="ticket_no" name="ticket_no" value="${ticketno}" class="form-control" readonly>
								</div>
							</div>
							<div class="row form-group">
								<div class="col col-md-3">
									<label for="job_status" class=" form-control-label text-right">Change Status</label>
								</div>
								<div class="col-12 col-md-9">
									<select name="job_status" id="job_status" class="form-control">
										<option value="">Select status</option>
										${opts}
									</select>
								</div>
							</div>
							<div class="row form-group">
								<div class="col col-md-3">
									<label for="comments" class="form-control-label text-right">Comments</label>
								</div>
								<div class="col-12 col-md-9">
									<textarea name="comments" id="comments" rows="3" placeholder="Enter the comments here..." class="form-control"></textarea>
								</div>
							</div>`;
			
			$("#mediumModalLabel").text('Change job status');
			$("#mediumModal .modal-body").html(htmlelem);
    		$("#mediumModal").modal('toggle');
		}else{
			alert('Please select any row');
		}
	}

	function changeStatus(){
		var hiddenstatus = $('#hiddenstatus').val();
		var tickInfoId = $('#tickInfoId').val();
		var jobstatus = $('#job_status').val();
		var comments = $('#comments').val();
		if(tickInfoId){
			var url = $('#apps_url').val();
			$.post(url+'qaticket/changestatus',{idx:tickInfoId, jobstatus:jobstatus, comments:comments, hiddenstatus:hiddenstatus},function(res){
				if(res==true){
					window.location.href = url+'qaticket/?succ=Data has been updated successfully.';
				}else{
					window.location.href = url+'qaticket/?error=Something is wrong. Please try again.';
				}
			});
		}else{
			alert('Please relaod the page and try again.');
		}
	}

	function showhideqcpagecount(chann){
		if(chann.toLowerCase()=='ipad'){
			$('.qcpagecount').slideDown();
		}else{
			$('.qcpagecount').slideUp();
		}
	}

	function getComplexity(cval){
		var complexity = {
						'print': ['C1 - Scratch', 'C2 - Localization/Adaptation', 'C3 - Drafts'], 
						'health': ['C1 - Interactive/Resources/Filters', 'C2 - Video/Events/IHS Hosting', 'C3 - Textual/New Page Creation/Deeplinks', 'C4 - Drafts/Push To Live', 'C5 - CRM Tagging/Tag Creation/Data Layer'],
						'emailer': ['C1 - New Creation', 'C2 - Localization/Adaptation', 'C3 - Drafts/GSK Vault/Sign-Off', 'C4 - Expire Content'],
						'ipad': ['C1 - Adaptation', 'C2 - Localization', 'C3 - Int.PDF/Drafts/Sign-Off', 'C4 - Static PDF/Video', 'C5 - Agency QC', 'C6 - Expire Content']
					};
		var getcval = cval.toLowerCase();
		var opts = complexity[getcval].reduce((opt, item)=>{
				opt += '<option value="'+item+'">'+item+'</option>';
				return opt;
			}, '<option value="">Select complexity</option>');
		
		$('#complexity').html(opts);
		$("#complexity").selectpicker("refresh");
	}




		
	
	
