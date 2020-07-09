'use strict';

let listId = "#viagemList";

import DatatablesJs from '../crosier/DatatablesJs';

import routes from '../../static/fos_js_routes.json';
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

import Moment from 'moment';

import $ from "jquery";

Routing.setRoutingData(routes)

function getDatatablesColumns() {
    return [
        {
            name: 'e.pedido',
            data: 'e.pedido',
            title: 'Pedido'
        },
        {
            name: 'e.dtHrSaida',
            data: 'e.dtHrSaida',
            title: 'Saída',
            render: function (data, type, row) {
                return Moment(data, Moment.ISO_8601, true).format('DD/MM/YYYY HH:mm:ss');
            }
        },
        {
            name: 'e.dtHrRetorno',
            data: 'e.dtHrRetorno',
            title: 'Retorno',
            render: function (data, type, row) {
                return Moment(data, Moment.ISO_8601, true).format('DD/MM/YYYY HH:mm:ss');
            }
        },
        {
            name: 'e.itinerario',
            data: 'e.itinerario.descricaoMontada',
            title: 'Itinerário',
        },
        {
            name: 'e.veiculo',
            data: 'e',
            title: 'Veículo / Motorista',
            render: function (data, type, row) {
                return data.veiculo.apelido + ' / ' + data.motorista.nome;
            }
        },
        {
            name: 'e.agencia',
            data: 'e.agencia.nome',
            title: 'Agência',
        },
        {
            name: 'e.status',
            data: 'e.status',
            title: 'Status',
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