window.includeClearent = function(settings){
	var api_settings = {
		base_url: null,
		public_key: null,
	};
	initForm();
	function initForm(){
		if(!api_settings.base_url || !api_settings.public_key){
			getApiSettings(() => {
				if(!api_settings.base_url || !api_settings.public_key){
					console.error('Could not initialize clearent api');
					return;
				}
				doInit();
			});
		}
		var doInit = () => {
			ClearentSDK.init({
				"baseUrl": api_settings.base_url,
				"pk": api_settings.public_key,
				"successCallback": "clearentSuccessCallback",
				"errorCallback": "clearentErrorCallback"
			});
			var submit_btn = $(settings.submit_button);
			submit_btn.on('click', (event)=>{
				event.preventDefault();
				// disable the submit button to prevent the user submitting multiple times:
				submit_btn.attr("disabled", true);
				// get the token:
				ClearentSDK.getPaymentToken();
			});
		}
	}
	function getApiSettings(callback){
		var token = $('meta[name="csrf-token"]').attr('content');
		var url =  window.location.origin + settings.settings_path;
		var request = $.ajax({
			url: url,
			headers: {
				'X-CSRF-TOKEN': token,
			},
		});
		request.done((data) => {
			api_settings.base_url = data.url;
			api_settings.public_key = data.public_key;
			callback();
		})
	}
	window.clearentSuccessCallback = function (raw,jwt) {
		if(jwt.status === 'success'){
			var token_field = $(settings.jwt_token_field);
			var card_type = $(settings.card_type_field);
			var last_four = $(settings.last_four_field);
			token_field.val(jwt.payload['mobile-jwt']['jwt']);
			card_type.val(jwt.payload['mobile-jwt']['card-type']);
			last_four.val(jwt.payload['mobile-jwt']['last-four']);
			$(settings.form_selector).submit();
		}else{
			$(settings.submit_button).attr('disabled', false);
		}
	};
	window.clearentErrorCallback = function (raw,jwt) {
		console.error(jwt.payload.error['error-message']);
		$(settings.submit_button).attr('disabled', false);
	};
};