$(document).ready(function () {


        $('.js-example-basic-multiple').select2();

	var ffl_image_path;
	var license_id_path;
	var voided_check_path;
	var upload_errored = false;
	$(document).on('submit', '#business_information_form', function (e) {
		e.preventDefault();
		$('#merchant_profile_section').removeClass('d-none');
		$('#business_information_section').addClass('d-none');
		$("#business_information_step").addClass('completed');
		$("#merchant_profile_step").addClass('active');
		$('#page-title').html('Merchant Profile');
	});

	$(document).on('submit', '#merchant_profile_form', function (e) {
		e.preventDefault();
		$('#merchant_profile_section').addClass('d-none');
		$('#banking_section').removeClass('d-none');
		$("#merchant_profile_step").addClass('completed');
		$("#banking_step").addClass('active');
		$('#page-title').html('Banking');

	});

	$(document).on('submit', '#banking_form', function (e) {
		e.preventDefault();
		$('#pricing_section').removeClass('d-none');
		$('#banking_section').addClass('d-none');
		$("#banking_step").addClass('completed');
		$("#pricing_step").addClass('active');
		$('#page-title').html('Pricing');

	});


	$(document).on('submit','#pricing_form',function (e) {
		e.preventDefault();
		var data = $('#business_information_form, #merchant_profile_form, #banking_form ,#pricing_form').serializeArray();
		var finalData = {};
		for (var i=0; i < data.length; i++){
			var value = data[i]['value'];
			var name = data[i]['name'];
			// if data already exist convert the value to array:
			if (finalData.hasOwnProperty(name)) {
				if (typeof finalData[name] === 'object') {
					value = finalData[name];
					value.push(data[i]['value']);
				}
				else {
					value = [finalData[name], data[i]['value']];
				}
			}
			finalData[name] = value;
		}
		finalData['business_months'] = [];
		for (var i=0; i < data.length; i++){
			if(data[i]['name'] === 'business_months'){
				finalData['business_months'].push(data[i]['value']);
			}
		}
		var stop = null;
		uploadAllFiles(function(){
			if(upload_errored){
				return;
			}
			finalData['ffl_image_path'] = ffl_image_path;
			finalData['license_id_path'] = license_id_path;
			finalData['voided_check_path'] = voided_check_path;
			submitData('store', finalData, function(response) {
				var stop = null;
			});
		});
	});

	function uploadAllFiles(callback){
		upload_errored = false;
		uploadFFLImage(function(){
			uploadLicenseId(function(){
				uploadVoidedCheck(function () {
					callback();
				})
			})
		});
	}
	function uploadFFLImage(callback){
		var files = $('#ffl_image').prop('files');
		if(!files.length){
			callback();
			return;
		}
		var file = files[0];
		var success = function(response){
			if(response.hasOwnProperty('result')){
				if(response.result.hasOwnProperty('ffl_image')){
					ffl_image_path = response.result.ffl_image;
				}
			}
			callback();
		};
		var error = function(response){
			upload_errored = true;
			parseErrors(response);
			callback();
		};
		uploadFile(file,'ffl_image', success, error);
	}

	function uploadLicenseId(callback){
		var files = $('#license_id').prop('files');
		if(!files.length){
			callback();
			return;
		}
		var success = function(response){
			if(response.hasOwnProperty('result')){
				if(response.result.hasOwnProperty('license_id')){
					license_id_path = response.result.license_id;
				}
			}
			callback();
		};
		var error = function(response){
			upload_errored = true;
			parseErrors(response);
			callback();
		};
		uploadFile(files[0],'license_id', success, error);
	}

	function uploadVoidedCheck(callback){
		var files = $('#voided_check').prop('files');
		if(!files.length){
			callback();
			return;
		}
		var success = function(response){
			if(response.hasOwnProperty('result')){
				if(response.result.hasOwnProperty('voided_check')){
					voided_check_path = response.result.voided_check;
				}
			}
			callback();
		};
		var error = function(response){
			upload_errored = true;
			parseErrors(response);
			callback();
		};
		uploadFile(files[0],'voided_check', success, error);
	}

	$("#upload_ffl_image").on("click", function(e){
		uploadFFLImage();
	});

	$(document).on('click','#merchant_previous_button',function(e){
		e.preventDefault();
		$('#business_information_section').removeClass('d-none');
		$('#merchant_profile_section').addClass('d-none');
		$('#page-title').html('Business Information');

	});
	$(document).on('click','#banking_previous_button',function(e){
		e.preventDefault();
		$('#merchant_profile_section').removeClass('d-none');
		$('#banking_section').addClass('d-none');
		$('#page-title').html('Merchant Profile');
	});
	$(document).on('click','#pricing_previous_button',function(e){
		e.preventDefault();
		$('#banking_section').removeClass('d-none');
		$('#pricing_section').addClass('d-none');
		$('#page-title').html('Banking');
	});

	$(document).on('change','#license_id',function(e){
		var file_name=e.target.files[0].name;
		$("#license_file_name").html(file_name);
	});
	$(document).on('change','#voided_check',function(e){
		var file_name=e.target.files[0].name;
		$("#voided_check_file_name").html(file_name);
	});

	$(document).on('click','#business_information_cancel',function(e){

		close();
	});

	$(document).on('change','.sells_firearms',function(e){
		var sells_firearms=$(this).val();
		if(sells_firearms === "true"){
			$("#ffl_number_container").removeClass('d-none');
			$("#ffl_number").prop('required',true);

			$("#ffl_image_container").removeClass('d-none');
			$("#ffl_image").prop('required',true);
		}else{
			$("#ffl_number_container").addClass('d-none');
			$("#ffl_number").removeAttr("required");

			$("#ffl_image_container").addClass('d-none');
			$("#ffl_image").removeAttr("required");
		}
	});

	$(document).on('change','.seasonal_business',function(e){
		var seasonal_business=$(this).val();
		if(seasonal_business === "true"){
			$("#business_months_container").removeClass('d-none');
			$("#business_months").prop('required',true);
		}else{
			$("#business_months_container").addClass('d-none');
			$("#business_months").removeAttr("required");
		}
	});

	$(document).on('change','.mailing_address_same',function(e){
		var mailing_address_same=$(this).val();
		if(mailing_address_same === "false"){
			$("#mailing_address_container").removeClass('d-none');
			$("#mailing_address").prop('required',true);
			$("#mailing_city").prop('required',true);
			$("#mailing_state").prop('required',true);
			$("#mailing_zip").prop('required',true);
		}else{
			$("#mailing_address_container").addClass('d-none');
			$("#mailing_address").removeAttr('required');
			$("#mailing_city").removeAttr('required');
			$("#mailing_state").removeAttr('required');
			$("#mailing_zip").removeAttr('required');
		}
	});
	$(document).on('change','.future_delivery',function(e){
		var future_delivery=$(this).val();
		if (future_delivery === "true") {
			$("#future_delivery_type_container").removeClass('d-none');
			$("#future_delivery_type").prop('required',true);

			$("#future_delivery_percentage_container").removeClass('d-none');
			$("#future_delivery_percentage").prop('required',true);

		}
		else {
			$("#future_delivery_type_container").addClass('d-none');
			$("#future_delivery_type").removeAttr('required');

			$("#future_delivery_percentage_container").addClass('d-none');
			$("#future_delivery_percentage").removeAttr('required');
		}
	});
	$(document).on('change', '.future_delivery_type', function(e){
		var val = $(this).val();
		if (val === "3") {
			$("#future_delivery_timing_container").removeClass('d-none');
			$("#future_delivery_timing").prop('required',true);
		}
		else {
			$("#future_delivery_timing_container").addClass('d-none');
			$("#future_delivery_timing").removeAttr('required');
		}
	});
	$(document).on('change','.terminated',function(e){
		var terminated=$(this).val();
		if (terminated === "true") {
			$("#termination_cause_container").removeClass('d-none');
			$("#termination_cause").prop('required',true);
		}
		else {
			$("#termination_cause_container").addClass('d-none');
			$("#termination_cause").removeAttr('required');
		}
	});

	$(document).on('change','.same_as_dba',function(e){
		var same_as_dba=$(this).val();
		if (same_as_dba === "false") {
			$("#legal_name_container").removeClass('d-none');
			$("#legal_name").prop('required',true);
		}
		else {
			$("#legal_name_container").addClass('d-none');
			$("#legal_name").removeAttr("required");
		}
	});

	$('.contact_type').on('change', function (e){
		var contact_type = $(this).val();
		var count = $(this).attr('data-count');
		var con = $(`#contact_person_additional_${count}`);
		var address_1 = con.find(`#cp_${count}_address_1`);
		var address_2 = con.find(`#cp_${count}_address_2`);
		if (contact_type === "1" || contact_type === "2" ) {
			con.removeClass('d-none');
		}
		else {
			con.addClass('d-none');
		}
	});

	function submitData(path, data, callback){
		var url = window.base_url + "/admin/on-boarding/" + path;
		var json_data = JSON.stringify(data);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: url,
			method: "POST",
			data: json_data,
			contentType: "application/json",
			dataType: "json",
			success: function (data) {
				callback(data);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				callback(jqXHR,textStatus,errorThrown);
			}
		});
	}
	function uploadFile(file, field_name, callback, error){
		var formData = new FormData();
		var url = window.base_url + "/admin/on-boarding/upload";
		formData.append(field_name, file);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				'Accept': "application/json",
			}
		});
		$.ajax({
			url: url,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: callback,
			error: error
		});
	}
	function parseErrors(response){
		var data = JSON.parse(response.responseText);
		var errorCon = $('#errors_container');
		if(typeof data === "object" && data.hasOwnProperty('message')){
			var errorEl = '<li>' + data.message + '</li>';
            $('#error_container').removeClass('d-none');
			errorCon.removeClass('d-none');
			errorCon.append(errorEl);
            $('#error_container').removeClass('d-none');
		}
		// loop through clearent errors:
		if(typeof data === "object" && data.hasOwnProperty('errors')){
			var fields = Object.keys(data.errors);
			for(var i=0; i< fields.length; i++){
				var name = fields[i];
				if(name === "clearent_errors"){
					for(var x=0; x < data.errors[name].length; x++){
						var errorEl = '<li>' + data.errors[name][x] + '</li>';
						errorCon.removeClass('d-none');
						errorCon.append(errorEl);
					}
				}
			}
		}
		// loop through laravel errors:
		if(typeof data === 'object' && data.hasOwnProperty('errors')){
			var fields = Object.keys(data.errors);
			for(var i=0; i< fields.length; i++){
				var name = fields[i];
				if(name === "clearent_errors"){
					continue;
				}
				var container = "#" + name + "_container";
				var fieldSel = "[name='" + name + "']";
				$(container).find(fieldSel).addClass('is-invalid');
				var label = $(container).find('label')[0];
				$(label).addClass('text-danger');
				for(var x=0; x < data.errors[name].length; x++){
					var helpEl = $(container).find('.onboarding-helping-text');
					helpEl.html(data.errors[name][x]);
					helpEl.removeClass('d-none');
					helpEl.addClass('text-danger');
				}
			}

			var firstFieldSel = "#" + fields[0] + "_container";
			var offset = $(firstFieldSel).offset();
			$('html, body').animate({
				scrollTop: offset.top,
				scrollLeft: offset.left
			}, 500);
		}
	}

    /*start accordion function*/

    var acc = document.getElementsByClassName("accordion-header-title");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            if(this.classList.contains("active")){
                $(this.children[0][0]).removeClass('fa-chevron-right');
                $(this.children[0][0]).addClass('fa-chevron-down');

            }else{
                $(this.children[0][0]).removeClass('fa-chevron-down');
                $(this.children[0][0]).addClass('fa-chevron-right');
            }

            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + 50 + "px";
            }
        });
    }
    /*end accordion function*/


});