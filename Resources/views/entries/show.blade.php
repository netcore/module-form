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
                    <div class="table-primary">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Field</th>
                                <th>Value</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($form->fields as $field)
                                <tr>
                                    <td width="20%">{{ $field->label }}:</td>
                                    <td>
                                        @if ($field->type === 'file')
                                            <a href="{{ asset(config('netcore.module-form.uploads_path') . array_get($entry, $field->key, '')) }}">{{ array_get($entry, $field->key, '') }}</a>
                                        @else
                                            {{ array_get($entry, $field->key, '') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <b>Additional information:</b><br/>
                    Date/Time: {{ $entryLog ? $entryLog->created_at->format('d.m.Y H:i') : '' }}<br/>
                    IP: {{ $entryLog ? $entryLog->ip : '' }}<br/>
                    User Agent: {{ $entryLog ? $entryLog->user_agent : '' }}<br/>
                </div>
            </div>
        </div>
    </div>
@endsection
