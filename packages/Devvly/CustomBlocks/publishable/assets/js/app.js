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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/Resources/assets/sass/app.scss":
/*!********************************************!*\
  !*** ./src/Resources/assets/sass/app.scss ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./src/resources/assets/js/app.js":
/*!****************************************!*\
  !*** ./src/resources/assets/js/app.js ***!
  \****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _bootstrap_blocks_container__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./bootstrap-blocks/container */ "./src/resources/assets/js/bootstrap-blocks/container/index.js");
/* harmony import */ var _bootstrap_blocks_column__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./bootstrap-blocks/column */ "./src/resources/assets/js/bootstrap-blocks/column/index.js");
/* harmony import */ var _bootstrap_blocks_row__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./bootstrap-blocks/row */ "./src/resources/assets/js/bootstrap-blocks/row/index.js");



Laraberg.registerCategory('Bootstrap Blocks', 'wp-bootstrap-blocks');
Laraberg.registerBlock('wp-bootstrap-blocks/container', _bootstrap_blocks_container__WEBPACK_IMPORTED_MODULE_0__["default"]);
Laraberg.registerBlock('wp-bootstrap-blocks/ccolumn', _bootstrap_blocks_column__WEBPACK_IMPORTED_MODULE_1__["default"]);
Laraberg.registerBlock('wp-bootstrap-blocks/row', _bootstrap_blocks_row__WEBPACK_IMPORTED_MODULE_2__["default"]);

/***/ }),

/***/ "./src/resources/assets/js/bootstrap-blocks/column/edit.js":
/*!*****************************************************************!*\
  !*** ./src/resources/assets/js/bootstrap-blocks/column/edit.js ***!
  \*****************************************************************/
/*! exports provided: bgColorOptions, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "bgColorOptions", function() { return bgColorOptions; });
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _objectWithoutProperties(source, excluded) { if (source == null) return {}; var target = _objectWithoutPropertiesLoose(source, excluded); var key, i; if (Object.getOwnPropertySymbols) { var sourceSymbolKeys = Object.getOwnPropertySymbols(source); for (i = 0; i < sourceSymbolKeys.length; i++) { key = sourceSymbolKeys[i]; if (excluded.indexOf(key) >= 0) continue; if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue; target[key] = source[key]; } } return target; }

function _objectWithoutPropertiesLoose(source, excluded) { if (source == null) return {}; var target = {}; var sourceKeys = Object.keys(source); var key, i; for (i = 0; i < sourceKeys.length; i++) { key = sourceKeys[i]; if (excluded.indexOf(key) >= 0) continue; target[key] = source[key]; } return target; }

/**
 * WordPress dependencies
 */
var __ = wp.i18n.__;

var _ref = wp.blockEditor || wp.editor,
    InnerBlocks = _ref.InnerBlocks,
    InspectorControls = _ref.InspectorControls; // Fallback to 'wp.editor' for backwards compatibility


var _wp$components = wp.components,
    CheckboxControl = _wp$components.CheckboxControl,
    ColorPalette = _wp$components.ColorPalette,
    PanelBody = _wp$components.PanelBody,
    RangeControl = _wp$components.RangeControl,
    SelectControl = _wp$components.SelectControl;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment;
var withSelect = wp.data.withSelect;
var applyFilters = wp.hooks.applyFilters;
var compose = wp.compose.compose;

var ColumnSizeRangeControl = function ColumnSizeRangeControl(_ref2) {
  var label = _ref2.label,
      attributeName = _ref2.attributeName,
      value = _ref2.value,
      setAttributes = _ref2.setAttributes,
      props = _objectWithoutProperties(_ref2, ["label", "attributeName", "value", "setAttributes"]);

  return /*#__PURE__*/React.createElement(RangeControl, _extends({
    label: label,
    value: value,
    onChange: function onChange(selectedSize) {
      setAttributes(_defineProperty({}, attributeName, selectedSize));
    },
    min: 0,
    max: 12
  }, props));
};

var bgColorOptions = [{
  name: 'primary',
  color: '#007bff'
}, {
  name: 'secondary',
  color: '#6c757d'
}];
bgColorOptions = applyFilters('wpBootstrapBlocks.column.bgColorOptions', bgColorOptions);
var paddingOptions = [{
  label: __('None', 'wp-bootstrap-blocks'),
  value: ''
}, {
  label: __('Small', 'wp-bootstrap-blocks'),
  value: 'p-2'
}, {
  label: __('Medium', 'wp-bootstrap-blocks'),
  value: 'p-3'
}, {
  label: __('Large', 'wp-bootstrap-blocks'),
  value: 'p-5'
}];
paddingOptions = applyFilters('wpBootstrapBlocks.column.paddingOptions', paddingOptions);

var BootstrapColumnEdit = /*#__PURE__*/function (_Component) {
  _inherits(BootstrapColumnEdit, _Component);

  var _super = _createSuper(BootstrapColumnEdit);

  function BootstrapColumnEdit() {
    _classCallCheck(this, BootstrapColumnEdit);

    return _super.apply(this, arguments);
  }

  _createClass(BootstrapColumnEdit, [{
    key: "render",
    value: function render() {
      console.log('props:', this.props);
      var _this$props = this.props,
          attributes = _this$props.attributes,
          className = _this$props.className,
          setAttributes = _this$props.setAttributes,
          hasChildBlocks = _this$props.hasChildBlocks;
      var sizeXl = attributes.sizeXl,
          sizeLg = attributes.sizeLg,
          sizeMd = attributes.sizeMd,
          sizeSm = attributes.sizeSm,
          sizeXs = attributes.sizeXs,
          equalWidthXl = attributes.equalWidthXl,
          equalWidthLg = attributes.equalWidthLg,
          equalWidthMd = attributes.equalWidthMd,
          equalWidthSm = attributes.equalWidthSm,
          equalWidthXs = attributes.equalWidthXs,
          bgColor = attributes.bgColor,
          padding = attributes.padding,
          centerContent = attributes.centerContent; // If centerContent is enabled but no background-color is selected -> reset attribute

      if (!bgColor && centerContent) {
        setAttributes({
          centerContent: false
        });
      }

      return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
        title: __('Column size', 'wp-bootstrap-blocks'),
        initialOpen: false
      }, /*#__PURE__*/React.createElement(ColumnSizeRangeControl, {
        label: __('Xs Column count', 'wp-bootstrap-blocks'),
        attributeName: "sizeXs",
        value: sizeXs,
        disabled: equalWidthXs,
        setAttributes: setAttributes
      }), /*#__PURE__*/React.createElement(CheckboxControl, {
        label: __('Xs equal-width', 'wp-bootstrap-blocks'),
        checked: equalWidthXs,
        onChange: function onChange(isChecked) {
          return setAttributes({
            equalWidthXs: isChecked
          });
        }
      }), /*#__PURE__*/React.createElement("hr", null), /*#__PURE__*/React.createElement(ColumnSizeRangeControl, {
        label: __('Sm Column count', 'wp-bootstrap-blocks'),
        attributeName: "sizeSm",
        value: sizeSm,
        disabled: equalWidthSm,
        setAttributes: setAttributes
      }), /*#__PURE__*/React.createElement(CheckboxControl, {
        label: __('Sm equal-width', 'wp-bootstrap-blocks'),
        checked: equalWidthSm,
        onChange: function onChange(isChecked) {
          return setAttributes({
            equalWidthSm: isChecked
          });
        }
      }), /*#__PURE__*/React.createElement("hr", null), /*#__PURE__*/React.createElement(ColumnSizeRangeControl, {
        label: __('Md Column count', 'wp-bootstrap-blocks'),
        attributeName: "sizeMd",
        value: sizeMd,
        disabled: equalWidthMd,
        setAttributes: setAttributes
      }), /*#__PURE__*/React.createElement(CheckboxControl, {
        label: __('Md equal-width', 'wp-bootstrap-blocks'),
        checked: equalWidthMd,
        onChange: function onChange(isChecked) {
          return setAttributes({
            equalWidthMd: isChecked
          });
        }
      }), /*#__PURE__*/React.createElement("hr", null), /*#__PURE__*/React.createElement(ColumnSizeRangeControl, {
        label: __('Lg Column count', 'wp-bootstrap-blocks'),
        attributeName: "sizeLg",
        value: sizeLg,
        disabled: equalWidthLg,
        setAttributes: setAttributes
      }), /*#__PURE__*/React.createElement(CheckboxControl, {
        label: __('Lg equal-width', 'wp-bootstrap-blocks'),
        checked: equalWidthLg,
        onChange: function onChange(isChecked) {
          return setAttributes({
            equalWidthLg: isChecked
          });
        }
      }), /*#__PURE__*/React.createElement("hr", null), /*#__PURE__*/React.createElement(ColumnSizeRangeControl, {
        label: __('Xl Column count', 'wp-bootstrap-blocks'),
        attributeName: "sizeXl",
        value: sizeXl,
        disabled: equalWidthXl,
        setAttributes: setAttributes
      }), /*#__PURE__*/React.createElement(CheckboxControl, {
        label: __('Xl equal-width', 'wp-bootstrap-blocks'),
        checked: equalWidthXl,
        onChange: function onChange(isChecked) {
          return setAttributes({
            equalWidthXl: isChecked
          });
        }
      })), /*#__PURE__*/React.createElement(PanelBody, {
        title: __('Background color', 'wp-bootstrap-blocks'),
        initialOpen: false
      }, /*#__PURE__*/React.createElement(ColorPalette, {
        colors: bgColorOptions,
        value: bgColor,
        onChange: function onChange(value) {
          // Value is undefined if color gets cleared
          if (!value) {
            setAttributes({
              bgColor: '',
              centerContent: false
            });
          } else {
            var selectedColor = bgColorOptions.find(function (c) {
              return c.color === value;
            });

            if (selectedColor) {
              setAttributes({
                bgColor: selectedColor.name
              });
            }
          }
        },
        disableCustomColors: true
      }), bgColor ? /*#__PURE__*/React.createElement(CheckboxControl, {
        label: __('Center content vertically in row', 'wp-bootstrap-blocks'),
        checked: centerContent,
        onChange: function onChange(isChecked) {
          return setAttributes({
            centerContent: isChecked
          });
        },
        help: __('This setting only applies if there is no vertical alignment set on the parent row block.', 'wp-bootstrap-blocks')
      }) : null), /*#__PURE__*/React.createElement(PanelBody, {
        title: __('Padding (inside column)', 'wp-bootstrap-blocks'),
        initialOpen: false
      }, /*#__PURE__*/React.createElement(SelectControl, {
        label: __('Size', 'wp-bootstrap-blocks'),
        value: padding,
        options: paddingOptions,
        onChange: function onChange(value) {
          setAttributes({
            padding: value
          });
        }
      }))), /*#__PURE__*/React.createElement("div", {
        className: className
      }, /*#__PURE__*/React.createElement(InnerBlocks, {
        templateLock: false,
        renderAppender: hasChildBlocks ? undefined : function () {
          return /*#__PURE__*/React.createElement(InnerBlocks.ButtonBlockAppender, null);
        }
      })));
    }
  }]);

  return BootstrapColumnEdit;
}(Component);

/* harmony default export */ __webpack_exports__["default"] = (compose(withSelect(function (select, ownProps) {
  var clientId = ownProps.clientId;

  var _ref3 = select('core/block-editor') || select('core/editor'),
      getBlockOrder = _ref3.getBlockOrder; // Fallback to 'core/editor' for backwards compatibility


  return {
    hasChildBlocks: getBlockOrder(clientId).length > 0
  };
}))(BootstrapColumnEdit));

/***/ }),

/***/ "./src/resources/assets/js/bootstrap-blocks/column/index.js":
/*!******************************************************************!*\
  !*** ./src/resources/assets/js/bootstrap-blocks/column/index.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/resources/assets/js/bootstrap-blocks/column/edit.js");


var _ref = wp.blockEditor || wp.editor,
    InnerBlocks = _ref.InnerBlocks; // Fallback to 'wp.editor' for backwards compatibility


var Column = {
  title: 'CColumn',
  icon: 'menu',
  category: 'wp-bootstrap-blocks',
  parent: ['wp-bootstrap-blocks/row'],
  // attributes are defined server side with register_block_type(). This is needed to make default attributes available in the blocks render callback.
  getEditWrapperProps: function getEditWrapperProps(attributes) {
    var sizeXl = attributes.sizeXl,
        sizeLg = attributes.sizeLg,
        sizeMd = attributes.sizeMd,
        sizeSm = attributes.sizeSm,
        sizeXs = attributes.sizeXs,
        equalWidthXl = attributes.equalWidthXl,
        equalWidthLg = attributes.equalWidthLg,
        equalWidthMd = attributes.equalWidthMd,
        equalWidthSm = attributes.equalWidthSm,
        equalWidthXs = attributes.equalWidthXs,
        bgColor = attributes.bgColor,
        padding = attributes.padding,
        centerContent = attributes.centerContent; // Prepare styles for selected background-color

    var style = {};

    if (bgColor) {
      var selectedBgColor = _edit__WEBPACK_IMPORTED_MODULE_0__["bgColorOptions"].find(function (bgColorOption) {
        return bgColorOption.name === bgColor;
      });

      if (selectedBgColor) {
        style = {
          backgroundColor: selectedBgColor.color
        };
      }
    }

    return {
      'data-size-xs': equalWidthXl || equalWidthLg || equalWidthMd || equalWidthSm || equalWidthXs ? 0 : sizeXs,
      'data-size-sm': equalWidthXl || equalWidthLg || equalWidthMd || equalWidthSm ? 0 : sizeSm,
      'data-size-md': equalWidthXl || equalWidthLg || equalWidthMd ? 0 : sizeMd,
      'data-size-lg': equalWidthXl || equalWidthLg ? 0 : sizeLg,
      'data-size-xl': equalWidthXl ? 0 : sizeXl,
      'data-bg-color': bgColor,
      'data-padding': padding,
      'data-center-content': centerContent,
      style: style
    };
  },
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: function save() {
    return /*#__PURE__*/React.createElement(InnerBlocks.Content, null);
  }
};
/* harmony default export */ __webpack_exports__["default"] = (Column);

/***/ }),

/***/ "./src/resources/assets/js/bootstrap-blocks/container/edit.js":
/*!********************************************************************!*\
  !*** ./src/resources/assets/js/bootstrap-blocks/container/edit.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

/**
 * WordPress dependencies
 */
var __ = wp.i18n.__;

var _ref = wp.blockEditor || wp.editor,
    InnerBlocks = _ref.InnerBlocks,
    InspectorControls = _ref.InspectorControls; // Fallback to 'wp.editor' for backwards compatibility


var _wp$components = wp.components,
    CheckboxControl = _wp$components.CheckboxControl,
    PanelBody = _wp$components.PanelBody,
    SelectControl = _wp$components.SelectControl;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment;
var withSelect = wp.data.withSelect;
var compose = wp.compose.compose;
var applyFilters = wp.hooks.applyFilters;
var marginAfterOptions = [{
  label: __('Small', 'wp-bootstrap-blocks'),
  value: 'mb-2'
}, {
  label: __('Medium', 'wp-bootstrap-blocks'),
  value: 'mb-3'
}, {
  label: __('Large', 'wp-bootstrap-blocks'),
  value: 'mb-5'
}];
marginAfterOptions = applyFilters('wpBootstrapBlocks.container.marginAfterOptions', marginAfterOptions);
marginAfterOptions = [{
  label: __('None', 'wp-bootstrap-blocks'),
  value: 'mb-0'
}].concat(_toConsumableArray(marginAfterOptions));
var fluidBreakpointOptions = [{
  label: __('No breakpoint selected', 'wp-bootstrap-blocks'),
  value: ''
}, {
  label: __('Xl', 'wp-bootstrap-blocks'),
  value: 'xl'
}, {
  label: __('Lg', 'wp-bootstrap-blocks'),
  value: 'lg'
}, {
  label: __('Md', 'wp-bootstrap-blocks'),
  value: 'md'
}, {
  label: __('Sm', 'wp-bootstrap-blocks'),
  value: 'sm'
}];

var BootstrapContainerEdit = /*#__PURE__*/function (_Component) {
  _inherits(BootstrapContainerEdit, _Component);

  var _super = _createSuper(BootstrapContainerEdit);

  function BootstrapContainerEdit() {
    _classCallCheck(this, BootstrapContainerEdit);

    return _super.apply(this, arguments);
  }

  _createClass(BootstrapContainerEdit, [{
    key: "render",
    value: function render() {
      var _this$props = this.props,
          attributes = _this$props.attributes,
          className = _this$props.className,
          setAttributes = _this$props.setAttributes,
          hasChildBlocks = _this$props.hasChildBlocks;
      var isFluid = attributes.isFluid,
          fluidBreakpoint = attributes.fluidBreakpoint,
          marginAfter = attributes.marginAfter;
      return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
        title: __('Fluid', 'wp-bootstrap-blocks')
      }, /*#__PURE__*/React.createElement(CheckboxControl, {
        label: __('Fluid', 'wp-bootstrap-blocks'),
        checked: isFluid,
        onChange: function onChange(isChecked) {
          setAttributes({
            isFluid: isChecked
          });
        }
      }), /*#__PURE__*/React.createElement(SelectControl, {
        label: __('Fluid Breakpoint', 'wp-bootstrap-blocks'),
        disabled: !isFluid,
        value: fluidBreakpoint,
        options: fluidBreakpointOptions,
        onChange: function onChange(selectedFluidBreakpoint) {
          setAttributes({
            fluidBreakpoint: selectedFluidBreakpoint
          });
        },
        help: __('Fluid breakpoints only work with Bootstrap v4.4+. The container will be 100% wide until the specified breakpoint is reached, after which max-widths for each of the higher breakpoints will be applied.', 'wp-bootstrap-blocks')
      })), /*#__PURE__*/React.createElement(PanelBody, {
        title: __('Margin', 'wp-bootstrap-blocks')
      }, /*#__PURE__*/React.createElement(SelectControl, {
        label: __('Margin After', 'wp-bootstrap-blocks'),
        value: marginAfter,
        options: marginAfterOptions,
        onChange: function onChange(selectedMarginAfter) {
          setAttributes({
            marginAfter: selectedMarginAfter
          });
        }
      }))), /*#__PURE__*/React.createElement("div", {
        className: className
      }, /*#__PURE__*/React.createElement(InnerBlocks, {
        renderAppender: hasChildBlocks ? undefined : function () {
          return /*#__PURE__*/React.createElement(InnerBlocks.ButtonBlockAppender, null);
        }
      })));
    }
  }]);

  return BootstrapContainerEdit;
}(Component);

/* harmony default export */ __webpack_exports__["default"] = (compose(withSelect(function (select, ownProps) {
  var clientId = ownProps.clientId;

  var _ref2 = select('core/block-editor') || select('core/editor'),
      getBlockOrder = _ref2.getBlockOrder; // Fallback to 'core/editor' for backwards compatibility


  return {
    hasChildBlocks: getBlockOrder(clientId).length > 0
  };
}))(BootstrapContainerEdit));

/***/ }),

/***/ "./src/resources/assets/js/bootstrap-blocks/container/index.js":
/*!*********************************************************************!*\
  !*** ./src/resources/assets/js/bootstrap-blocks/container/index.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/resources/assets/js/bootstrap-blocks/container/edit.js");
/**
 * BLOCK: wp-bootstrap-blocks/container
 */


var _ref = wp.blockEditor || wp.editor,
    InnerBlocks = _ref.InnerBlocks; // Fallback to 'wp.editor' for backwards compatibility


var Container = {
  title: 'Container',
  icon: 'feedback',
  category: 'wp-bootstrap-blocks',
  supports: {
    align: false
  },
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: function save() {
    return /*#__PURE__*/React.createElement(InnerBlocks.Content, null);
  }
};
/* harmony default export */ __webpack_exports__["default"] = (Container);

/***/ }),

/***/ "./src/resources/assets/js/bootstrap-blocks/row/edit.js":
/*!**************************************************************!*\
  !*** ./src/resources/assets/js/bootstrap-blocks/row/edit.js ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./icons */ "./src/resources/assets/js/bootstrap-blocks/row/icons.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }


var __ = wp.i18n.__;

var _ref = wp.blockEditor || wp.editor,
    InnerBlocks = _ref.InnerBlocks,
    InspectorControls = _ref.InspectorControls,
    BlockControls = _ref.BlockControls,
    AlignmentToolbar = _ref.AlignmentToolbar; // Fallback to 'wp.editor' for backwards compatibility


var _wp$components = wp.components,
    IconButton = _wp$components.IconButton,
    CheckboxControl = _wp$components.CheckboxControl,
    PanelBody = _wp$components.PanelBody,
    SVG = _wp$components.SVG,
    Path = _wp$components.Path;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment;
var _wp$data = wp.data,
    withSelect = _wp$data.withSelect,
    withDispatch = _wp$data.withDispatch;
var applyFilters = wp.hooks.applyFilters;
var compose = wp.compose.compose;
var ALLOWED_BLOCKS = ['wp-bootstrap-blocks/column'];

var addMissingTemplateIcons = function addMissingTemplateIcons(templates) {
  return templates.map(function (template) {
    return _objectSpread({
      icon: _icons__WEBPACK_IMPORTED_MODULE_0__["templateIconMissing"]
    }, template);
  });
};

var templates = [{
  name: '1-1',
  title: __('2 Columns (1:1)', 'wp-bootstrap-blocks'),
  icon: /*#__PURE__*/React.createElement(SVG, {
    width: "48",
    height: "48",
    viewBox: "0 0 48 48",
    xmlns: "http://www.w3.org/2000/svg"
  }, /*#__PURE__*/React.createElement(Path, {
    fillRule: "evenodd",
    clipRule: "evenodd",
    d: "M39 12C40.1046 12 41 12.8954 41 14V34C41 35.1046 40.1046 36 39 36H9C7.89543 36 7 35.1046 7 34V14C7 12.8954 7.89543 12 9 12H39ZM39 34V14H25V34H39ZM23 34H9V14H23V34Z"
  })),
  templateLock: 'all',
  template: [['wp-bootstrap-blocks/column', {
    sizeMd: 6
  }], ['wp-bootstrap-blocks/column', {
    sizeMd: 6
  }]]
}, {
  name: '1-2',
  title: __('2 Columns (1:2)', 'wp-bootstrap-blocks'),
  icon: /*#__PURE__*/React.createElement(SVG, {
    width: "48",
    height: "48",
    viewBox: "0 0 48 48",
    xmlns: "http://www.w3.org/2000/svg"
  }, /*#__PURE__*/React.createElement(Path, {
    fillRule: "evenodd",
    clipRule: "evenodd",
    d: "M39 12C40.1046 12 41 12.8954 41 14V34C41 35.1046 40.1046 36 39 36H9C7.89543 36 7 35.1046 7 34V14C7 12.8954 7.89543 12 9 12H39ZM39 34V14H20V34H39ZM18 34H9V14H18V34Z"
  })),
  templateLock: 'all',
  template: [['wp-bootstrap-blocks/column', {
    sizeMd: 4
  }], ['wp-bootstrap-blocks/column', {
    sizeMd: 8
  }]]
}, {
  name: '2-1',
  title: __('2 Columns (2:1)', 'wp-bootstrap-blocks'),
  icon: /*#__PURE__*/React.createElement(SVG, {
    width: "48",
    height: "48",
    viewBox: "0 0 48 48",
    xmlns: "http://www.w3.org/2000/svg"
  }, /*#__PURE__*/React.createElement(Path, {
    fillRule: "evenodd",
    clipRule: "evenodd",
    d: "M39 12C40.1046 12 41 12.8954 41 14V34C41 35.1046 40.1046 36 39 36H9C7.89543 36 7 35.1046 7 34V14C7 12.8954 7.89543 12 9 12H39ZM39 34V14H30V34H39ZM28 34H9V14H28V34Z"
  })),
  templateLock: 'all',
  template: [['wp-bootstrap-blocks/column', {
    sizeMd: 8
  }], ['wp-bootstrap-blocks/column', {
    sizeMd: 4
  }]]
}, {
  name: '1-1-1',
  title: __('3 Columns (1:1:1)', 'wp-bootstrap-blocks'),
  icon: /*#__PURE__*/React.createElement(SVG, {
    width: "48",
    height: "48",
    viewBox: "0 0 48 48",
    xmlns: "http://www.w3.org/2000/svg"
  }, /*#__PURE__*/React.createElement(Path, {
    fillRule: "evenodd",
    d: "M41 14a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v20a2 2 0 0 0 2 2h30a2 2 0 0 0 2-2V14zM28.5 34h-9V14h9v20zm2 0V14H39v20h-8.5zm-13 0H9V14h8.5v20z"
  })),
  templateLock: 'all',
  template: [['wp-bootstrap-blocks/column', {
    sizeMd: 4
  }], ['wp-bootstrap-blocks/column', {
    sizeMd: 4
  }], ['wp-bootstrap-blocks/column', {
    sizeMd: 4
  }]]
}];
templates = applyFilters('wpBootstrapBlocks.row.templates', templates);
templates = addMissingTemplateIcons(templates);
var enableCustomTemplate = applyFilters('wpBootstrapBlocks.row.enableCustomTemplate', true);

if (enableCustomTemplate) {
  templates.push({
    name: 'custom',
    title: __('Custom', 'wp-bootstrap-blocks'),
    icon: _icons__WEBPACK_IMPORTED_MODULE_0__["templateIconMissing"],
    templateLock: false,
    template: [['wp-bootstrap-blocks/column']]
  });
}

var getColumnsTemplate = function getColumnsTemplate(templateName) {
  var template = templates.find(function (t) {
    return t.name === templateName;
  });
  return template ? template.template : [];
};

var getColumnsTemplateLock = function getColumnsTemplateLock(templateName) {
  var template = templates.find(function (t) {
    return t.name === templateName;
  });
  return template ? template.templateLock : false;
};

var BootstrapRowEdit = /*#__PURE__*/function (_Component) {
  _inherits(BootstrapRowEdit, _Component);

  var _super = _createSuper(BootstrapRowEdit);

  function BootstrapRowEdit() {
    _classCallCheck(this, BootstrapRowEdit);

    return _super.apply(this, arguments);
  }

  _createClass(BootstrapRowEdit, [{
    key: "render",
    value: function render() {
      var _this$props = this.props,
          className = _this$props.className,
          attributes = _this$props.attributes,
          setAttributes = _this$props.setAttributes,
          columns = _this$props.columns,
          updateBlockAttributes = _this$props.updateBlockAttributes;
      var selectedTemplateName = attributes.template,
          noGutters = attributes.noGutters,
          alignment = attributes.alignment,
          verticalAlignment = attributes.verticalAlignment,
          editorStackColumns = attributes.editorStackColumns;

      var onTemplateChange = function onTemplateChange(newSelectedTemplateName) {
        var template = templates.find(function (t) {
          return t.name === newSelectedTemplateName;
        });

        if (template) {
          // Update sizes to fit with selected template
          columns.forEach(function (column, index) {
            if (template.template.length > index) {
              var newAttributes = template.template[index][1];
              updateBlockAttributes(column.clientId, newAttributes);
            }
          });
          setAttributes({
            template: newSelectedTemplateName
          });
        }
      };

      var alignmentControls = [{
        icon: 'editor-alignleft',
        title: __('Align columns left', 'wp-bootstrap-blocks'),
        align: 'left'
      }, {
        icon: 'editor-aligncenter',
        title: __('Align columns center', 'wp-bootstrap-blocks'),
        align: 'center'
      }, {
        icon: 'editor-alignright',
        title: __('Align columns right', 'wp-bootstrap-blocks'),
        align: 'right'
      }];
      var verticalAlignmentControls = [{
        icon: _icons__WEBPACK_IMPORTED_MODULE_0__["alignTop"],
        title: __('Align columns top', 'wp-bootstrap-blocks'),
        align: 'top'
      }, {
        icon: _icons__WEBPACK_IMPORTED_MODULE_0__["alignCenter"],
        title: __('Align columns center', 'wp-bootstrap-blocks'),
        align: 'center'
      }, {
        icon: _icons__WEBPACK_IMPORTED_MODULE_0__["alignBottom"],
        title: __('Align columns bottom', 'wp-bootstrap-blocks'),
        align: 'bottom'
      }];
      return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, null, /*#__PURE__*/React.createElement(CheckboxControl, {
        label: __('Editor: Display columns stacked', 'wp-bootstrap-blocks'),
        description: __("Displays stacked columns in editor to enhance readability of block content. This option is only used in the editor and won't affect the output of the row.", 'wp-bootstrap-blocks'),
        checked: editorStackColumns,
        onChange: function onChange(isChecked) {
          return setAttributes({
            editorStackColumns: isChecked
          });
        }
      })), /*#__PURE__*/React.createElement(PanelBody, {
        title: __('Change layout', 'wp-bootstrap-blocks')
      }, /*#__PURE__*/React.createElement("ul", {
        className: "wp-bootstrap-blocks-template-selector-list"
      }, templates.map(function (template, index // eslint-disable-line no-shadow
      ) {
        return (
          /*#__PURE__*/
          React.createElement("li", {
            className: "wp-bootstrap-blocks-template-selector-button",
            key: index
          }, /*#__PURE__*/React.createElement(IconButton, {
            label: template.title,
            icon: template.icon,
            onClick: function onClick() {
              onTemplateChange(template.name);
            },
            className: selectedTemplateName === template.name ? 'is-active' : null
          }, /*#__PURE__*/React.createElement("div", {
            className: "wp-bootstrap-blocks-template-selector-button-label"
          }, template.title)))
        );
      }))), /*#__PURE__*/React.createElement(PanelBody, {
        title: __('Row options', 'wp-bootstrap-blocks')
      }, /*#__PURE__*/React.createElement(CheckboxControl, {
        label: __('No Gutters', 'wp-bootstrap-blocks'),
        checked: noGutters,
        onChange: function onChange(isChecked) {
          return setAttributes({
            noGutters: isChecked
          });
        }
      }))), /*#__PURE__*/React.createElement(BlockControls, null, /*#__PURE__*/React.createElement(AlignmentToolbar, {
        value: alignment,
        label: __('Change horizontal alignment of columns', 'wp-bootstrap-blocks'),
        onChange: function onChange(newAlignment) {
          return setAttributes({
            alignment: newAlignment
          });
        },
        alignmentControls: alignmentControls
      }), /*#__PURE__*/React.createElement(AlignmentToolbar, {
        value: verticalAlignment,
        label: __('Change vertical alignment of columns', 'wp-bootstrap-blocks'),
        onChange: function onChange(newVerticalAlignment) {
          return setAttributes({
            verticalAlignment: newVerticalAlignment
          });
        },
        alignmentControls: verticalAlignmentControls
      })), /*#__PURE__*/React.createElement("div", {
        className: className
      }, /*#__PURE__*/React.createElement(InnerBlocks, {
        allowedBlocks: ALLOWED_BLOCKS,
        template: getColumnsTemplate(selectedTemplateName),
        templateLock: getColumnsTemplateLock(selectedTemplateName)
      })));
    }
  }]);

  return BootstrapRowEdit;
}(Component);

var applyWithSelect = withSelect(function (select, _ref2) {
  var clientId = _ref2.clientId;

  var _ref3 = select('core/block-editor') || select('core/editor'),
      getBlocksByClientId = _ref3.getBlocksByClientId; // Fallback to 'core/editor' for backwards compatibility


  var columns = getBlocksByClientId(clientId)[0] ? getBlocksByClientId(clientId)[0].innerBlocks : [];
  return {
    columns: columns
  };
});
var applyWithDispatch = withDispatch(function (dispatch) {
  var _ref4 = dispatch('core/block-editor') || dispatch('core/editor'),
      updateBlockAttributes = _ref4.updateBlockAttributes; // Fallback to 'core/editor' for backwards compatibility


  return {
    updateBlockAttributes: updateBlockAttributes
  };
});
/* harmony default export */ __webpack_exports__["default"] = (compose(applyWithSelect, applyWithDispatch)(BootstrapRowEdit));

/***/ }),

/***/ "./src/resources/assets/js/bootstrap-blocks/row/icons.js":
/*!***************************************************************!*\
  !*** ./src/resources/assets/js/bootstrap-blocks/row/icons.js ***!
  \***************************************************************/
/*! exports provided: alignBottom, alignCenter, alignTop, templateIconMissing */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "alignBottom", function() { return alignBottom; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "alignCenter", function() { return alignCenter; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "alignTop", function() { return alignTop; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "templateIconMissing", function() { return templateIconMissing; });
/**
 * Backport from Gutenberg 5.5
 * Source: https://github.com/WordPress/gutenberg/blob/master/packages/block-editor/src/components/block-vertical-alignment-toolbar/icons.js
 */
var _wp$components = wp.components,
    Path = _wp$components.Path,
    SVG = _wp$components.SVG;
var alignBottom = /*#__PURE__*/React.createElement(SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  width: "20",
  height: "20",
  viewBox: "0 0 24 24"
}, /*#__PURE__*/React.createElement(Path, {
  fill: "none",
  d: "M0 0h24v24H0V0z"
}), /*#__PURE__*/React.createElement(Path, {
  d: "M16 13h-3V3h-2v10H8l4 4 4-4zM4 19v2h16v-2H4z"
}));
var alignCenter = /*#__PURE__*/React.createElement(SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  width: "20",
  height: "20",
  viewBox: "0 0 24 24"
}, /*#__PURE__*/React.createElement(Path, {
  fill: "none",
  d: "M0 0h24v24H0V0z"
}), /*#__PURE__*/React.createElement(Path, {
  d: "M8 19h3v4h2v-4h3l-4-4-4 4zm8-14h-3V1h-2v4H8l4 4 4-4zM4 11v2h16v-2H4z"
}));
var alignTop = /*#__PURE__*/React.createElement(SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  width: "20",
  height: "20",
  viewBox: "0 0 24 24"
}, /*#__PURE__*/React.createElement(Path, {
  fill: "none",
  d: "M0 0h24v24H0V0z"
}), /*#__PURE__*/React.createElement(Path, {
  d: "M8 11h3v10h2V11h3l-4-4-4 4zM4 3v2h16V3H4z"
}));
var templateIconMissing = /*#__PURE__*/React.createElement(SVG, {
  width: "48",
  height: "48",
  viewBox: "0 0 48 48",
  xmlns: "http://www.w3.org/2000/svg"
}, /*#__PURE__*/React.createElement(Path, {
  fillRule: "evenodd",
  clipRule: "evenodd",
  d: "M23.58 26.28c0-.600003.1499985-1.099998.45-1.5.3000015-.400002.7433304-.8399976 1.33-1.32.5600028-.4533356.9833319-.8699981 1.27-1.25s.43-.8433306.43-1.39c0-.5466694-.1733316-1.0566643-.52-1.53s-.986662-.71-1.92-.71c-1.1066722 0-1.8533314.2766639-2.24.83-.3866686.5533361-.58 1.1766632-.58 1.87 0 .1466674.0033333.2666662.01.36.0066667.0933338.01.1533332.01.18h-1.78c-.0133334-.0533336-.0266666-.146666-.04-.28-.0133334-.133334-.02-.2733326-.02-.42 0-.7733372.1766649-1.4666636.53-2.08.3533351-.6133364.8899964-1.0999982 1.61-1.46.7200036-.3600018 1.5999948-.54 2.64-.54 1.2133394 0 2.2033295.3233301 2.97.97s1.15 1.5099946 1.15 2.59c0 .7066702-.1033323 1.3033309-.31 1.79-.2066677.4866691-.4533319.8799985-.74 1.18-.2866681.3000015-.6566644.6233316-1.11.97-.4800024.3866686-.8333322.7166653-1.06.99-.2266678.2733347-.34.6233312-.34 1.05v.82h-1.74zm-.14 2.56h2V31h-2zM39 12c1.1046 0 2 .8954 2 2v20c0 1.1046-.8954 2-2 2H9c-1.10457 0-2-.8954-2-2V14c0-1.1046.89543-2 2-2h30zm0 22V14H9v20h30z"
}));

/***/ }),

/***/ "./src/resources/assets/js/bootstrap-blocks/row/index.js":
/*!***************************************************************!*\
  !*** ./src/resources/assets/js/bootstrap-blocks/row/index.js ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/resources/assets/js/bootstrap-blocks/row/edit.js");
/**
 * BLOCK: wp-bootstrap-blocks/row
 */


var _ref = wp.blockEditor || wp.editor,
    InnerBlocks = _ref.InnerBlocks; // Fallback to 'wp.editor' for backwards compatibility


var Row = {
  title: "Row",
  icon: 'layout',
  category: 'wp-bootstrap-blocks',
  supports: {
    align: ['full']
  },
  // attributes are defined server side with register_block_type(). This is needed to make default attributes available in the blocks render callback.
  getEditWrapperProps: function getEditWrapperProps(attributes) {
    return {
      'data-alignment': attributes.alignment,
      'data-vertical-alignment': attributes.verticalAlignment,
      'data-editor-stack-columns': attributes.editorStackColumns
    };
  },
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: function save() {
    return /*#__PURE__*/React.createElement(InnerBlocks.Content, null);
  }
};
/* harmony default export */ __webpack_exports__["default"] = (Row);

/***/ }),

/***/ 0:
/*!***********************************************************************************!*\
  !*** multi ./src/resources/assets/js/app.js ./src/Resources/assets/sass/app.scss ***!
  \***********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! C:\Users\funct\Documents\dev\2agunshow-bagisto\site\packages\Devvly\CustomBlocks\src\resources\assets\js\app.js */"./src/resources/assets/js/app.js");
module.exports = __webpack_require__(/*! C:\Users\funct\Documents\dev\2agunshow-bagisto\site\packages\Devvly\CustomBlocks\src\Resources\assets\sass\app.scss */"./src/Resources/assets/sass/app.scss");


/***/ })

/******/ });