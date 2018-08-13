@extends('admin::layouts.master')

@section('content')
    @include('admin::_partials._messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="{{ route('admin::form.export', $form->id) }}" class="btn btn-xs btn-success">
                            <i class="fa fa-file-excel-o"></i> Export (XLS)
                        </a>

                        <a href="{{ route('admin::form.export', [$form->id, 'csv']) }}" class="btn btn-xs btn-success">
                            <i class="fa fa-file-o"></i> Export (CSV)
                        </a>

                        <a href="{{ route('admin::form.index') }}" class="btn btn-xs btn-primary">
                            <i class="fa fa-undo"></i> Back to list
                        </a>
                    </div>
                    <h4 class="panel-title">Form "{{ $form->name }}" entries</h4>
                </div>

                <div class="panel-body">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                        <tr>
                            @foreach ($form->fields->sortBy('order') as $field)
                                <th>{{ $field->label }}</th>
                            @endforeach
                            <th>Submitted At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#datatable').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: '{{ route('admin::form.entries.pagination', $form->id) }}',

                columns: [
                    @foreach ($form->fields->sortBy('order') as $field)
                    {
                    	data: '{{ $field->key }}',
                        name: '{{ $field->key }}'
                    },
                    @endforeach
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: false,
                        width: '10%',
                        className: 'text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        searchable: false,
                        sortable: false,
                        width: '10%',
                        className: 'text-center'
                    }
                ],

                order: [[{{ $form->fields->count() }}, 'desc' ]]
            })
        });
    </script>
@endsection
