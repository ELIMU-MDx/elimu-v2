<label x-data="{active: @entangle($attributes->wire('model')).defer}" type="button"
       class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
       :class="{'bg-indigo-600': active, 'bg-gray-200': !active}"
       role="switch"
       @click="active = !active"
       :aria-checked="active">
    <span class="sr-only">{{$label}}</span>
    <span aria-hidden="true"
          class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
          :class="{'translate-x-5': active, 'translate-x-0': !active}"
    ></span>
</label>
