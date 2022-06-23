<div class="card" style="width: 18rem;">
    <div class="card-body">
        <h3 class="card-title">{{ $title }}</h3>
        <h4 class="card-subtitle mb-2 text-muted">
            {{ $subtitle }}
        </h4>
    </div>
    <ul class="list-group list-group-flush">
        @if (is_a($items ,'Illuminate\Support\Collection'))
            @foreach ($items as $item)
                <li class="list-group-item">
                    {{ $item }}
                </li>
            @endforeach
        @else
        {{ $items }}
        @endif
       
    </ul>
</div>