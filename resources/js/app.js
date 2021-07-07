require('./bootstrap');

import Alpine from 'alpinejs';
import Tooltip from "@ryangjchandler/alpine-tooltip";
import 'tippy.js/dist/tippy.css';

Alpine.plugin(Tooltip);
window.Alpine = Alpine;
Alpine.start();
