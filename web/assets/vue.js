import Vue from 'vue';
import VueResource from 'vue-resource'
Vue.use(VueResource);

import AppMain from './App';

Vue.config.productionTip = false;

let app = new Vue({
  el: '#vueApp',
  template: '<app-main></app-main>',
  components: { AppMain }
})