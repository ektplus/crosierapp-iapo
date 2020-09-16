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



$(document).ready(function () {

    let $senha1 = $('#dadosCliente_senha1');
    let $senha2 = $('#dadosCliente_senha2');


    function validatePassword(){
        console.log('a');
        if($senha1.val() !== $senha2.val()) {
            $senha2.addClass('is-invalid');
            $senha2[0].setCustomValidity('As senhas não são iguais.')
        } else {
            $senha2.removeClass('is-invalid');
            $senha2[0].setCustomValidity('');
        }
    }

    $senha1.change(function() {validatePassword()});
    $senha2.change(function() {validatePassword()});

    console.log('aaaa');

});