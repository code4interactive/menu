@foreach($items as $item)
    <li {!! $item->cAttributes() !!}>
        <a href="{{ $item->getUrl() }}" {!! $item->attributes() !!}>{!! $item->renderIcon() !!} {!! $item->getTitle() !!}</a>
    </li>
@endforeach