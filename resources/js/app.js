import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Tooltip from "@ryangjchandler/alpine-tooltip";
import 'tippy.js/dist/tippy.css';

Alpine.plugin(Tooltip);
Livewire.start();
