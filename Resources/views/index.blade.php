@extends('admin::layouts.master')

@section('content')
    @include('admin::_partials._messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="{{ route('admin::form.export', 'all') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-file-excel-o"></i> Export all (XLS)
                        </a>
                        <a href="{{ route('admin::form.export', ['all', 'csv']) }}" class="btn btn-xs btn-success">
                            <i class="fa fa-file-o"></i> Export all (CSV)
                        </a>
                        <a href="{{ route('admin::form.create') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i> Create
                        </a>
                    </div>
                    <h4 class="panel-title">Forms</h4>
                </div>
                <div class="panel-body">
                    <div class="table-primary">
                        <table class="table table-bordered datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th width="15%" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($forms as $form)
                                <tr>
                                    <td>
                                        @foreach(\Netcore\Translator\Helpers\TransHelper::getAllLanguages() as $language)
                                            <b>{{ strtoupper($language->iso_code) }}:</b> {{ trans_model($form, $language, 'name') }}<br/>
                                        @endforeach
                                    </td>
                                    <td width="15%" class="text-center">
                                        <a href="{{ route('admin::form.entries.index', $form) }}"
                                           class="btn btn-xs btn-default">
                                            <i class="fa fa-eye"></i> Entries ({{ $form->entries()->count() }})
                                        </a>
                                        <a href="{{ route('admin::form.edit', $form) }}" class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin::form.destroy', $form) }}"
                                           class="btn btn-danger btn-xs confirm-delete" data-id="{{ $form->id }}">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ versionedAsset('assets/form/admin/js/forms_index.js') }}" type="text/javascript"></script>
@endsection
