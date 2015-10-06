@extends('layouts.layout')

@section('title', ' - All')

@section('addPane')
    @include('panes.addPane')
@endsection

@section('controlPane')
    @include('panes.controlPane')
@endsection

@section('pageContent')
    @foreach ($items as $item)
        <div class="container-link-pane container">
            <div class="col-md-12">
                <div class="media link-padding">
                    <div class="media-left">
                        @if (get_class($item->itemable) == "App\Link")
                            <a href="#">
                                <img class="media-object" src="{{ $item->itemable->photo }}" alt="">
                            </a>
                        @endif
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">{{ $item->value }}</h4>
                        {{ $item->description }}
                        @if (get_class($item->itemable) == "App\Link")
                            <div class="media-url">
                                {{ $item->itemable->url }}
                            </div>
                        @endif
                        <div class="media-tags">
                            @foreach ($item->tags as $tag)
                                {{ $tag->name }}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection