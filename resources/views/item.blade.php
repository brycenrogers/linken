<div class="container-item-pane container">
    <div class="col-md-12">
        <div class="media link-padding">
            <div class="media-left">
                @if (get_class($item->itemable) == "App\Models\Link")
                    <?php $photo_url = asset('/assets/images/new-link.png') ?>
                    @if ($item->itemable->photo)
                        <?php $photo_url = asset('assets/images/thumbs/' . $item->itemable->photo) ?>
                    @endif
                    <a href="{{ $item->itemable->url }}">
                        <div class="media-link-image" style="background-image: url('{{ $photo_url }}')"></div>
                    </a>
                @else
                    <?php $photo_url = asset('/assets/images/new-note.png') ?>
                    <div class="media-link-image" style="background-image: url('{{ $photo_url }}')"></div>
                @endif
            </div>
            <div class="media-body">
                @if (get_class($item->itemable) == "App\Models\Link")
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
                        @if ($item->user == Auth::user())
                        <a href="#itemSettingsModal"
                           class="settings-link"
                           title="Settings"
                           data-toggle="modal"
                           data-itemid="{{ $item->id }}"
                           data-type="{{ (get_class($item->itemable) == "App\Models\Link") ? "Link" : "Note" }}"
                           data-value="{{ $item->value }}"
                           data-description="{{ $item->description }}">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        @endif
                        <a href="#itemShareModal" class="share-link" title="Share" data-toggle="modal">
                            <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>