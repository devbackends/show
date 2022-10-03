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
/******/ 	return __webpack_require__(__webpack_require__.s = 8);
/******/ })
/************************************************************************/
/******/ ({

/***/ 8:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(9);


/***/ }),

/***/ 9:
/***/ (function(module, exports) {

window.includeClearent = function (settings) {
	var api_settings = {
		base_url: null,
		public_key: null
	};
	initForm();
	function initForm() {
		if (!api_settings.base_url || !api_settings.public_key) {
			getApiSettings(function () {
				if (!api_settings.base_url || !api_settings.public_key) {
					console.error('Could not initialize clearent api');
					return;
				}
				doInit();
			});
		}
		var doInit = function doInit() {
			ClearentSDK.init({
				"baseUrl": api_settings.base_url,
				"pk": api_settings.public_key,
				"successCallback": "clearentSuccessCallback",
				"errorCallback": "clearentErrorCallback"
			});
			var submit_btn = $(settings.submit_button);
			submit_btn.on('click', function (event) {
				event.preventDefault();
				// disable the submit button to prevent the user submitting multiple times:
				submit_btn.attr("disabled", true);
				// get the token:
				ClearentSDK.getPaymentToken();
			});
		};
	}
	function getApiSettings(callback) {
		var token = $('meta[name="csrf-token"]').attr('content');
		var url = window.location.origin + settings.settings_path;
		var request = $.ajax({
			url: url,
			headers: {
				'X-CSRF-TOKEN': token
			}
		});
		request.done(function (data) {
			api_settings.base_url = data.url;
			api_settings.public_key = data.public_key;
			callback();
		});
	}
	window.clearentSuccessCallback = function (raw, jwt) {
		if (jwt.status === 'success') {
			var token_field = $(settings.jwt_token_field);
			var card_type = $(settings.card_type_field);
			var last_four = $(settings.last_four_field);
			token_field.val(jwt.payload['mobile-jwt']['jwt']);
			card_type.val(jwt.payload['mobile-jwt']['card-type']);
			last_four.val(jwt.payload['mobile-jwt']['last-four']);
			if (settings.hasOwnProperty('submit_callback')) {
				settings.submit_callback();
			} else {
				$(settings.form_selector).submit();
			}
		} else {
			$(settings.submit_button).attr('disabled', false);
		}
	};
	window.clearentErrorCallback = function (raw, jwt) {
		console.error(jwt.payload.error['error-message']);
		$(settings.submit_button).attr('disabled', false);
	};
};

/***/ })

/******/ });