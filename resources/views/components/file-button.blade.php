@props(['name', 'id', 'class' => '', 'file' => null, 'limit' => 15])
<input type="file"
       id="{{$id ?? $name}}"
       name="{{$name}}"
       class="sr-only peer"
       {{$attributes}}
       @if($attributes->wire('model'))
       wire:target="{{$attributes->wire('model')}}" wire:loading.attr="disabled"
        @endif>
<label
        for="{{$id ?? $name}}"
        class="inline-flex items-center px-4 py-3 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 shadow-sm cursor-pointer hover:text-gray-500 peer-focus:outline-none peer-focus:border-indigo-200 peer-focus:ring peer-focus:ring-blue-200 peer-active:text-gray-800 peer-active:bg-gray-50 disabled:opacity-25 transition {{$class}}">

    @if($attributes->wire('model'))
        <span wire:loading wire:target="{{$attributes->wire('model')->value()}}">Uploading...</span>
        <span wire:loading.remove wire:target="{{$attributes->wire('model')->value()}}">
            @if(is_array($file) && count($file) === 1)
                {{Str::limit($file[0]->getClientOriginalName(), $limit)}} ({{round($file[0]->getSize() / 1024, 2)}} KB)
            @elseif($file)
                {{count($file)}} Rdml Files
            @else
                <span class="uppercase tracking-widest">{{$slot}}</span>
            @endif
        </span>
    @else
        <span class="uppercase tracking-widest">{{$slot}}</span>
    @endif
</label>
