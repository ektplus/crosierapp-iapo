'use strict';

import $ from 'jquery';

import routes from '../../static/fos_js_routes.json';
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

import 'bootstrap';
import 'popper.js';
import 'summernote/dist/summernote-bs4.js';
import 'summernote/dist/summernote-bs4.css';

Routing.setRoutingData(routes);


$(document).ready(function () {


    $('.summernote').summernote();


});
