@extends('admin::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="{{ route('admin::form.create') }}" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Create</a>
                    </div>
                    <h4 class="panel-title">Forms</h4>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($forms as $form)
                            <tr>
                                <td>{{ $form->name }}</td>
                                <td width="10%" class="text-center">
                                    <a href="{{ route('admin::form.edit', $form) }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="{{ route('admin::form.destroy', $form) }}" class="btn btn-danger btn-xs confirm-delete" data-id="{{ $form->id }}"><i class="fa fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
                                    <div class="alert alert-info">No Forms</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
