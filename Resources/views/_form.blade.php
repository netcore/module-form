@section('styles')
    <link rel="stylesheet" href="{{ versionedAsset('assets/form/admin/css/form.css') }}">
@endsection

<input type="hidden" id="languages" value="{{ $languages->toJson() }}"/>
<input type="hidden" id="currentFields" value="{{ $fields->toJson() }}"/>

@include('translate::_partials._nav_tabs')

<div class="tab-content">
    @foreach($languages as $language)
        <div role="tabpanel" class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language->iso_code }}">
            <div class="form-group no-margin-hr">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('translations['.$language->iso_code.'][name]', trans_model((isset($form) ? $form : null), $language, 'name'), ['class' => 'form-control']) !!}
            </div>

            <div class="form-group no-margin-hr">
                {!! Form::label('success_message', 'Success message') !!}
                {!! Form::text('translations['.$language->iso_code.'][success_message]', trans_model((isset($form) ? $form : null), $language, 'success_message'), ['class' => 'form-control']) !!}
            </div>
        </div>
    @endforeach
</div>

<hr>

{!! Form::label('fields', 'Fields') !!}

<div class="row">
    <div class="col-lg-4">
        <ul class="fields">
            <li v-for="field in availableFields" @click="addField(field)" v-cloak>
                @{{ field.name }}
                <i class="fa fa-chevron-right"></i>
            </li>
        </ul>
    </div>

    <div class="col-lg-8">
        <div v-if="formFields.length">
            <div id="accordion" role="tablist" aria-multiselectable="true">
                <draggable v-model="formFields" @update="updateOrder" :options="{handle:'.handle'}">
                    <form-field v-for="field in formFields" :data="field" :key="field.id" v-on:remove-field="removeField(field)" :languages="languages"></form-field>
                </draggable>
            </div>
        </div>
        <div v-else>
            <div class="alert alert-info">No fields added</div>
        </div>
    </div>
</div>

@section('scripts')
    <script src="{{ versionedAsset('assets/form/admin/js/forms_form.js') }}"></script>
@endsection
