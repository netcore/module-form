class Errors {
    constructor() {
        this.formErrors = {};
        this.success = false;
    }

    record(errors) {
        this.formErrors = errors;
    }

    any() {
        return Object.keys(this.formErrors).length > 0;
    }

    has(field) {
        return this.formErrors.hasOwnProperty(field);
    }

    hasPartial(field) {
        var exists = false;

        $.each(this.formErrors, function (key, value) {
            if (key.indexOf(field) !== -1) {
                exists = true;
            }
        });

        return exists;
    }

    get(field) {
        if (this.formErrors[field]) {
            return this.formErrors[field][0];
        }
    }

    clear(field) {
        Vue.delete(this.formErrors, field);
    }

    clearAll(fields) {
        var self = this;

        $.each(fields, function (key, field) {
            if (self.get(field)) {
                Vue.delete(self.formErrors, field);
            }
        });
    }
}

import draggable from 'vuedraggable';

new Vue({
    el: '#formApp',

    components: {
        'form-field': require('./components/FormField.vue'),
        'draggable': draggable
    },

    data: {
        formErrors: new Errors(),
        languages: JSON.parse($('#languages').val()),
        form: JSON.parse($('#form').val()),
        loading: false,
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
        submit: function () {
            var self = this;
            self.loading = true;

            var $form = $('form');
            var $formData = new FormData($form[0]);

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $formData,
                processData: false,
                contentType: false,
                cache: false
            })
                .done(function (response) {
                    window.location.replace(response.redirect);
                })
                .fail(function (response) {
                    if (response.responseJSON.errors) {
                        self.formErrors.record(response.responseJSON.errors);
                    }

                    self.loading = false;
                });
        },

        addField(field) {
            var translations = {};

            $.each(this.languages, function (i, language) {
                translations[language.iso_code] = {
                    label: 'Unnamed field',
                    placeholder: ''
                }
            });

            this.form.fields.push({
                id: this.getNextId(this.form.fields, 'id'),
                type: field.type,
                translations: translations,
                order: this.getNextId(this.form.fields, 'order'),
                meta: {
                    'attributes': [],
                    'validation': [],
                },
                optionsData: [],
                optionsFunction: '',
                optionsType: 'data',
            });
        },

        removeField(field) {
            var self = this;
            var text = 'You will not be able to restore this data!';

            if (self.form.id !== undefined) {
                text = text + ' And all related entry data will be deleted!'
            }

            swal({
                title: 'Are you sure?',
                text: text,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Delete!',
            }).then(function () {
                if (self.form.id !== undefined) {
                    $.post('/admin/form/' + self.form.id + '/' + field.id);
                }

                var index = self.findIndex('id', field.id);

                if (index !== -1) {
                    self.form.fields.splice(index, 1);
                }

                toastr.success('Field successfully deleted!');
            }).catch(swal.noop);
        },

        updateOrder(e) {
            this.form.fields[e.newIndex]['order'] = e.newIndex + 1;
            this.form.fields[e.oldIndex]['order'] = e.oldIndex + 1;
        },

        findIndex(property, value) {
            var result = -1;
            this.form.fields.some(function (item, i) {
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
