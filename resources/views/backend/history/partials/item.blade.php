<div>
    <i class="fa fa-{{ $historyItem->icon }} {{ $historyItem->class }}"></i>

    <div class="timeline-item">
        <span class="time"><i class="fas fa-clock"></i> {{ $historyItem->created_at->diffForHumans() }}</span>

        <h3 class="timeline-header"><strong>{{ $historyItem->user->name }}</strong> {!! history()->renderDescription($historyItem->text, $historyItem->assets) !!}</h3>
    </div><!--timeline-item-->
</div>