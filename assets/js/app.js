
import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueMeta from 'vue-meta';
import relativeDateFilter from "./filters/relative-date-filter";
import prettyDateFilter from "./filters/pretty-date-filter";
import VueGtag from "vue-gtag";
import * as Sentry from "@sentry/vue";
import { BrowserTracing } from "@sentry/tracing";
import GmapVue from 'gmap-vue';
import Buefy from 'buefy'
import PerfectScrollbar from 'vue2-perfect-scrollbar'
import VueProgressBar from 'vue-progressbar'

Vue.use(Buefy, {
  defaultIconPack: 'fas',
  defaultProgrammaticPromise: true
});

if (process.env.SENTRY_DSN) {
  Sentry.init({
    Vue,
    dsn: process.env.SENTRY_DSN,
    integrations: [
      new BrowserTracing({
        routingInstrumentation: Sentry.vueRouterInstrumentation(router),
        tracingOrigins: ["127.0.0.1", "www.musicall.com", /^\//],
      }),
    ],
    // Set tracesSampleRate to 1.0 to capture 100%
    // of transactions for performance monitoring.
    // We recommend adjusting this value in production
    tracesSampleRate: 1.0,
  });
}

Vue.use(VueProgressBar, {});

Vue.use(PerfectScrollbar);

Vue.use(VueMeta, {
  // optional pluginOptions
  refreshOnceOnNavigation: true
});

Vue.use(VueGtag, {
  config: { id: "G-1CK1G9W6FX" }
}, router);

Vue.use(GmapVue, {
  load: {
    key: process.env.GOOGLE_API_KEY_FRONT,
    libraries: 'places',
  },
  installComponents: true
})


Vue.filter('relativeDate', relativeDateFilter);
Vue.filter('prettyDate', prettyDateFilter);

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(App),
  metaInfo: () => ({
    title: 'MusicAll, le site de référence au service de la musique',
    meta: [
      {vmid: 'description', name: 'description', 'content': 'Site communautaire au service de la musique. Articles et cours sur la musique, annuaire de musiciens, forums, ...'},
      { property: "og:type", content: 'website' },
      { property: "og:locale", content: 'fr_FR' },
      { property: "og:image", content: window.location.origin + '/build/images/facebook-logo.jpg' },
      { property: "og:site_name", content: 'MusicAll' },
    ]
  })
}).$mount("#app");