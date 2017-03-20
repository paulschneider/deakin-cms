    <div class="modal inmodal fade" id="all-commands" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">All Commands</h4>
                    <small class="font-bold">A listing of available commands to php artisan</small>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered table-hover">
                        @foreach (Artisan::all() as $command => $details)
                            <tr>
                                <td>{{ $command }}</td>
                                <td>
                                {{ $details->getDescription() }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>