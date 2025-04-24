import './bootstrap';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
window.toastr = toastr;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
