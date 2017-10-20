@extends('admin::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="{{ route('admin::form.entries.index', $form) }}" class="btn btn-xs btn-primary">
                            <i class="fa fa-undo"></i> Back to list
                        </a>
                    </div>
                    <h4 class="panel-title">View entry</h4>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tbody>
                        @foreach ($form->fields as $field)
                            <tr>
                                <td width="20%">{{ $field->label }}:</td>
                                <td>{{ array_get($entry, $field->key, '') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
