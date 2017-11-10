@section('styles')
    <link rel="stylesheet" href="{{ versionedAsset('assets/form/admin/css/form.css') }}">
@endsection

<div class="form-group no-margin-hr">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

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
                <draggable v-model="formFields" @update="updateOrder">
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
    <script>
        var languages = '{{ $languages->toJson() }}';
        var currentFields = '{{ $fields->toJson() }}';

        languages = JSON.parse(languages.replace(/&quot;/g, '"'));
        currentFields = JSON.parse(currentFields.replace(/&quot;/g, '"'));
    </script>
    <script src="{{ versionedAsset('assets/form/admin/js/forms_form.js') }}"></script>
@endsection
