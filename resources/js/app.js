import './bootstrap';

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.PUSER_APP_KEY,
    cluster: process.env.PUSER_APP_CLUSTER,
    forceTLS: true,
});