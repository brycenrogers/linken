{{ $user->name }} sent you something from <a href="https://linken.me">Linken</a>
@if (get_class($item->itemable) == "App\Models\Link")
    <img src="<?php echo $message->embed(asset('assets/images/thumbs/' . $item->itemable->photo)); ?>">
@endif
<div class="title">
    @if (get_class($item->itemable) == "App\Models\Link")
        <a href="{{ $item->itemable->url }}">{{ $item->value }}</a>
    @else
        {{ $item->value }}
    @endif
</div>
@if ($item->description)
    <div class="description">
        {{ $item->description }}
    </div>
@endif