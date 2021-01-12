window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.iziToast = require('iziToast');

window.swal = require('sweetalert');

window.select2 = require('select2');

window.mask = require('jquery-mask-plugin');

window.moment = require('moment');

window.daterangepicker = require('daterangepicker');

window.datepicker = require('bootstrap-datepicker');

window.Chart = require('chart.js');

// window.Cleave = require('cleave.js');
// import "cleave.js/dist/addons/cleave-phone.ph.js";

// var cleavePN = new Cleave('.phone-number', {
//     phone: true,
//     phoneRegionCode: 'us'
//   });

// window.Cleave = require('cleave.js');
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
