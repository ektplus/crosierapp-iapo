'use strict';

import $ from "jquery";

import routes from '../../static/fos_js_routes.json';
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import Moment from "moment";


Routing.setRoutingData(routes);

/**
 * Este script é utilizado tanto para lançamentos de movimentações em carteiras comuns quanto em caixas.
 */
$(document).ready(function () {

    let $cpf = $('#passageiro_cpf');

    let $rg = $('#passageiro_rg');
    let $dtNascimento = $('#passageiro_dtNascimento');
    let $nome = $('#passageiro_nome');
    let $foneFixo = $('#passageiro_foneFixo');
    let $foneCelular = $('#passageiro_foneCelular');
    let $foneWhatsapp = $('#passageiro_foneWhatsapp');
    let $foneRecados = $('#passageiro_foneRecados');
    let $email = $('#passageiro_email');
    let $obs = $('#passageiro_obs');


    $cpf.on('blur', function () {
        if ($cpf.val()) {
            if ($cpf.val() != $cpf.data('oldval')) {
                $cpf.data('oldval', $cpf.val());
                $.getJSON(Routing.generate('passageiro_findPassageiroBy') + '?tipoDoc=cpf&v=' + encodeURIComponent($cpf.val()))
                    .done(
                        function (data) {
                            if (data.result) {
                                $rg.val(data.result.rg);
                                $nome.val(data.result.nome);
                                $foneFixo.val(data.result.foneFixo);
                                $foneCelular.val(data.result.foneCelular);
                                $foneWhatsapp.val(data.result.foneWhatsapp);
                                $foneRecados.val(data.result.foneRecados);
                                $email.val(data.result.email);
                                $dtNascimento.val(Moment(data.result.dtNascimento, Moment.ISO_8601, true).format('DD/MM/YYYY'));
                                $obs.val(data.result.obs);
                            }

                        });
            }

        }

    });


})
;
