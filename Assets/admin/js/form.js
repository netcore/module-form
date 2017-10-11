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
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(1);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


new Vue({
    el: '#formApp',

    components: {
        'form-field': __webpack_require__(2)
    },

    data: {
        languages: languages,
        formFields: currentFields,
        availableFields: [{
            'name': 'Text',
            'type': 'text'
        }, {
            'name': 'Textarea',
            'type': 'textarea'
        }, {
            'name': 'Select',
            'type': 'select'
        }, {
            'name': 'Checkbox',
            'type': 'checkbox'
        }, {
            'name': 'File',
            'type': 'file'
        }]
    },

    mounted: function mounted() {
        this.$on('remove-field', function (data) {
            this.removeField(data);
        }.bind(this));
    },


    methods: {
        addField: function addField(field) {
            var translations = {};

            $.each(this.languages, function (i, language) {
                var isoCode = language.iso_code;
                translations[isoCode] = {
                    'label': 'Unnamed field'
                };
            });

            this.formFields.push({
                'id': this.formFields.length,
                'type': field.type,
                'type_name': field.name,
                'translations': translations
            });
        },

        removeField: function removeField(field) {
            var index = this.findIndex('id', field.id);

            if (index != -1) {
                this.formFields.splice(index, 1);
            }
        },

        findIndex: function findIndex(property, value) {
            var result = -1;
            this.formFields.some(function (item, i) {
                if (item[property] === value) {
                    result = i;

                    return true;
                }
            });

            return result;
        }
    }
});

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(3)
/* script */
var __vue_script__ = __webpack_require__(4)
/* template */
var __vue_template__ = __webpack_require__(5)
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "Resources\\assets\\js\\components\\FormField.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] FormField.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-639e14d9", Component.options)
  } else {
    hotAPI.reload("data-v-639e14d9", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 3 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// this module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate
    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
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

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        data: Object,
        languages: Array
    },

    computed: {
        model: function model() {

            var modified = JSON.parse(JSON.stringify(this.data));

            $.each(this.languages, function (i, language) {

                var isoCode = language.iso_code;
                var langObj = modified.translations[isoCode];
                var label = langObj ? langObj.label : '';

                modified.translations[isoCode] = {
                    'label': label
                };
            });

            return modified;
        }
    },

    methods: {
        remove: function remove(field) {
            this.$emit('remove-field', field);
        },
        checkOption: function checkOption() {
            //
        }
    }
});

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "div",
      {
        staticClass: "panel-heading",
        attrs: { role: "tab", id: "heading-" + _vm.model.id }
      },
      [
        _c("h4", { staticClass: "panel-title" }, [
          _vm._m(0),
          _vm._v(" "),
          _c(
            "a",
            {
              attrs: {
                role: "button",
                "data-toggle": "collapse",
                "data-parent": "#accordion",
                href: "#collapse-" + _vm.model.id,
                "aria-expanded": "true",
                "aria-controls": "collapse-" + _vm.model.id
              }
            },
            _vm._l(_vm.model.translations, function(translation, key) {
              return _c("div", { staticClass: "pull-left m-l-5" }, [
                _vm._v(
                  "\n                    " +
                    _vm._s(key.toUpperCase()) +
                    ": " +
                    _vm._s(translation.label) +
                    "\n                "
                )
              ])
            })
          ),
          _vm._v(" "),
          _c("div", { staticClass: "pull-right" }, [
            _vm._v(
              "\n                " +
                _vm._s(_vm.model.type_name) +
                "\n            "
            )
          ])
        ])
      ]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        staticClass: "panel-collapse collapse",
        attrs: {
          id: "collapse-" + _vm.model.id,
          role: "tabpanel",
          "aria-labelledby": "heading-" + _vm.model.id
        }
      },
      [
        _c("div", { staticClass: "panel-body" }, [
          _c("input", {
            attrs: {
              type: "hidden",
              name: "fields[" + _vm.model.id + "][type]"
            },
            domProps: { value: _vm.model.type }
          }),
          _vm._v(" "),
          _c("input", {
            attrs: {
              type: "hidden",
              name: "fields[" + _vm.model.id + "][type_name]"
            },
            domProps: { value: _vm.model.type_name }
          }),
          _vm._v(" "),
          _c("div", { staticClass: "form-group" }, [
            _c("label", [_vm._v("Key")]),
            _vm._v(" "),
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.data.key,
                  expression: "data.key"
                }
              ],
              staticClass: "form-control",
              attrs: {
                name: "fields[" + _vm.model.id + "][key]",
                placeholder: "For example, phone_number"
              },
              domProps: { value: _vm.data.key },
              on: {
                input: function($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.data.key = $event.target.value
                }
              }
            })
          ]),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "row" },
            _vm._l(_vm.languages, function(language) {
              return _c(
                "div",
                {
                  staticClass: "col-xs-12",
                  class: "col-md-" + 12 / _vm.languages.length
                },
                [
                  _c("div", { staticClass: "form-group" }, [
                    _c("label", {
                      domProps: {
                        textContent: _vm._s(
                          "Label " + language.iso_code.toUpperCase()
                        )
                      }
                    }),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value:
                            _vm.data.translations[String(language.iso_code)]
                              .label,
                          expression:
                            "data.translations[String(language.iso_code)].label"
                        }
                      ],
                      staticClass: "form-control",
                      attrs: {
                        name:
                          "fields[" +
                          _vm.model.id +
                          "][translations][" +
                          language.iso_code +
                          "][label]",
                        placeholder: "For example, Phone number"
                      },
                      domProps: {
                        value:
                          _vm.data.translations[String(language.iso_code)].label
                      },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.data.translations[
                            String(language.iso_code)
                          ].label =
                            $event.target.value
                        }
                      }
                    })
                  ])
                ]
              )
            })
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "form-group" },
            [
              _c("label", [_vm._v("Validation")]),
              _vm._v(" "),
              _c("select2", {
                attrs: {
                  data: [
                    { id: "required", text: "Required" },
                    { id: "min", text: "Min" },
                    { id: "max", text: "Max" }
                  ],
                  name: "fields[" + _vm.model.id + "][validation][]",
                  placeholder: "Please select",
                  options: { multiple: true }
                }
              })
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "a",
            {
              staticClass: "btn btn-xs btn-danger pull-right",
              attrs: { href: "javascript:;" },
              on: {
                click: function($event) {
                  _vm.remove(_vm.model)
                }
              }
            },
            [
              _c("i", { staticClass: "fa fa-trash" }),
              _vm._v(" Remove\n            ")
            ]
          )
        ])
      ]
    )
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "pull-left" }, [
      _c("i", { staticClass: "fa fa-sort" })
    ])
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-639e14d9", module.exports)
  }
}

/***/ })
/******/ ]);