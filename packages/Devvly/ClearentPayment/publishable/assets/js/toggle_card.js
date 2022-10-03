/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 6);
/******/ })
/************************************************************************/
/******/ ({

/***/ 6:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(7);


/***/ }),

/***/ 7:
/***/ (function(module, exports) {

var savedCardSelectedId, radioID, savedCardSelectedCard;
var next_step = "#checkout-payment-continue-button";
var alt_next_step = "#checkout-place-order-button";
var next_step_btn_sel;
var next_btn_found;
var should_wait = false;
// listen to payment section added:
var settings = {};
eventBus.$on('after-checkout-payment-section-added', function () {
	$(document).ready(function () {
		settings = {
			settings_path: "/clearent/settings",
			form_selector: "form#clearent_payment_form",
			submit_button: "#submit_btn",
			jwt_token_field: 'input[name="jwt_token"]',
			card_type_field: 'input[name="card_type"]',
			last_four_field: 'input[name="last_four"]',
			submit_callback: submitCallback
		};
		includeClearent(settings);
	});
});

// listen to payment method change event:
eventBus.$on('after-payment-method-selected', function () {
	$(document).ready(function () {
		setNextStepBtn(function () {
			var val = $('input[name="payment[method]"]:checked').val();
			if (val === 'clearent') {
				showForm();
			} else {
				hideForm();
			}
		});
	});
});
function setNextStepBtn(callback) {
	if (next_btn_found) {
		if (should_wait) {
			setTimeout(function () {
				callback();
			}, 3500);
		} else {
			callback();
		}
		return;
	}
	var interval = setInterval(function () {
		var next_step_btn = $(next_step);
		if (next_step_btn.length) {
			clearInterval(interval);
			next_step_btn_sel = next_step;
			next_btn_found = true;
			should_wait = false;
			callback();
		} else {
			next_step_btn = $(alt_next_step);
			if (next_step_btn.length) {
				clearInterval(interval);
				next_step_btn_sel = alt_next_step;
				next_btn_found = true;
				setTimeout(function () {
					callback();
				}, 1500);
			}
		}
	}, 100);
}
function showForm() {
	$('.clearent-add-card').css('display', 'block');
	$(next_step_btn_sel).attr('disabled', 'disabled');
	$('.clearent-cards-block').css('display', 'block');
	if ($('.clearent-card-info > .radio-container > input[type="radio"]').is(':checked')) {
		radioID = $('.clearent-card-info > .radio-container > input[type="radio"]:checked').attr('id');
		savedCardSelectedId = radioID;
		savedCardSelectedCard = true;
		$(next_step_btn_sel).removeAttr('disabled', 'disabled');
	}
}
function hideForm() {
	$('.clearent-add-card').css('display', 'none');
	$(next_step_btn_sel).removeAttr('disabled', 'disabled');
	$('.clearent-cards-block').css('display', 'none');
}

/**
 * Payment form submit callback.
 */
function submitCallback() {
	var arr = $(settings.form_selector).serializeArray();
	var formData = {};
	for (var i = 0; i < arr.length; i++) {
		formData[arr[i].name] = arr[i].value;
	}
	if (formData.hasOwnProperty('save')) {
		formData.save = formData.save === "true" || formData.save === "on";
	} else {
		formData.save = false;
	}
	var token = $('meta[name="csrf-token"]').attr('content');
	var url = $(settings.form_selector).attr('action');
	var method = $(settings.form_selector).attr('method');
	var request = $.ajax({
		type: method,
		url: url,
		contentType: "application/json",
		data: JSON.stringify(formData),
		headers: {
			'X-CSRF-TOKEN': token
		}
	});
	request.done(function (data, textStatus, jqXHR) {
		successCallback(data, textStatus, jqXHR);
		$(settings.submit_button).attr('disabled', false);
	});
	request.fail(function (jqXHR, textStatus, errorThrown) {
		errorCallback(jqXHR, textStatus, errorThrown);
		$(settings.submit_button).attr('disabled', false);
	});
}

/**
 * Payment form submit success callback.
 *
 * @param data
 * @param textStatus
 * @param jqXHR
 */
function successCallback(data, textStatus, jqXHR) {
	$('#error_con').css('display: none');
	var el = " \n\t\t\t<div id=\"1\" class=\"clearent-card-info\">\n\t\t\t\t<label class=\"radio-container\">\n\t\t\t\t\t<input type=\"radio\" name=\"saved-card\" id=\"" + data.id + "\"  value=\"" + data.id + "\" class=\"saved-card-list\">\n\t\t\t\t\t<span class=\"checkmark\"></span>\n\t\t\t\t</label>\n\t\t\t\t<span class=\"icon currency-card-icon\"></span>\n\t\t\t\t<span class=\"card-last-four\" style=\"margin-left: 16px;\">************" + data.last_four + "</span>\n\t\t\t</div>";
	var saved_cards = $('#saved-cards .control-info');
	if (saved_cards.length) {
		saved_cards.append(el);
	} else {
		$('#customer_card .control-info').append(el);
	}
	$('#clearentModal').css('display', 'none');
	$(next_step_btn_sel).removeAttr('disabled', 'disabled');
	$('input#' + data.id).prop('checked', 'checked').trigger('click');
}

/**
 * Payment form submit error callback.
 *
 * @param jqXHR
 * @param textStatus
 * @param errorThrown
 */
function errorCallback(jqXHR, textStatus, errorThrown) {
	var errorsEl = "";
	var json = jqXHR.responseJSON;
	var status;
	var statusCode = jqXHR.status;
	if (json.hasOwnProperty('message') && statusCode === 422) {
		status = "<li><b>" + json.message + "</b></li>";
	} else if (errorThrown && statusCode === 500) {
		status = "<li><b>" + errorThrown + "</b></li>";
	}
	errorsEl += status;

	if (json.hasOwnProperty('errors')) {
		var fields = Object.keys(json.errors);
		for (var i = 0; i < fields.length; i++) {
			var field = fields[i];
			for (var x = 0; x < json.errors[field].length; x++) {
				var errText = "<li>" + json.errors[field] + "</li>";
				errorsEl += errText;
			}
		}
	}
	$('#error_con').html(errorsEl);
	$('#error_con').css('display: block');
}

/***/ })

/******/ });