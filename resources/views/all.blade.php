@extends('layouts.layout')

@section('title', ' - All')

@section('addPane')
    @include('panes.addPane')
@endsection

@section('controlPane')
    @include('panes.controlPane')
@endsection

@section('pageContent')
<div class="container-link-pane container">
    <div class="col-md-12">
        <div class="media link-padding">
            <div class="media-left">
                <a href="#">
                    <img class="media-object" src="" alt="">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading">Middle aligned media</h4>
                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.
                Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla.
                Donec lacinia congue felis in faucibus. Donec sed odio dui. Nullam quis risus eget urna mollis ornare vel eu leo.
                Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                <div class="media-url">
                    google.com/test/stuff
                </div>
                <div class="media-tags">
                    programming, apple, stuff
                </div>
            </div>
        </div>
    </div>
</div>
@endsection