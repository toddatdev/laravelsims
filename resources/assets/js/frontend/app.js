
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');
require('../AdminLTE/js/AdminLTE');

//Moment JS (time library)
require('moment');
require('moment-timezone');
window.moment = require('moment');

/**
 * JQuery DataTables (DataTable Default Styling)
 */
require( 'jszip' );
require( 'pdfmake' );
require( 'datatables.net-dt' );
require( 'datatables.net-buttons-dt' );
require( 'datatables.net-buttons/js/buttons.colVis.js' );
require( 'datatables.net-buttons/js/buttons.html5.js' );
require( 'datatables.net-buttons/js/buttons.print.js' );
require( 'datatables.net-fixedheader-dt' );
require( 'datatables.net-keytable-dt' );
require( 'datatables.net-responsive-dt' );
require( 'datatables.net-rowgroup-dt' );
require( 'datatables.net-rowreorder-dt' );
require( 'datatables.net-select-dt' );

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/* Comment Out Not Using Vue.js ... yet -TC 2019-11-18
window.Vue = require('vue');


Vue.component('example', require('../components/frontend/Example.vue'));

const app = new Vue({
    el: '#app'
});
*/