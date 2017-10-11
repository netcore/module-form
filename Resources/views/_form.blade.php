@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/form/admin/css/form.css') }}">
@endsection

<div class="form-group no-margin-hr">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group no-margin-hr">
    {!! Form::label('type', 'Type') !!}
    {!! Form::select('type', ['url' => 'URL', 'model' => 'Model'], null, ['class' => 'form-control']) !!}
</div>

<div class="form-group no-margin-hr">
    {!! Form::label('type_value', 'Type value') !!}
    {!! Form::text('type_value', null, ['class' => 'form-control']) !!}
</div>

<hr>

<div class="row">
    <div class="col-lg-6">
        <ul class="fields">
            <li v-for="field in availableFields" @click="addField(field)" v-cloak>
                @{{ field.name }}
                <i class="fa fa-chevron-right"></i>
            </li>
        </ul>
    </div>

    <div class="col-lg-6">
        <div id="accordion" role="tablist" aria-multiselectable="true">
            <draggable v-model="formFields" @update="updateOrder">
                <form-field v-for="field in formFields" :data="field" :key="field.id" v-on:remove-field="removeField(field)" :languages="languages"></form-field>
            </draggable>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        var languages = '{{ json_encode($languages) }}';
        var currentFields = '{{ json_encode(($fields ?: []), JSON_UNESCAPED_UNICODE) }}';

        languages = JSON.parse(languages.replace(/&quot;/g, '"'));
        currentFields = JSON.parse(currentFields.replace(/&quot;/g, '"'));
    </script>
    <script src="{{ asset('assets/form/admin/js/form.js') }}"></script>
@endsection
