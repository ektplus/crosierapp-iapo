'use strict';

let listId = "#veiculoList";

import routes from '../../static/fos_js_routes.json';
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

import Moment from 'moment';

import $ from "jquery";

Routing.setRoutingData(routes)

function getDatatablesColumns() {
    return [
        {
            name: 'e.prefixo',
            data: 'e.prefixo',
            title: 'Prefixo'
        },
        {
            name: 'e.apelido',
            data: 'e.apelido',
            title: 'Apelido'
        },
        {
            name: 'e.placa',
            data: 'e.placa',
            title: 'Placa'
        },
        {
            name: 'e.status',
            data: 'e.status',
            title: 'Status'
        },
        {
            name: 'e.id',
            data: 'e',
            title: '',
            render: function (data, type, row) {
                let colHtml = "";
                if ($(listId).data('routeedit')) {
                    let routeedit = Routing.generate($(listId).data('routeedit'), {id: data.id});
                    colHtml += DatatablesJs.makeEditButton(routeedit);
                }
                if ($(listId).data('routedelete')) {
                    let deleteUrl = Routing.generate($(listId).data('routedelete'), {id: data.id});
                    let csrfTokenDelete = $(listId).data('crsf-token-delete');
                    colHtml += DatatablesJs.makeDeleteButton(deleteUrl, csrfTokenDelete);
                }
                colHtml += '<br /><span class="badge badge-pill badge-info">' + Moment(data.updated).format('DD/MM/YYYY HH:mm:ss') + '</span> ';
                return colHtml;
            },
            className: 'text-right'
        }
    ];
}

DatatablesJs.makeDatatableJs(listId, getDatatablesColumns());