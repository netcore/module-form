<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" :id="'heading-' + model.id">
                <h4 class="panel-title">
                    <div class="pull-left">
                        <i class="fa fa-sort"></i>
                    </div>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" :href="'#collapse-' + model.id"
                       aria-expanded="true" :aria-controls="'collapse-' + model.id">
                        <div class="pull-left m-l-5" v-for="(translation, key) in model.translations">
                            {{ key.toUpperCase() }}: {{ translation.label }}
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

                    <div class="row">
                        <div :class="'col-md-' + (12 / languages.length)" class="col-xs-12"
                             v-for="language in languages">
                            <div class="form-group">
                                <label v-text="'Label ' + language.iso_code.toUpperCase()"></label>
                                <input :name="'fields['+model.id+'][translations]['+language.iso_code+'][label]'"
                                       v-model="data.translations[String(language.iso_code)].label"
                                       class="form-control"
                                       placeholder="For example, Phone number"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Validation</label>
                        <select2
                                :data="[{id: 'required', 'text': 'Required'}]"
                                :name="'fields['+model.id+'][validation][]'"
                                :placeholder="'Please select'"
                                :multiple="true"
                                v-model="test_variable"
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

        props: {
            data: Object,
            languages: Array,
        },

        data: function(){
            return {
                test_variable: []
            }
        },

        computed: {
            model: function () {
                var modified = JSON.parse(JSON.stringify(this.data))

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
            remove(field) {
                this.$emit('remove-field', field);
            }
        }
    }
</script>
