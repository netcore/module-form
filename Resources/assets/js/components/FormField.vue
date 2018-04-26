<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" :id="'heading-' + data.id">
                <h4 class="panel-title">
                    <div class="pull-left handle">
                        <i class="fa fa-arrows"></i>
                    </div>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" :href="'#collapse-' + data.id"
                       aria-expanded="true" :aria-controls="'collapse-' + data.id">
                        <div class="pull-left m-l-5" v-for="(language, key) in languages">
                            {{ language.iso_code.toUpperCase() }}: {{ data.translations[String(language.iso_code)].label }}
                        </div>
                    </a>
                    <div class="pull-right">
                        {{ camelCase(data.type) }}
                        <span v-if="$root.formErrors.hasPartial('fields.' + data.id + '.')" class="badge">!</span>
                    </div>
                </h4>
            </div>
            <div :id="'collapse-' + data.id" class="panel-collapse collapse" role="tabpanel"
                 :aria-labelledby="'heading-' + data.id">
                <div class="panel-body">
                    <input type="hidden" :name="'fields['+data.id+'][id]'" v-model="data.id" :value="data.id"/>
                    <input type="hidden" :name="'fields['+data.id+'][order]'" v-model="data.order"
                           :value="data.order"/>
                    <input type="hidden" :name="'fields['+data.id+'][type]'" :value="data.type"/>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6">
                            <ul class="nav nav-tabs" role="tablist">
                                <li
                                        v-for="(language, key) in languages"
                                        role="presentation"
                                        :class="{active: key === 0}"
                                >
                                    <a
                                            :href="'#fields-' + data.id + '-' + language.iso_code"
                                            :aria-controls="language.iso_code"
                                            role="tab"
                                            data-toggle="tab"
                                    >
                                        {{ language.title_localized }}
                                        <span v-if="$root.formErrors.hasPartial('fields.' + data.id + '.translations.' + language.iso_code)"
                                              class="badge">!</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div v-for="(language, key) in languages" role="tabpanel" class="tab-pane"
                                     :class="{active: key === 0}" :id="'fields-' + data.id + '-' + language.iso_code">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group"
                                                 :class="{ 'has-error': $root.formErrors.has('fields.' + data.id + '.translations.' + language.iso_code + '.label') }">
                                                <label v-text="'Label ' + language.iso_code.toUpperCase()"></label>
                                                <input :name="'fields['+data.id+'][translations]['+language.iso_code+'][label]'"
                                                       v-model="data.translations[String(language.iso_code)].label"
                                                       class="form-control"
                                                       placeholder="For example, Phone number"/>
                                                <span v-if="$root.formErrors.has('fields.' + data.id + '.translations.' + language.iso_code + '.label')"
                                                      class="help-block"
                                                      v-text="$root.formErrors.get('fields.' + data.id + '.translations.' + language.iso_code + '.label')"></span>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group"
                                                 :class="{ 'has-error': $root.formErrors.has('fields.' + data.id + '.translations.' + language.iso_code + '.placeholder') }">
                                                <label v-text="'Placeholder ' + language.iso_code.toUpperCase()"></label>
                                                <input :name="'fields['+data.id+'][translations]['+language.iso_code+'][placeholder]'"
                                                       v-model="data.translations[String(language.iso_code)].placeholder"
                                                       class="form-control"
                                                       placeholder="For example, Enter phone number"/>
                                                <span v-if="$root.formErrors.has('fields.' + data.id + '.translations.' + language.iso_code + '.placeholder')"
                                                      class="help-block"
                                                      v-text="$root.formErrors.get('fields.' + data.id + '.translations.' + language.iso_code + '.placeholder')"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-10">
                            <div class="form-group"
                                 :class="{ 'has-error': $root.formErrors.has('fields.' + data.id + '.key') }">
                                <label>Key</label>
                                <input :name="'fields['+data.id+'][key]'"
                                       v-model="data.key"
                                       class="form-control"
                                       placeholder="For example, phone_number"/>
                                <span v-if="$root.formErrors.has('fields.' + data.id + '.key')"
                                      class="help-block"
                                      v-text="$root.formErrors.get('fields.' + data.id + '.key')"></span>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Show label?</label>
                                <label :for="'success-view-' + data.id" class="switcher switcher-success">
                                    <input type="checkbox" :id="'success-view-' + data.id"
                                           :name="'fields['+data.id+'][show_label]'" v-model="data.show_label">
                                    <div class="switcher-indicator">
                                        <div class="switcher-yes">YES</div>
                                        <div class="switcher-no">NO</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div v-if="data.type === 'select'">
                        <div class="form-group">
                            <label>Options</label>
                            <br>
                            <select2
                                    :data="optionTypes"
                                    :name="'fields['+data.id+'][options_type]'"
                                    :placeholder="'Please select'"
                                    v-model="data.optionsType"
                            ></select2>
                        </div>

                        <div class="form-group" v-if="data.optionsType === 'data'">
                            <button type="button" class="btn btn-success btn-xs" @click="addOption()">
                                <i class="fa fa-plus"></i> Add Option
                            </button>
                            <br>
                            <div class="input-group" v-for="(option, key) in data.optionsData">
                                <input type="text" :name="'fields['+data.id+'][options][key][]'"
                                       v-model="option.key"
                                       class="form-control"/>
                                <span class="input-group-addon">-</span>
                                <input type="text" :name="'fields['+data.id+'][options][value][]'"
                                       v-model="option.value"
                                       class="form-control"/>
                                <span class="input-group-addon">
                                    <button type="button"
                                            class="btn btn-danger btn-xs"
                                            @click="removeOption(key)">
                                        <i class="fa fa-trash"></i> Remove
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div v-if="data.optionsType === 'function'">
                            <div class="form-group">
                                <label>Function name</label>
                                <input :name="'fields['+data.id+'][options]'"
                                       v-model="data.optionsFunction"
                                       class="form-control"
                                       placeholder="For example, getCountriesList"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Attributes</label>
                                <select2
                                        :data="htmlAttributes"
                                        :name="'fields['+data.id+'][attributes][]'"
                                        :placeholder="'Please select'"
                                        :multiple="true"
                                        v-model="data.meta.attributes"
                                ></select2>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Validation rules</label>
                                <select2
                                        :data="validationRules"
                                        :name="'fields['+data.id+'][validation][]'"
                                        :placeholder="'Please select'"
                                        :multiple="true"
                                        v-model="data.meta.validation"
                                ></select2>
                            </div>
                        </div>
                    </div>

                    <a href="javascript:;" class="btn btn-xs btn-danger pull-right" @click="remove(model)">
                        <i class="fa fa-trash"></i> Remove
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        components: {
            'select2': window.Select2
        },

        props: {
            data: Object,
            languages: Array,
        },

        data: function () {
            return {
                model: {},
                htmlAttributes: [
                    {id: 'required', 'text': 'Required'},
                    {id: 'disabled', 'text': 'Disabled'}
                ],
                validationRules: [
                    {id: 'accepted', 'text': 'Accepted'},
                    {id: 'email', 'text': 'Email'},
                    {id: 'file', 'text': 'File'},
                    {id: 'image', 'text': 'Image'},
                    {id: 'required', 'text': 'Required'},
                    {id: 'unique', 'text': 'Unique'},
                    {id: 'numeric', 'text': 'Numeric'},
                    {id: 'url', 'text': 'URL'},
                ],
                optionTypes: [
                    {id: 'function', 'text': 'Function'},
                    {id: 'data', 'text': 'Data'},
                ]
            }
        },

        created() {
            this.setOptions();
        },

        methods: {
            addOption() {
                this.data.optionsData.push({
                    'key': '',
                    'value': ''
                });
            },

            removeOption(option) {
                var self = this;

                swal({
                    title: "Are you sure?",
                    text: "You will not be able to restore this data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Delete!"
                }).then(function () {
                    Vue.delete(self.options, option);
                    toastr.success('Option successfully removed!');
                }).catch(swal.noop);
            },

            remove(field) {
                this.$emit('remove-field', field);
            },

            setOptions() {
                var options = this.data['meta'] ? this.data['meta']['options'] : [];

                if (typeof options === 'object') {
                    var tmpArray = [];

                    for (var key in options) {
                        if (options.hasOwnProperty(key)) {
                            tmpArray.push({
                                key: key,
                                value: options[key]
                            });
                        }
                    }

                    this.data.optionsType = 'data';
                    this.data.optionsData = tmpArray;
                } else if (options === undefined) {
                    this.data.optionsType = 'data';
                } else {
                    this.data.optionsType = 'function';
                    this.data.optionsFunction = options;
                }
            },

            camelCase(str) {
                return str.replace(/(?:^\w|[A-Z]|\b\w)/g, function (letter, index) {
                    return index === 0 ? letter.toUpperCase() : letter.toLowerCase();
                }).replace(/\s+/g, '');
            }
        }
    }
</script>
