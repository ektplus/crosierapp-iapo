'use strict';

let listId = "#itinerarioList";

import routes from '../../static/fos_js_routes.json';
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

import Numeral from 'numeral';
import 'numeral/locales/pt-br.js';
Numeral.locale('pt-br');

import Moment from 'moment';

import $ from "jquery";

Routing.setRoutingData(routes)

function getDatatablesColumns() {
    return [
        {
            name: 'e.origemCidade',
            data: 'e',
            title: 'Origem',
            render: function (data, type, row) {
                return data.origemCidade + ' - ' + data.origemEstado;
            }
        },
        {
            name: 'e.destinoCidade',
            data: 'e',
            title: 'Destino',
            render: function (data, type, row) {
                return data.destinoCidade + ' - ' + data.destinoEstado;
            }
        },
        {
            name: 'e.veiculo',
            data: 'e',
            title: 'Veículo',
            render: function (data, type, row) {
                return data.veiculo.apelido;
            }
        },
        {
            name: 'e.precoMin',
            data: 'e.precoMin',
            title: 'Preço Mín',
            render: function (data, type, row) {
                let val = parseFloat(data);
                return Numeral(val).format('$ 0.0,[00]');
            },
            className: 'text-right'
        },
        {
            name: 'e.precoMax',
            data: 'e.precoMax',
            title: 'Preço Máx',
            render: function (data, type, row) {
                let val = parseFloat(data);
                return Numeral(val).format('$ 0.0,[00]');
            },
            className: 'text-right'
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