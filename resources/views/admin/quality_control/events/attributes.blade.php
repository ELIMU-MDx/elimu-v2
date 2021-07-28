<div class="text-sm text-gray-500 mt-2 flex">
    <dl class="space-y-2">
        @foreach($data as $attribute => $value)
            <div class="grid grid-cols-1 sm:grid-cols-audit gap-x-4">
                <dt class="font-semibold truncate">{{$attribute}}</dt>
                <dd>{{$value}}</dd>
            </div>
        @endforeach
    </dl>
</div>
