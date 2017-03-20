            @if ($history->count())
                <h2>History</h2>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date Changed</th>
                            <th>Field</th>
                            <th>User</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $item )
                            <tr>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->fieldName() }}</td>
                                <td>{{ $item->userResponsible()->name }}</td>
                                <td>View @todo</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif