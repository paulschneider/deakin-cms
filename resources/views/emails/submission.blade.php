@extends('emails.general')

@section('content')
    <table cellpadding="0" cellpadding="0" width="100%">
        @foreach ($submission as $field => $value)
            <tr>
                <th>{{ ucfirst(str_replace('_', ' ', $field)) }}:</th>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
    </table>
@endsection