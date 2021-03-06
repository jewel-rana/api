/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import moment from 'moment';
import popper from 'popper.js';
import progressbar from 'vue-progressbar';
import { Form, HasError, AlertError } from 'vform';
import swal from 'sweetalert2';

//constant functions
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

//add event to window
window.Form = Form;
window.swal = swal;
window.toast = toast;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//Custom Events
window.Fire = new Vue();

//uses packeges
Vue.use(VueRouter);
Vue.use(progressbar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '4px'
});

//vue routes
let routes = [
    { path: '/dashboard', component: require('./components/dashboard/home/Index.vue') },
    { path: '/dashboard/ftp', component: require('./components/dashboard/ftp/Index.vue') },
    { path: '/dashboard/ftp/view/{id}', component: require('./components/dashboard/ftp/View.vue') },
    { path: '/dashboard/payment', component: require('./components/dashboard/payment/Index.vue') },
    { path: '/dashboard/user', component: require('./components/dashboard/user/Index.vue') }
];

const router = new VueRouter({
    mode: 'history',
	routes //short for routes: routes
});

/*
* Filters
*/
Vue.filter('ucFirst', function( text ){
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('humanDate', function( date ){
    return moment(date).format('LL');
});

Vue.filter('humanTime', function( date ){
    return moment(date).format('h:mm a');
});

Vue.filter('humanDateTime', function( date ){
    return moment(date).format('lll');
});

Vue.filter('niceDateTime', function( date ){
    return moment(date).format('MMMM Do YYYY');
});
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