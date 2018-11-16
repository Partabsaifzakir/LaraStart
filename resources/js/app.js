
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/*==========AUTHENTICATE GATE CODE==========*/
import Gate from './Gate';
Vue.prototype.$gate = new Gate(window.user);
/*==========END AUTHENTICATE GATE CODE==========*/

/*==========AUTHENTICATE GATE CODE==========*/
Vue.filter('toMoney', function (value) {
    if (!value) return '$0.00'
    
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 2
    });
  
    return  formatter.format(value); 
  });
/*==========END AUTHENTICATE GATE CODE==========*/


/*==========MOMENT CODE==========*/
import moment from 'moment';

Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('myDate', function(value) {
    if (value) {
      return moment(String(value)).format('DD-MMM-YYYY')
    }
  });
/*==========END MOMENT CODE==========*/

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
import { Form, HasError, AlertError } from 'vform'

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
/*==========END V-FORM VALIDATION CODE==========*/

Vue.component('pagination', require('laravel-vue-pagination'));

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
    { path: '/developer', component: require('./components/Developer.vue') },
    { path: '/profile', component: require('./components/Profile.vue') },
    { path: '/users', component: require('./components/Users.vue') },
    { path: '/vendors', component: require('./components/MasterForm/Vendor.vue') },
    { path: '/customers', component: require('./components/MasterForm/Customer.vue') },
    { path: '/ticket-invoices', component: require('./components/TicketInvoice/VendorTicketInvoice.vue') },
    { path: '/ct-invoices', component: require('./components/TicketInvoice/CustomerTicketInvoice.vue') },
    { name: 'ctInvoiceView', path:'/ct-invoice-view/:id', component:('ctInvoiceView', require('./components/TicketInvoice/CtInvoiceView.vue')) },
]
/*==========END ALL VUE COMPONENTS CODE==========*/

/*==========RELOADER CODE==========*/
window.Fire = new Vue();
/*==========END RELOADER CODE==========*/

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*==========ALL PASSPORT COMPONENTS CODE==========*/
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);
/*==========END ALL PASSPORT COMPONENTS CODE==========*/

/*==========ALL SVG COMPONENTS CODE==========*/
Vue.component(
    'not-found',
    require('./components/SVG/NotFound.vue')
);
Vue.component(
    'maintenance',
    require('./components/SVG/Maintenance.vue')
);
/*==========END ALL SVG COMPONENTS CODE==========*/

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    router,
    data:{
        search: ''
    },
    methods:{
        searchit: _.debounce(() => {
            Fire.$emit('searching')
        }, 500)
    }
});
