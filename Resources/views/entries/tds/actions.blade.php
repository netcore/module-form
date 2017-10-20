<a href="{{ route('admin::form.entries.show', [$form, $entry['id']]) }}"
   class="btn btn-default btn-xs">
    <i class="fa fa-eye"></i> View
</a>
<a href="{{ route('admin::form.entries.destroy', [$form, $entry['id']]) }}"
   class="btn btn-danger btn-xs confirm-delete" data-id="{{ $form->id }}">
    <i class="fa fa-trash"></i> Delete
</a>
