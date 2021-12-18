/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.component('pagination', require('laravel-vue-pagination'));


import dayjs from "dayjs";
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import VueProgressBar from 'vue-progressbar';

const options = {
  color: '#29d',
  failedColor: '#874b4b',
  thickness: '2px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}

Vue.use(VueProgressBar, options)


//Sweet Alert Start
import Swal from 'sweetalert2'
  window.Swal = Swal; 
//window.swal = toast;
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast;
//Sweet Alert top-end

import Popper from 'popper.js';
window.Popper = Popper;

import { Form, HasError, AlertError } from 'vform';
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import VueRouter from 'vue-router'
Vue.use(VueRouter)

Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  return value.charAt(0).toUpperCase() + value.slice(1);
});

Vue.filter('myDate', function (created) {
  return dayjs(created).format('MMMM DD YYYY');
});


let routes = [
  { path: '/dashboard', component: require('./components/Dashboard.vue').default  },
  { path: '/developer', component: require('./components/Developer.vue').default  },
  { path: '/users', component: require('./components/Users.vue').default  },
  { path: '/profile', component: require('./components/Profile.vue').default  }
]


const router = new VueRouter({
	mode: "history",
  routes // short for `routes: routes`
})
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Universal Event For Search

window.Fire = new Vue();

//Universal Event End

const app = new Vue({
    el: '#app',
    router,

    // Search Start
    data:{
      search: ''
    },

    methods:{

      searchit : _.debounce(() => {
        console.log('searching');
        Fire.$emit('searching');
      }, 1000)
      

         
    }
    // Search End
});


// Passport Start

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

// Passport End


