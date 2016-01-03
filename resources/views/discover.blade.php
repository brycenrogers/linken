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
            <div class="blurb">
                Here you can find links added by other users based on common tagging interests
            </div>
            <table id="discover-table">
                @foreach ($items as $tag => $itemArray)
                    <tr>
                        <td class="discover-sidebar-td">
                            <div class="discover-sidebar-container">
                                {{ $tag }}
                            </div>
                        </td>
                        <td class="discover-list-td">
                            <div class="discover-list-container">
                                @each('item', $itemArray, 'item')
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

@endsection