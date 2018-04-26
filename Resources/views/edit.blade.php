@extends('admin::layouts.master')

@section('content')
    @include('admin::_partials._messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="{{ route('admin::form.index') }}" class="btn btn-xs btn-primary">
                            <i class="fa fa-undo"></i> Back to list
                        </a>
                    </div>
                    <h4 class="panel-title">Edit form</h4>
                </div>
                <div class="panel-body" id="formApp" v-cloak>
                    {!! Form::model($form, ['route' => ['admin::form.update', $form], 'method' => 'PATCH']) !!}

                    @include('form::_form')

                    <button type="button" :disabled="loading" class="btn btn-success pull-right" @click="submit()">
                        <span v-if="loading">
                            <i class="fa fa-refresh fa-spin"></i>
                        </span>
                        <span v-else>
                           <i class="fa fa-save"></i> Save
                        </span>
                    </button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
