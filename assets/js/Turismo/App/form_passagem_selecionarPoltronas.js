'use strict';

import $ from "jquery";

import 'daterangepicker';

import routes from '../../../static/fos_js_routes.json';
import Routing from '../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

import 'select2/dist/css/select2.css';
import 'select2';
import 'select2/dist/js/i18n/pt-BR.js';
import 'select2-bootstrap-theme/dist/select2-bootstrap.css';

$.fn.select2.defaults.set("theme", "bootstrap");
$.fn.select2.defaults.set("language", "pt-BR");

Routing.setRoutingData(routes);

import Moment from 'moment';

$(document).ready(function () {

    let $btnsSelPoltrona = $('.btnSelPoltrona');

    $btnsSelPoltrona.on('click', function() {

    });


    function iniForm() {

    }

    iniForm();

});