@extends('admin.layouts.master', ['title' => 'Add Block'])

@section('content')

    <div class="row">
        <div class="col-md-12">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if ( ! empty($block_types))
                        @foreach ($block_types as $key => $block)
                            <tr>
                                <td>
                                    <h4>{{ $block['name'] }}</h4>
                                    <p>{{ $block['description'] }}</>
                                </td>
                                <td>
                                    {!!link_to_route('admin.blocks.create.type', 'Create', $key) !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2">There are no block types defined</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@stop
