@extends('layouts.layout')

@section('title', $title)

@section('addPane')
    @include('panes.addPane')
@endsection

@section('controlPane')
    @include('panes.controlPane')
@endsection

@section('pageContent')
    @foreach ($items as $item)
        <div class="container-item-pane container">
            <div class="col-md-12">
                <div class="media link-padding">
                    @if (get_class($item->itemable) == "App\Link" && $item->itemable->photo)
                        <div class="media-left">
                            <a href="{{ $item->itemable->url }}">
                                <div class="media-link-image" style="background-image: url('{{ $item->itemable->photo }}')"></div>
                            </a>
                        </div>
                    @endif
                    <div class="media-body">
                        @if (get_class($item->itemable) == "App\Link")
                            <h4 class="media-heading">
                                <a href="{{ $item->itemable->url }}">
                                    {{ $item->value }}
                                </a>
                            </h4>
                            <div class="media-url">
                                <a href="{{ $item->itemable->url }}">{{ $item->itemable->url }}</a>
                            </div>
                        @else
                            <h4 class="media-heading">
                                {{ $item->value }}
                            </h4>
                        @endif
                        @if ($item->description)
                            <div class="media-description">
                                {{ $item->description }}
                            </div>
                        @endif
                        <div class="media-tags">
                            @foreach ($item->tags as $tag)
                                <a href="/tags?q={{ $tag->name }}" class="tag-link">
                                    <span class="label label-tag">{{ $tag->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection