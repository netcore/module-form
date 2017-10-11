'use strict';

new Vue({
    el: '#formApp',

    components: {
        'form-field': require('./components/FormField.vue')
    },

    data: {
        languages: languages,
        formFields: currentFields,
        availableFields: [
            {
                'name': 'Text',
                'type': 'text'
            },
            {
                'name': 'Textarea',
                'type': 'textarea'
            },
            {
                'name': 'Select',
                'type': 'select'
            },
            {
                'name': 'Checkbox',
                'type': 'checkbox'
            },
            {
                'name': 'File',
                'type': 'file'
            },
        ]
    },

    mounted() {
        this.$on('remove-field', function (data) {
            this.removeField(data);
        }.bind(this));
    },

    methods: {
        addField: function (field) {
            var translations = {};

            $.each(this.languages, function (i, language) {
                var isoCode = language.iso_code;
                translations[isoCode] = {
                    'label': 'Unnamed field'
                }
            });

            this.formFields.push({
                'id': this.formFields.length,
                'type': field.type,
                'type_name': field.name,
                'translations': translations
            });
        },
        
        removeField: function (field) {
            var index = this.findIndex('id', field.id);

            if (index != -1) {
                this.formFields.splice(index, 1);
            }
        },

        findIndex: function (property, value) {
            var result = -1;
            this.formFields.some(function (item, i) {
                if (item[property] === value) {
                    result = i;

                    return true;
                }
            });

            return result;
        },
    }
});
