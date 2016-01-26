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
        <div class="col-md-4">
            <select multiple class="form-control input-lg select2" id="discover-tags" style="width: 100%; padding: 10px;" aria-hidden="true"></select>
        </div>
        <div class="col-md-8">
            <div class="blurb">
                <span class="pull-right">
                    Here you can find links added by other users based on common tagging interests
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
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