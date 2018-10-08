
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/*==========MOMENT CODE==========*/
import moment from 'moment';
import { Form, HasError, AlertError } from 'vform'

Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1)
});
Vue.filter('myDate', function(date){
    return moment().format("MMM Do YY");
});
/*==========MOMENT CODE==========*/

/*==========TOAST CODE==========*/
import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

window.toast = toast;
/*==========END TOAST CODE==========*/

/*==========V-FORM VALIDATION CODE==========*/
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
/*==========END V-FORM VALIDATION CODE==========*/

/*==========PROGRESS BAR CODE==========*/
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '5px'
  })
/*==========END PROGRESS BAR CODE==========*/

/*==========VUE ROUTER CODE==========*/
import VueRouter from 'vue-router'
Vue.use(VueRouter)
/*==========END VUE ROUTER CODE==========*/

/*==========ALL VUE COMPONENTS CODE==========*/
let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue') },
    { path: '/profile', component: require('./components/Profile.vue') },
    { path: '/Users', component: require('./components/Users.vue') }
  ]
/*==========END ALL VUE COMPONENTS CODE==========*/

window.Fire = new Vue();

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    router
});
