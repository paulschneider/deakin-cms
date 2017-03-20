
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('method' => 'PATCH', 'route' => array('admin.attachments.move'), 'class' => 'attachment-modify-selected form-inline ')) !!}
            <table class="table table-bordered table-striped table-hover attachment-table">
                <thead>
                    <tr>
                        @if ($insert)
                            <th width="100">&nbsp;</th>
                        @else
                            <th width="20">&nbsp;</th>
                        @endif
                        <th width="50">&nbsp;</th>
                        <th valign="middle">File title</th>
                        @if ($actions)
                        <th class="col-xs-2">Created</th>
                        @endif
                        <th class="col-xs-2">Updated</th>
                        @if ($actions)
                            <th class="col-xs-1">Friendly URL</th>
                            <th class="col-xs-1">Edit</th>
                            <th class="col-xs-1">Delete</th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                </tbody>
                @if ( ! $insert)
                <tfoot class="sr-only">
                    <tr>
                        <td colspan="3">
                            {!! Form::select('term_id',  Tax::vocabularyOptions(config('attachments.vocab'), 'Move selected to folder'), null, ['class' => 'form-control col-sm-6']) !!}
                        </td>
                        @if ($actions)
                            <td colspan="2">&nbsp;</td>
                        @endif
                        <td colspan="3">
                            {!! Form::button('Delete selected', array('class' => 'btn btn-danger btn-outline delete')) !!}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
            {!! Form::close() !!}

        </div>
    </div>

