@section('styles')
    <link rel="stylesheet" href="{{ versionedAsset('assets/form/admin/css/form.css') }}">
@endsection

<input type="hidden" id="languages" value="{{ $languages->toJson() }}"/>
<input type="hidden" id="form" value="{{ $form->toJson() }}"/>

<ul class="nav nav-tabs" role="tablist">
    <li
            v-for="(language, key) in languages"
            role="presentation"
            :class="{'active': key === 0}"
    >
        <a
                :href="'#language-' + language.iso_code"
                :aria-controls="language.iso_code"
                role="tab"
                data-toggle="tab"
                class="v-cloak--hidden"
        >
            <span v-if="formErrors.has('translations.' + language.iso_code + '.name')" class="badge">!</span>
            @{{ language.title_localized }}
        </a>
    </li>
</ul>
<div class="tab-content">
    <div v-for="(language, key) in languages" role="tabpanel" class="tab-pane"
         :class="{'active': key === 0}" :id="'language-' + language.iso_code">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group"
                     :class="{ 'has-error': formErrors.has('translations.' + language.iso_code + '.name') }">
                    <label class="control-label">Name</label>
                    <input type="text" class="form-control" :name="'translations['+language.iso_code+'][name]'"
                           v-model="form.translations[key].name">
                    <span v-if="formErrors.has('translations.' + language.iso_code + '.name')"
                          class="help-block"
                          v-text="formErrors.get('translations.' + language.iso_code + '.name')"></span>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group"
                     :class="{ 'has-error': formErrors.has('translations.' + language.iso_code + '.success_message') }">
                    <label class="control-label">Success message</label>
                    <input type="text" class="form-control"
                           :name="'translations['+language.iso_code+'][success_message]'"
                           v-model="form.translations[key].success_message">
                </div>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-lg-9">
        <div class="form-group"
             :class="{ 'has-error': formErrors.has('key') }">
            <label>Key</label>
            <input type="text" :name="'key'" v-model="form.key" class="form-control"/>
            <span v-if="formErrors.has('key')"
                  class="help-block"
                  v-text="formErrors.get('key')"></span>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group"
             :class="{ 'has-error': formErrors.has('has_success_view') }">
            <label>Has success view?</label><br/>
            <label for="success-view" class="switcher switcher-success">
                <input type="checkbox" id="success-view" :name="'has_success_view'" v-model="form.has_success_view">
                <div class="switcher-indicator">
                    <div class="switcher-yes">YES</div>
                    <div class="switcher-no">NO</div>
                </div>
            </label>
        </div>
    </div>
</div>

{!! Form::label('fields', 'Fields') !!}

<div class="row">
    <div class="col-lg-4">
        <ul class="fields v-cloak--hidden">
            <li v-for="field in availableFields" @click="addField(field)">
                @{{ field.name }}
                <i class="fa fa-chevron-right"></i>
            </li>
        </ul>
    </div>

    <div class="col-lg-8">
        <div v-if="form.fields.length">
            <div id="accordion" role="tablist" aria-multiselectable="true">
                <draggable v-model="form.fields" @update="updateOrder" :options="{handle:'.handle'}">
                    <form-field v-for="(field, key) in form.fields" :data="field" :key="key"
                                v-on:remove-field="removeField(field)" :languages="languages"></form-field>
                </draggable>
            </div>
        </div>
        <div v-else>
            <div class="alert alert-danger v-cloak--hidden">Please add fields!</div>
        </div>
    </div>
</div>

@section('scripts')
    <script src="{{ versionedAsset('assets/form/admin/js/forms_form.js') }}"></script>
@endsection
