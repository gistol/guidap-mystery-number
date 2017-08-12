import Vue from 'vue';

import App from './App';

Vue.config.productionTip = false;

let app = new Vue({
  el: '#vueApp',
  template: '<App/>',
  components: { App }
})