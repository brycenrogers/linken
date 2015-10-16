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
                        <div class="media-footer">
                            <div class="media-tags">
                                @foreach ($item->tags as $tag)
                                    <a href="/tags?q={{ $tag->name }}" class="tag-link">
                                        <span class="label label-tag">{{ $tag->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="media-options">
                                <a href="#itemSettingsModal"
                                   class="settings-link"
                                   title="Settings"
                                   data-toggle="modal"
                                   data-type="{{ (get_class($item->itemable) == "App\Link") ? "Link" : "Note" }}"
                                   data-value="{{ $item->value }}"
                                   data-description="{{ $item->description }}">
                                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                </a>
                                <a href="#itemShareModal" class="share-link" title="Share" data-toggle="modal">
                                    <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @if (!is_array($items))
        <div id="pager-container">
            {!! $items->render() !!}
        </div>
    @endif
@endsection

<!-- Item Settings Modal -->
<div class="modal fade" id="itemSettingsModal" tabindex="-1" role="dialog" aria-labelledby="itemSettingsModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="itemSettingsModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="settingsValue">Title</label>
                    <textarea name="value" id="settingsValue" class="form-control input-lg" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="settingsValue">Description</label>
                    <textarea name="description" id="settingsDescription" class="form-control input-lg" rows="6"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Item Share Modal -->
<div class="modal fade" id="itemShareModal" tabindex="-1" role="dialog" aria-labelledby="itemShareModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="itemShareModalLabel">Share</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Send</button>
            </div>
        </div>
    </div>
</div>