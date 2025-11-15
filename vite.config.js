import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/app.js'],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  server: {
    host: '0.0.0.0',
    port: 5173,
    https: {
      key: './docker/certs/dev.board.filkx.com-key.pem',
      cert: './docker/certs/dev.board.filkx.com.pem',
    },
    hmr: {
      host: 'dev.board.filkx.com',
      protocol: 'wss',
      port: 5173,
    },
  },
});
