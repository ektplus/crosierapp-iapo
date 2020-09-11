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

    let $filter_dts = $('#filter_dts');

    let $filter_cidadeOrigem = $('#filter_cidadeOrigem');

    $filter_cidadeOrigem.select2({
            width: '100%',
            data: $filter_cidadeOrigem.data('options')
        }
    ).on('select2:select', function () {

    });


    $filter_dts.daterangepicker(
        {
            opens: 'left',
            autoApply: true,
            locale: {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "Aplicar",
                "cancelLabel": "Cancelar",
                "fromLabel": "De",
                "toLabel": "Até",
                "customRangeLabel": "Custom",
                "daysOfWeek": [
                    "Dom",
                    "Seg",
                    "Ter",
                    "Qua",
                    "Qui",
                    "Sex",
                    "Sáb"
                ],
                "monthNames": [
                    "Janeiro",
                    "Fevereiro",
                    "Março",
                    "Abril",
                    "Maio",
                    "Junho",
                    "Julho",
                    "Agosto",
                    "Setembro",
                    "Outubro",
                    "Novembro",
                    "Dezembro"
                ],
                "firstDay": 0
            },
            ranges: {
                'Hoje': [Moment(), Moment()],
                'Próximos 7 dias': [Moment(), Moment().add(7, 'days')],
                'Próximos 15 dias': [Moment(), Moment().add(15, 'days')],
                'Próximos 30 dias': [Moment(), Moment().add(30, 'days')],
            },
            "alwaysShowCalendars": true
        },
        function (start, end, label) {

        }
    ).on('apply.daterangepicker', function (ev, picker) {

    });


});