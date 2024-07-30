import { loadEnv } from "vite";
import path from "path";
import vue from "@vitejs/plugin-vue";
import { VitePWA } from "vite-plugin-pwa";
import { createHtmlPlugin } from "vite-plugin-html";

// https://vitejs.dev/config/
export default ({ mode }) => {
  const env = loadEnv(mode, process.cwd());
  return {
    css: {
      devSourcemap: true,
    },
    plugins: [
      vue(),
      VitePWA({
        registerType: "autoUpdate",
        injectRegister: "auto",
        devOptions: {
          enabled: true,
        },
        workbox: {
          globPatterns: ["**/*.{js,css,html,ico,png,svg,vue}"],
        },
        includeAssets: [
          "favicons/favicon.ico",
          "favicons/apple-touch-icon.png",
          "favicons/masked-icon.svg",
        ],
        manifest: {
          name: env.VITE_PRODUCT_NAME,
          short_name: env.VITE_SHORT_NAME,
          description: env.VITE_DESCRIPTION,
          theme_color: "#ffffff",
          icons: [
            {
              src: "favicons/android-chrome-192x192.png",
              sizes: "192x192",
              type: "image/png",
            },
            {
              src: "favicons/android-chrome-512x512.png",
              sizes: "512x512",
              type: "image/png",
            },
          ],
        },
      }),
      createHtmlPlugin({
        minify: true,
        inject: {
          data: {
            PRODUCT_NAME: env.VITE_PRODUCT_NAME,
            BASE_URL: "/",
          },
        },
      }),
    ],
    resolve: {
      alias: {
        "@vue/runtime-core":
          "@vue/runtime-core/dist/runtime-core.esm-bundler.js",
        "@": path.resolve(__dirname, "./src"),
      },
    },
    server: {
      host: "127.0.0.1",
      https: false,
      port: 8080,
      open: "http://127.0.0.1:8080" // opens browser window automatically
    },
    transpileDependencies: true,
  };
};
