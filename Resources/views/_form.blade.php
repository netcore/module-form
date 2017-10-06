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
