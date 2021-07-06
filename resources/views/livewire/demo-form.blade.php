<form class="space-y-6">

    <x-primary-button as="label" wire:loading.class="!bg-indigo-400" wire:target="rdml">
        <input type="file" class="hidden" wire:model="rdml">
        <span wire:loading wire:target="rdml">Uploading...</span>
        <span wire:loading.remove wire:target="rdml">
            @if($rdml)
                {{$rdml->getClientOriginalName()}} ({{round($rdml->getSize() / 1024, 2)}} KB)
            @else
                Choose .rdml File
            @endif
        </span>
    </x-primary-button>

    @foreach($targets as $key => $target)
        <div class="space-y-6" x-data="{quantify: @entangle('targets.'.$key.'.quantify').defer}">
            <h3 class="font-bold text-2xl bg-gray-50 p-4 -mx-4 rounded">{{$target['target']}}
                | {{$target['fluor']}}</h3>
            <div class="grid md:grid-cols-4 md:items-center">
                <div class="col-span-2">
                    <x-label for="threshold-{{$key}}">Threshold</x-label>
                </div>
                <div class="col-span-2">
                    <x-input name="threshold-{{$key}}"/>
                </div>
            </div>

            <div class="grid md:grid-cols-4 md:items-center">
                <div class="col-span-2">
                    <x-label for="cutoff-{{$key}}">Cutoff</x-label>
                </div>
                <div class="col-span-2">
                    <x-input name="cutoff-{{$key}}"/>
                </div>
            </div>
            <div class="grid md:grid-cols-4 md:items-center">
                <div class="col-span-2">
                    <x-label for="cutoff_stddev-{{$key}}">Cutoff Stddev</x-label>
                </div>
                <div class="col-span-2">
                    <x-input name="cutoff_stddev-{{$key}}"/>
                </div>
            </div>

            <div class="grid md:grid-cols-4 md:items-center">
                <div class="col-span-2">
                    <x-label for="quantify-{{$key}}">Quantify</x-label>
                </div>
                <div class="col-span-2">
                    <label class="flex items-center">
                        <x-checkbox name="quantify-{{$key}}" value="1" x-model="quantify"/>
                        <span class="ml-4">Yes</span>
                    </label>
                </div>
            </div>

            <div class="grid md:grid-cols-4 md:items-center" x-show="quantify">
                <div class="col-span-2">
                    <x-label for="slope-{{$key}}">Slope</x-label>
                </div>
                <div class="col-span-2">
                    <x-input name="slope-{{$key}}"/>
                </div>
            </div>

            <div class="grid md:grid-cols-4 md:items-center" x-show="quantify">
                <div class="col-span-2">
                    <x-label for="intercep-{{$key}}">Intercept</x-label>
                </div>
                <div class="col-span-2">
                    <x-input name="intercept-{{$key}}"/>
                </div>
            </div>

            <div class="grid md:grid-cols-4 md:items-center">
                <div class="col-span-2">
                    <x-label for="repetitions-{{$key}}">Required repetitions</x-label>
                </div>
                <div class="col-span-2">
                    <x-input name="repetitions-{{$key}}" type="number" min="1" value="1"/>
                </div>
            </div>
        </div>
    @endforeach

    @if($rdml)
        <x-primary-button type="submit">
            Analyze
        </x-primary-button>
    @endif
</form>
