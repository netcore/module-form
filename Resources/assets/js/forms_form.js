'use strict';

import draggable from 'vuedraggable';

new Vue({
    el: '#formApp',

    components: {
        'form-field': require('./components/FormField.vue'),
        'draggable': draggable
    },

    data: {
        languages: JSON.parse($('#languages').val()),
        formFields: Object.values(JSON.parse($('#currentFields').val())),
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
            {
                'name': 'Email',
                'type': 'email'
            },
            {
                'name': 'Number',
                'type': 'number'
            },
        ]
    },

    mounted() {
        this.$on('remove-field', function (data) {
            this.removeField(data);
        }.bind(this));
    },

    methods: {
        addField(field) {
            var translations = {};

            $.each(this.languages, function (i, language) {
                translations[language.iso_code] = {
                    'name': 'Unnamed field'
                }
            });

            this.formFields.push({
                'id': this.getNextId(this.formFields, 'id'),
                'type': field.type,
                'type_name': field.name,
                'translations': translations,
                'order': this.getNextId(this.formFields, 'order'),
            });
        },

        removeField(field) {
            var index = this.findIndex('id', field.id);

            if (index !== -1) {
                this.formFields.splice(index, 1);
            }
        },

        updateOrder(e) {
            this.formFields[e.newIndex]['order'] = e.newIndex + 1;
            this.formFields[e.oldIndex]['order'] = e.oldIndex + 1;
        },

        findIndex(property, value) {
            var result = -1;
            this.formFields.some(function (item, i) {
                if (item[property] === value) {
                    result = i;

                    return true;
                }
            });

            return result;
        },

        getNextId: function (array, key) {
            var ids = array.map(function (el) {
                return el[key];
            });
            var maxId = Math.max.apply(null, ids);

            return maxId !== -Infinity ? maxId + 1 : 1;
        }

    }
});
