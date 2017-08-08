webpackJsonp([17,19],[
/* 0 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["a"] = ({
  name: 'swiper-slide',
  data() {
    return {
      slideClass: 'swiper-slide'
    };
  },
  ready() {
    this.update();
  },
  mounted() {
    this.update();
    if (this.$parent.options.slideClass) {
      this.slideClass = this.$parent.options.slideClass;
    }
  },
  updated() {
    this.update();
  },
  attached() {
    this.update();
  },
  methods: {
    update() {
      if (this.$parent && this.$parent.swiper && this.$parent.swiper.update) {
        this.$parent.swiper.update(true);
        if (this.$parent.options.loop) {
          this.$parent.swiper.reLoop();
        }
      }
    }
  }
});

/***/ }),
/* 9 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
//
//
//
//
//
//
//
//
//
//
//
//
//

var browser = typeof window !== 'undefined';
if (browser) {
  window.Swiper = __webpack_require__(3);
  __webpack_require__(12);
}
/* harmony default export */ __webpack_exports__["a"] = ({
  name: 'swiper',
  props: {
    options: {
      type: Object,
      default() {
        return {
          autoplay: 3500
        };
      }
    }
  },
  data() {
    return {
      defaultSwiperClasses: {
        wrapperClass: 'swiper-wrapper'
      }
    };
  },
  ready() {
    if (!this.swiper && browser) {
      this.swiper = new Swiper(this.$el, this.options);
    }
  },
  mounted() {
    var self = this;
    var mount = function () {
      if (!self.swiper && browser) {
        delete self.options.notNextTick;
        var setClassName = false;
        for (var className in self.defaultSwiperClasses) {
          if (self.defaultSwiperClasses.hasOwnProperty(className)) {
            if (self.options[className]) {
              setClassName = true;
              self.defaultSwiperClasses[className] = self.options[className];
            }
          }
        }
        var mountInstance = function () {
          self.swiper = new Swiper(self.$el, self.options);
        };
        setClassName ? self.$nextTick(mountInstance) : mountInstance();
      }
    };
    this.options.notNextTick ? mount() : this.$nextTick(mount);
  },
  updated() {
    if (this.swiper) {
      this.swiper.update();
    }
  },
  beforeDestroy() {
    if (this.swiper) {
      this.swiper.destroy();
      delete this.swiper;
    }
  }
});

/***/ }),
/* 10 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 11 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 12 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 13 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 14 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 15 */,
/* 16 */,
/* 17 */,
/* 18 */,
/* 19 */,
/* 20 */,
/* 21 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_vue_loader_lib_selector_type_script_index_0_slide_vue__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__vue_loader_lib_template_compiler_index_id_data_v_58f1647e_hasScoped_false_vue_loader_lib_selector_type_template_index_0_slide_vue__ = __webpack_require__(23);
var normalizeComponent = __webpack_require__(1)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_vue_loader_lib_selector_type_script_index_0_slide_vue__["a" /* default */],
  __WEBPACK_IMPORTED_MODULE_1__vue_loader_lib_template_compiler_index_id_data_v_58f1647e_hasScoped_false_vue_loader_lib_selector_type_template_index_0_slide_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 22 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_vue_loader_lib_selector_type_script_index_0_swiper_vue__ = __webpack_require__(9);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__vue_loader_lib_template_compiler_index_id_data_v_bad77e0a_hasScoped_false_vue_loader_lib_selector_type_template_index_0_swiper_vue__ = __webpack_require__(24);
var normalizeComponent = __webpack_require__(1)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_vue_loader_lib_selector_type_script_index_0_swiper_vue__["a" /* default */],
  __WEBPACK_IMPORTED_MODULE_1__vue_loader_lib_template_compiler_index_id_data_v_bad77e0a_hasScoped_false_vue_loader_lib_selector_type_template_index_0_swiper_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 23 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    class: _vm.slideClass
  }, [_vm._t("default")], 2)
}
var staticRenderFns = []
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);

/***/ }),
/* 24 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "swiper-container"
  }, [_vm._t("parallax-bg"), _vm._v(" "), _c('div', {
    class: _vm.defaultSwiperClasses.wrapperClass
  }, [_vm._t("default")], 2), _vm._v(" "), _vm._t("pagination"), _vm._v(" "), _vm._t("button-prev"), _vm._v(" "), _vm._t("button-next"), _vm._v(" "), _vm._t("scrollbar")], 2)
}
var staticRenderFns = []
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);

/***/ })
],[44]);