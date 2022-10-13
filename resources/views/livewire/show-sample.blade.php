<div>
    <div class="grid grid-cols-4 gap-4" x-data="chart()">
        <div class="shadow sm:rounded-lg bg-white p-4">
            <h2 class="px-4 uppercase text-sm text-gray-500 tracking-wider font-bold">Filters</h2>
            <form class="divide-y divide-gray-200">
                <x-measurement-filter label="Targets" field="filters.target"
                                      :show="true"
                                      :options="$sample->measurements->pluck('target')->unique()"/>
                <x-measurement-filter label="Positions" field="filters.position"
                                      :show="false"
                                      :options="$sample->measurements->pluck('position')->unique()"/>
                <x-measurement-filter label="Experiment" field="filters.experiment"
                                      :show="false"
                                      :options="$sample->measurements->pluck('experiment.name')->unique()"/>
                <div class="pt-4 pb-4" x-data="{show: false}">
                    <fieldset class="min-w-full">
                        <legend class="w-full px-2">
                            <button type="button"
                                    @click="show = !show"
                                    class="flex w-full items-center justify-between p-2 text-gray-400 cursor-pointer hover:text-gray-500"
                                    aria-controls="filter-section-0" :aria-expanded="show">
                                <span class="text-sm font-medium text-gray-900">Excluded</span>
                                <span class="ml-6 flex h-7 items-center">
                                    <svg class="h-5 w-5" :class="{'-rotate-180': show, 'rotate-0': !show}"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            </button>
                        </legend>
                        <div class="px-4 pt-4 pb-2 max-w-full" id="filter-section-0" x-show="show">
                            <div class="space-y-6">
                                <div class="flex items-center max-w-full">
                                    <input id="filter-excluded" wire:model="filters.excluded" type="checkbox"
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-excluded"
                                           class="ml-3 text-sm cursor-pointer text-gray-500 flex-1 block break-all">Show
                                        excluded</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </form>
        </div>
        <div class="col-span-3">
            <div class="shadow sm:rounded-lg bg-white p-4">
                <div x-ref="chart" wire:ignore></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('chart', () => ({
                    chart: null,

                    init() {
                        this.measurements = @json($options);
                        this.drawChart()
                        Livewire.on('updated', (data) => {
                            console.log(data);
                            this.updateChart(data.series)
                        })
                    },

                    updateChart(series) {
                        this.chart.updateSeries(series);
                    },

                    drawChart() {
                        if (this.chart) {
                            this.chart.destroy();
                        }

                        let options = @json($options);

                        this.chart = new ApexCharts(this.$refs.chart, options);
                        this.chart.render()
                    }
                }))


            })
        </script>
    @endpush
</div>

