@extends('admin::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Form "{{ $form->name }}" entries</h4>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                        <tr>
                            @foreach ($form->fields as $field)
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
                    @foreach ($form->fields as $field)
                    {data: '{{ $field->key }}', name: '{{ $field->key }}'},
                    @endforeach
                    {
                        data: 'submitted_at',
                        name: 'submitted_at',
                        searchable: false,
                        sortable: false,
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
                ]

            })

        });

    </script>
@endsection
