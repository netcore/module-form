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
                                    <td width="20%">
                                        {{ $field->label }}:
                                    </td>
                                    <td>
                                        @if ($field->type === 'file')
                                            <a href="{{ asset(config('netcore.module-form.uploads_path') . array_get($entry, $field->key, '')) }}">
                                                {{ array_get($entry, $field->key, '') }}
                                            </a>
                                        @else
                                            {{ array_get($entry, $field->key, '') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <b>Additional information:</b>
                    <hr>

                    <ul>
                        <li><b>IP:</b> {{ $entryLog ? $entryLog->ip : '' }}</li>
                        <li><b>Date/Time:</b> {{ $entryLog ? $entryLog->created_at->format('d.m.Y H:i') : '' }}</li>
                        <li><b>User Agent:</b> {{ $entryLog ? $entryLog->user_agent : '' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style type="text/css">
        table tbody tr td {
            overflow-wrap: break-word;

            word-wrap: break-word;
            word-break: break-all;
            word-break: break-word;
            -ms-word-break: break-all;

            hyphens: auto;
            -ms-hyphens: auto;
            -moz-hyphens: auto;
            -webkit-hyphens: auto;
        }
    </style>
@endsection