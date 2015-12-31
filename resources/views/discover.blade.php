@extends('layouts.layout')

@section('title', $title)

@section('addPane')
    @include('panes.addPane')
@endsection

@section('controlPane')
    @include('panes.controlPane')
@endsection

@section('pageContent')

    <div class="row">
        <div class="col-md-12">
            <table id="discover-table">
                <tr>
                    <td id="discover-sidebar-td">

                    </td>
                    <td id="discover-list-td">
                        <div id="discover-list-container">
                            @each('item', $items, 'item')
                        </div>
                        @if ( ! is_array($items))
                            <div id="pager-container">
                                {!! $items->render() !!}
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

@endsection