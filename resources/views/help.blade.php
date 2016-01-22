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
            How to use Linken
        </div>
    </div>

@endsection