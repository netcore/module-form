<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" :id="'heading-' + model.id">
                <h4 class="panel-title">
                    <div class="pull-left handle">
                        <i class="fa fa-arrows"></i>
                    </div>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" :href="'#collapse-' + model.id"
                       aria-expanded="true" :aria-controls="'collapse-' + model.id">
                        <div class="pull-left m-l-5" v-for="(translation, key) in model.translations">
                            {{ key.toUpperCase() }}: {{ translation.name }}
                        </div>
                    </a>
                    <div class="pull-right">
                        {{ model.type_name }}
                    </div>
                </h4>
            </div>
            <div :id="'collapse-' + model.id" class="panel-collapse collapse" role="tabpanel"
                 :aria-labelledby="'heading-' + model.id">
                <div class="panel-body">
                	<input type="hidden" :name="'fields['+model.id+'][id]'" v-model="model.id" :value="model.id"/>
                    <input type="hidden" :name="'fields['+model.id+'][order]'" v-model="model.order" :value="model.order"/>
                    <input type="hidden" :name="'fields['+model.id+'][type]'" :value="model.type"/>
                    <input type="hidden" :name="'fields['+model.id+'][type_name]'" :value="model.type_name"/>

                    <div class="form-group">
                        <label>Key</label>
                        <input :name="'fields['+model.id+'][key]'"
                               v-model="data.key"
                               class="form-control"
                               placeholder="For example, phone_number"/>
                    </div>

                    <div class="form-group">
                        <label>Show label?</label><br>
                        <input type="checkbox" :name="'fields['+model.id+'][show_label]'" v-model="data.show_label"/>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6">
                            <ul class="nav nav-tabs" role="tablist">
                                <li
                                        v-for="(language, key) in languages"
                                        role="presentation"
                                        :class="{active: key === 0}"
                                >
                                    <a
                                            :href="'#fields-' + model.id + '-' + language.iso_code"
                                            :aria-controls="language.iso_code"
                                            role="tab"
                                            data-toggle="tab"
                                    >
                                        {{ language.title_localized }}
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div v-for="(language, key) in languages" role="tabpanel" class="tab-pane"
                                     :class="{active: key === 0}" :id="'fields-' + model.id + '-' + language.iso_code">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label v-text="'Label ' + language.iso_code.toUpperCase()"></label>
                                                <input :name="'fields['+model.id+'][translations]['+language.iso_code+'][label]'"
                                                       v-model="model.translations[String(language.iso_code)].label"
                                                       class="form-control"
                                                       placeholder="For example, Phone number"/>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label v-text="'Placeholder ' + language.iso_code.toUpperCase()"></label>
                                                <input :name="'fields['+model.id+'][translations]['+language.iso_code+'][placeholder]'"
                                                       v-model="model.translations[String(language.iso_code)].placeholder"
                                                       class="form-control"
                                                       placeholder="For example, Enter phone number"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" v-if="model.type === 'select'">
                        <label>Options</label>
                        <br>
                        <button type="button" class="btn btn-success btn-xs" @click="addOption()">
                            <i class="fa fa-plus"></i> Add Field
                        </button>
                        <br>
                        <div class="input-group" v-for="(option, key) in options">
                            <input type="text" :name="'fields['+model.id+'][options][key][]'" :value="option.value" v-model="option.key" class="form-control"/>
                            <span class="input-group-addon">-</span>
                            <input type="text" :name="'fields['+model.id+'][options][value][]'" :value="option.text" v-model="option.value" class="form-control"/>
                            <span class="input-group-addon">
                                <button type="button" class="btn btn-danger btn-xs"
                                        @click="removeOption(key)">Delete</button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Attributes</label>
                        <select2
                                :data="htmlAttributes"
                                :name="'fields['+model.id+'][attributes][]'"
                                :placeholder="'Please select'"
                                :multiple="true"
                                v-model="attributes"
                        ></select2>
                    </div>

                    <div class="form-group">
                        <label>Validation rules</label>
                        <select2
                                :data="validationRules"
                                :name="'fields['+model.id+'][validation][]'"
                                :placeholder="'Please select'"
                                :multiple="true"
                                v-model="validation"
                        ></select2>
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
                attributes: this.data['meta'] ? this.data['meta']['attributes'] : [],
                options: [],
                validation: this.data['meta'] ? this.data['meta']['validation'] : []
            }
        },

        mounted() {
            this.setOptions();
        },

        computed: {
            model: function () {
                var modified = JSON.parse(JSON.stringify(this.data));

                $.each(this.languages, function (i, language) {

                    var isoCode = language.iso_code;
                    var langObj = modified.translations[isoCode];
                    var label = langObj ? langObj.label : '';
                    var name = langObj ? langObj.name : '';
                    var placeholder = langObj ? langObj.placeholder : '';

                    modified.translations[isoCode] = {
                        'label': label,
                        'name': name,
                        'placeholder': placeholder,
                    };
                });

                return modified;
            }
        },

        methods: {
            addOption() {
                this.options.push({
                    'key': '',
                    'value': ''
                });
            },

            removeOption(option) {
                Vue.delete(this.options, option);
            },

            remove(field) {
                this.$emit('remove-field', field);
            },

            setOptions() {
                var options = this.data['meta'] ? this.data['meta']['options'] : [];
                var tmpArray = [];

                for (var key in options) {
                    if (options.hasOwnProperty(key)) {
                        tmpArray.push({
                            key: key,
                            value: options[key]
                        });
                    }
                }

                this.options = tmpArray;
            }
        }
    }
</script>
