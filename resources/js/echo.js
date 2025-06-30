import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST ?? window.location.hostname,
  wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
  wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
  forceTLS: false,
  enabledTransports: ['ws', 'wss'],
  authorizer: (channel, options) => {
    return {
      authorize: (socketId, callback) => {
        window.axios
          .post('/broadcasting/auth', {
            socket_id: socketId,
            channel_name: channel.name,
          })
          .then((response) => callback(false, response.data))
          .catch((error) => callback(true, error));
      },
    };
  },
});
const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');

if (userId) {

  window.Echo.private(`App.Models.User.${user.id}`).notification((notification) => {
    notifications.value.unshift({
      ...notification,
      read_at: null,
      created_at: 'щойно',
    });
    unreadCount.value++;
  });
} else {
  console.warn('⚠️ User ID not found. Skipping private channel subscription.');
}

window.Echo.private('test.name')
  .listen('.server.created', (e) => {
    console.log('Received event:', e);
  })
  .error((error) => {
    console.error('Echo error:', error);
  });
