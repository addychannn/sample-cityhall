// vite.config.js
import { loadEnv } from "file:///C:/Users/AddyChan/Documents/GitHub/egov-smoke-free/client/node_modules/vite/dist/node/index.js";
import path from "path";
import vue from "file:///C:/Users/AddyChan/Documents/GitHub/egov-smoke-free/client/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import { VitePWA } from "file:///C:/Users/AddyChan/Documents/GitHub/egov-smoke-free/client/node_modules/vite-plugin-pwa/dist/index.mjs";
import { createHtmlPlugin } from "file:///C:/Users/AddyChan/Documents/GitHub/egov-smoke-free/client/node_modules/vite-plugin-html/dist/index.mjs";
var __vite_injected_original_dirname = "C:\\Users\\AddyChan\\Documents\\GitHub\\egov-smoke-free\\client";
var vite_config_default = ({ mode }) => {
  const env = loadEnv(mode, process.cwd());
  return {
    css: {
      devSourcemap: true
    },
    plugins: [
      vue(),
      VitePWA({
        registerType: "autoUpdate",
        injectRegister: "auto",
        devOptions: {
          enabled: true
        },
        workbox: {
          globPatterns: ["**/*.{js,css,html,ico,png,svg,vue}"]
        },
        includeAssets: [
          "favicons/favicon.ico",
          "favicons/apple-touch-icon.png",
          "favicons/masked-icon.svg"
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
              type: "image/png"
            },
            {
              src: "favicons/android-chrome-512x512.png",
              sizes: "512x512",
              type: "image/png"
            }
          ]
        }
      }),
      createHtmlPlugin({
        minify: true,
        inject: {
          data: {
            PRODUCT_NAME: env.VITE_PRODUCT_NAME,
            BASE_URL: "/"
          }
        }
      })
    ],
    resolve: {
      alias: {
        "@vue/runtime-core": "@vue/runtime-core/dist/runtime-core.esm-bundler.js",
        "@": path.resolve(__vite_injected_original_dirname, "./src")
      }
    },
    server: {
      host: "127.0.0.1",
      https: false,
      port: 8080,
      open: "http://127.0.0.1:8080"
      // opens browser window automatically
    },
    transpileDependencies: true
  };
};
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxBZGR5Q2hhblxcXFxEb2N1bWVudHNcXFxcR2l0SHViXFxcXGVnb3Ytc21va2UtZnJlZVxcXFxjbGllbnRcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXEFkZHlDaGFuXFxcXERvY3VtZW50c1xcXFxHaXRIdWJcXFxcZWdvdi1zbW9rZS1mcmVlXFxcXGNsaWVudFxcXFx2aXRlLmNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vQzovVXNlcnMvQWRkeUNoYW4vRG9jdW1lbnRzL0dpdEh1Yi9lZ292LXNtb2tlLWZyZWUvY2xpZW50L3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgbG9hZEVudiB9IGZyb20gXCJ2aXRlXCI7XHJcbmltcG9ydCBwYXRoIGZyb20gXCJwYXRoXCI7XHJcbmltcG9ydCB2dWUgZnJvbSBcIkB2aXRlanMvcGx1Z2luLXZ1ZVwiO1xyXG5pbXBvcnQgeyBWaXRlUFdBIH0gZnJvbSBcInZpdGUtcGx1Z2luLXB3YVwiO1xyXG5pbXBvcnQgeyBjcmVhdGVIdG1sUGx1Z2luIH0gZnJvbSBcInZpdGUtcGx1Z2luLWh0bWxcIjtcclxuXHJcbi8vIGh0dHBzOi8vdml0ZWpzLmRldi9jb25maWcvXHJcbmV4cG9ydCBkZWZhdWx0ICh7IG1vZGUgfSkgPT4ge1xyXG4gIGNvbnN0IGVudiA9IGxvYWRFbnYobW9kZSwgcHJvY2Vzcy5jd2QoKSk7XHJcbiAgcmV0dXJuIHtcclxuICAgIGNzczoge1xyXG4gICAgICBkZXZTb3VyY2VtYXA6IHRydWUsXHJcbiAgICB9LFxyXG4gICAgcGx1Z2luczogW1xyXG4gICAgICB2dWUoKSxcclxuICAgICAgVml0ZVBXQSh7XHJcbiAgICAgICAgcmVnaXN0ZXJUeXBlOiBcImF1dG9VcGRhdGVcIixcclxuICAgICAgICBpbmplY3RSZWdpc3RlcjogXCJhdXRvXCIsXHJcbiAgICAgICAgZGV2T3B0aW9uczoge1xyXG4gICAgICAgICAgZW5hYmxlZDogdHJ1ZSxcclxuICAgICAgICB9LFxyXG4gICAgICAgIHdvcmtib3g6IHtcclxuICAgICAgICAgIGdsb2JQYXR0ZXJuczogW1wiKiovKi57anMsY3NzLGh0bWwsaWNvLHBuZyxzdmcsdnVlfVwiXSxcclxuICAgICAgICB9LFxyXG4gICAgICAgIGluY2x1ZGVBc3NldHM6IFtcclxuICAgICAgICAgIFwiZmF2aWNvbnMvZmF2aWNvbi5pY29cIixcclxuICAgICAgICAgIFwiZmF2aWNvbnMvYXBwbGUtdG91Y2gtaWNvbi5wbmdcIixcclxuICAgICAgICAgIFwiZmF2aWNvbnMvbWFza2VkLWljb24uc3ZnXCIsXHJcbiAgICAgICAgXSxcclxuICAgICAgICBtYW5pZmVzdDoge1xyXG4gICAgICAgICAgbmFtZTogZW52LlZJVEVfUFJPRFVDVF9OQU1FLFxyXG4gICAgICAgICAgc2hvcnRfbmFtZTogZW52LlZJVEVfU0hPUlRfTkFNRSxcclxuICAgICAgICAgIGRlc2NyaXB0aW9uOiBlbnYuVklURV9ERVNDUklQVElPTixcclxuICAgICAgICAgIHRoZW1lX2NvbG9yOiBcIiNmZmZmZmZcIixcclxuICAgICAgICAgIGljb25zOiBbXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICBzcmM6IFwiZmF2aWNvbnMvYW5kcm9pZC1jaHJvbWUtMTkyeDE5Mi5wbmdcIixcclxuICAgICAgICAgICAgICBzaXplczogXCIxOTJ4MTkyXCIsXHJcbiAgICAgICAgICAgICAgdHlwZTogXCJpbWFnZS9wbmdcIixcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgIHNyYzogXCJmYXZpY29ucy9hbmRyb2lkLWNocm9tZS01MTJ4NTEyLnBuZ1wiLFxyXG4gICAgICAgICAgICAgIHNpemVzOiBcIjUxMng1MTJcIixcclxuICAgICAgICAgICAgICB0eXBlOiBcImltYWdlL3BuZ1wiLFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgXSxcclxuICAgICAgICB9LFxyXG4gICAgICB9KSxcclxuICAgICAgY3JlYXRlSHRtbFBsdWdpbih7XHJcbiAgICAgICAgbWluaWZ5OiB0cnVlLFxyXG4gICAgICAgIGluamVjdDoge1xyXG4gICAgICAgICAgZGF0YToge1xyXG4gICAgICAgICAgICBQUk9EVUNUX05BTUU6IGVudi5WSVRFX1BST0RVQ1RfTkFNRSxcclxuICAgICAgICAgICAgQkFTRV9VUkw6IFwiL1wiLFxyXG4gICAgICAgICAgfSxcclxuICAgICAgICB9LFxyXG4gICAgICB9KSxcclxuICAgIF0sXHJcbiAgICByZXNvbHZlOiB7XHJcbiAgICAgIGFsaWFzOiB7XHJcbiAgICAgICAgXCJAdnVlL3J1bnRpbWUtY29yZVwiOlxyXG4gICAgICAgICAgXCJAdnVlL3J1bnRpbWUtY29yZS9kaXN0L3J1bnRpbWUtY29yZS5lc20tYnVuZGxlci5qc1wiLFxyXG4gICAgICAgIFwiQFwiOiBwYXRoLnJlc29sdmUoX19kaXJuYW1lLCBcIi4vc3JjXCIpLFxyXG4gICAgICB9LFxyXG4gICAgfSxcclxuICAgIHNlcnZlcjoge1xyXG4gICAgICBob3N0OiBcIjEyNy4wLjAuMVwiLFxyXG4gICAgICBodHRwczogZmFsc2UsXHJcbiAgICAgIHBvcnQ6IDgwODAsXHJcbiAgICAgIG9wZW46IFwiaHR0cDovLzEyNy4wLjAuMTo4MDgwXCIsIC8vIG9wZW5zIGJyb3dzZXIgd2luZG93IGF1dG9tYXRpY2FsbHlcclxuICAgIH0sXHJcbiAgICB0cmFuc3BpbGVEZXBlbmRlbmNpZXM6IHRydWUsXHJcbiAgfTtcclxufTtcclxuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUEyVyxTQUFTLGVBQWU7QUFDblksT0FBTyxVQUFVO0FBQ2pCLE9BQU8sU0FBUztBQUNoQixTQUFTLGVBQWU7QUFDeEIsU0FBUyx3QkFBd0I7QUFKakMsSUFBTSxtQ0FBbUM7QUFPekMsSUFBTyxzQkFBUSxDQUFDLEVBQUUsS0FBSyxNQUFNO0FBQzNCLFFBQU0sTUFBTSxRQUFRLE1BQU0sUUFBUSxJQUFJLENBQUM7QUFDdkMsU0FBTztBQUFBLElBQ0wsS0FBSztBQUFBLE1BQ0gsY0FBYztBQUFBLElBQ2hCO0FBQUEsSUFDQSxTQUFTO0FBQUEsTUFDUCxJQUFJO0FBQUEsTUFDSixRQUFRO0FBQUEsUUFDTixjQUFjO0FBQUEsUUFDZCxnQkFBZ0I7QUFBQSxRQUNoQixZQUFZO0FBQUEsVUFDVixTQUFTO0FBQUEsUUFDWDtBQUFBLFFBQ0EsU0FBUztBQUFBLFVBQ1AsY0FBYyxDQUFDLG9DQUFvQztBQUFBLFFBQ3JEO0FBQUEsUUFDQSxlQUFlO0FBQUEsVUFDYjtBQUFBLFVBQ0E7QUFBQSxVQUNBO0FBQUEsUUFDRjtBQUFBLFFBQ0EsVUFBVTtBQUFBLFVBQ1IsTUFBTSxJQUFJO0FBQUEsVUFDVixZQUFZLElBQUk7QUFBQSxVQUNoQixhQUFhLElBQUk7QUFBQSxVQUNqQixhQUFhO0FBQUEsVUFDYixPQUFPO0FBQUEsWUFDTDtBQUFBLGNBQ0UsS0FBSztBQUFBLGNBQ0wsT0FBTztBQUFBLGNBQ1AsTUFBTTtBQUFBLFlBQ1I7QUFBQSxZQUNBO0FBQUEsY0FDRSxLQUFLO0FBQUEsY0FDTCxPQUFPO0FBQUEsY0FDUCxNQUFNO0FBQUEsWUFDUjtBQUFBLFVBQ0Y7QUFBQSxRQUNGO0FBQUEsTUFDRixDQUFDO0FBQUEsTUFDRCxpQkFBaUI7QUFBQSxRQUNmLFFBQVE7QUFBQSxRQUNSLFFBQVE7QUFBQSxVQUNOLE1BQU07QUFBQSxZQUNKLGNBQWMsSUFBSTtBQUFBLFlBQ2xCLFVBQVU7QUFBQSxVQUNaO0FBQUEsUUFDRjtBQUFBLE1BQ0YsQ0FBQztBQUFBLElBQ0g7QUFBQSxJQUNBLFNBQVM7QUFBQSxNQUNQLE9BQU87QUFBQSxRQUNMLHFCQUNFO0FBQUEsUUFDRixLQUFLLEtBQUssUUFBUSxrQ0FBVyxPQUFPO0FBQUEsTUFDdEM7QUFBQSxJQUNGO0FBQUEsSUFDQSxRQUFRO0FBQUEsTUFDTixNQUFNO0FBQUEsTUFDTixPQUFPO0FBQUEsTUFDUCxNQUFNO0FBQUEsTUFDTixNQUFNO0FBQUE7QUFBQSxJQUNSO0FBQUEsSUFDQSx1QkFBdUI7QUFBQSxFQUN6QjtBQUNGOyIsCiAgIm5hbWVzIjogW10KfQo=
