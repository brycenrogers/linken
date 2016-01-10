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
            <div id="discover-container">
                @foreach ($items as $tag => $itemArray)
                    <div class="discover-header">
                        <div class="discover-header-container">
                            {{ $tag }}
                        </div>
                    </div>
                    <div class="discover-list-item">
                        <div class="discover-list-container">
                            @each('item', $itemArray, 'item')
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection