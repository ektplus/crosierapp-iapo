<?php

namespace App\Controller\Turismo\App;

use App\Entity\Turismo\Viagem;
use App\Repository\Turismo\ViagemRepository;
use CrosierSource\CrosierLibBaseBundle\Controller\FormListController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class CompraController extends FormListController
{


    /**
     *
     * @Route("/app/tur/compra/ini", name="tur_app_compra_ini")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     *
     */
    public function ini(Request $request)
    {
        $params = [];

        /** @var ViagemRepository $repoViagens */
        $repoViagens = $this->getDoctrine()->getRepository(Viagem::class);
        $cidadesOrigens = $repoViagens->buildSelect2CidadesOrigensViagensProgramadas();

        $params['cidadesOrigens'] = json_encode($cidadesOrigens);
        return $this->render('Turismo/App/form_passagem_pesquisarViagens.html.twig', $params);
    }


    /**
     *
     * @Route("/app/tur/compra/listarViagens", name="tur_app_compra_listarViagens")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function listarViagens(Request $request)
    {
        if ($request->get('filter')) {
            $filter = $request->get('filter');
            /** @var ViagemRepository $repoViagens */
            $repoViagens = $this->getDoctrine()->getRepository(Viagem::class);
            $params['viagens'] = $repoViagens->findViagensBy($filter['dts'], $filter['cidadeOrigem'], $filter['cidadeDestino']);
            if (count($params['viagens']) < 1) {
                $this->addFlash('warn', 'Nenhuma viagem encontrada na pesquisa.');
                return $this->redirectToRoute('tur_app_compra_ini', $filter);
            }
            return $this->render('Turismo/App/form_passagem_listarViagens.html.twig', $params);
        } else {
            throw new \RuntimeException('filter n/d');
        }
    }


    /**
     *
     * @Route("/app/tur/compra/selecionarPoltronas/{viagem}", name="tur_app_compra_selecionarPoltronas", requirements={"viagem"="\d+"})
     * @param Request $request
     * @param SessionInterface $session
     * @param Viagem $viagem
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function selecionarPoltronas(Request $request, SessionInterface $session, Viagem $viagem)
    {
        $session->set('viagem', $viagem->getId());
        $params = [];
        return $this->render('Turismo/App/form_passagem_selecionarPoltronas.html.twig', $params);
    }

    /**
     *
     * @Route("/app/tur/compra/informarDadosPassageiros", name="tur_app_compra_informarDadosPassageiros")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function informarDadosPassageiros(Request $request, SessionInterface $session)
    {
        $params = [];
        $rPoltronas = $request->get('poltronas');
        if (!$rPoltronas) {
            $this->addFlash('warn', 'É necessário selecionar ao menos 1 poltrona');
            return $this->redirectToRoute('tur_app_compra_selecionarPoltronas', ['viagem' => $session->get('viagem')]);
        }
        $params['poltronas'] = [];
        foreach ($rPoltronas as $k => $v) {
            $params['poltronas'][] = $k;
        }

        return $this->render('Turismo/App/form_passagem_informarDadosPassageiros.html.twig', $params);
    }

    /**
     *
     * @Route("/app/tur/compra/resumo", name="tur_app_compra_resumo")
     * @param Request $request
     * @param Viagem $viagem
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resumo(Request $request, SessionInterface $session)
    {
        $params = [];
        $viagem = $this->getDoctrine()->getRepository(Viagem::class)->find($session->get('viagem'));

        $rDadosPassageiros = $request->get('dadosPassageiro');

        $totais = [
            'passagens' => 0,
            'taxas' => 0,
            'bagagens' => 0,
            'geral' => 0,
        ];

        $dadosPassageiros = [];
        foreach ($rDadosPassageiros as $rDadoPassageiro) {

            if ($rDadoPassageiro['qtdeBagagens'] > 1) {
                $rDadoPassageiro['total_bagagens'] = bcmul($rDadoPassageiro['qtdeBagagens'] - 1, $viagem->valorBagagem, 2);
                $totais['bagagens'] = bcadd($totais['bagagens'], $rDadoPassageiro['total_bagagens'], 2);
            }

            $totais['passagens'] = bcadd($totais['passagens'], $viagem->valorPoltrona);
            $totais['taxas'] = bcadd($totais['taxas'], $viagem->valorTaxas);
            $totais['geral'] = $totais['passagens'] + $totais['taxas'] + $totais['bagagens'];

            $dadosPassageiros[] = $rDadoPassageiro;
        }

        $params['viagem'] = $viagem;
        $params['dadosPassageiros'] = $dadosPassageiros;
        $params['totais'] = $totais;

        $session->set('dadosPassageiros', $dadosPassageiros);
        $session->set('totais', $totais);

        return $this->render('Turismo/App/form_passagem_resumo.html.twig', $params);
    }


    /**
     *
     * @Route("/app/tur/compra/informarDadosCliente", name="tur_app_compra_informarDadosCliente")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     *
     */
    public function informarDadosCliente(Request $request)
    {
        $params = [];
        return $this->render('Turismo/App/form_passagem_dadosCliente.html.twig', $params);
    }

    /**
     *
     * @Route("/app/tur/compra/pagtoIni", name="tur_app_compra_pagtoIni")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function pagtoIni(Request $request, SessionInterface $session)
    {
        $session->set('dadosCliente', $request->get('dadosCliente'));
        $params = [];
        $params['dadosCliente'] = $session->get('dadosCliente');
        $params['totais'] = $session->get('totais');
        return $this->render('Turismo/App/form_passagem_pagtoIni.html.twig', $params);
    }


}