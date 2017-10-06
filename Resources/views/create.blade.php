@extends('admin::layouts.master')

@section('content')
    @include('admin::_partials._messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Edit form</h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(['route' => 'admin::form.store']) !!}

                    @include('form::_form')

                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                    <a href="{{ route('admin::form.index') }}" class="btn btn-default pull-right"><i class="fa fa-undo"></i> Back</a>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
