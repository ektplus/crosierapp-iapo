<?php

namespace App\Controller\Turismo\App;

use App\Entity\Turismo\Cliente;
use App\Entity\Turismo\Compra;
use App\Entity\Turismo\Viagem;
use App\EntityHandler\Turismo\ClienteEntityHandler;
use App\EntityHandler\Turismo\CompraEntityHandler;
use App\Repository\Turismo\ViagemRepository;
use CrosierSource\CrosierLibBaseBundle\Controller\FormListController;
use CrosierSource\CrosierLibBaseBundle\Exception\ViewException;
use CrosierSource\CrosierLibBaseBundle\Utils\ArrayUtils\ArrayUtils;
use CrosierSource\CrosierLibBaseBundle\Utils\DateTimeUtils\DateTimeUtils;
use Doctrine\DBAL\Connection;
use PagarMe\Client;
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

    private ClienteEntityHandler $clienteEntityHandler;

    private CompraEntityHandler $compraEntityHandler;

    /**
     * @return ClienteEntityHandler
     */
    public function getClienteEntityHandler(): ClienteEntityHandler
    {
        return $this->clienteEntityHandler;
    }

    /**
     * @required
     * @param ClienteEntityHandler $clienteEntityHandler
     */
    public function setClienteEntityHandler(ClienteEntityHandler $clienteEntityHandler): void
    {
        $this->clienteEntityHandler = $clienteEntityHandler;
    }

    /**
     * @return CompraEntityHandler
     */
    public function getCompraEntityHandler(): CompraEntityHandler
    {
        return $this->compraEntityHandler;
    }

    /**
     * @required
     * @param CompraEntityHandler $compraEntityHandler
     */
    public function setCompraEntityHandler(CompraEntityHandler $compraEntityHandler): void
    {
        $this->compraEntityHandler = $compraEntityHandler;
    }


    /**
     *
     * @Route("/app/tur/compra/ini", name="tur_app_compra_ini")
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function ini(SessionInterface $session)
    {
        $params = [];

        $session->clear();

        /** @var ViagemRepository $repoViagens */
        $repoViagens = $this->getDoctrine()->getRepository(Viagem::class);
        $cidadesOrigens = $repoViagens->buildSelect2CidadesOrigensViagensProgramadas();

        $params['cidadesOrigens'] = json_encode($cidadesOrigens);

        $proxMes = DateTimeUtils::incMes(new \DateTime());
        $params['filter']['dts'] = (new \DateTime())->format('d/m/Y') . ' - ' . $proxMes->format('d/m/Y');

        return $this->render('Turismo/App/form_passagem_ini.html.twig', $params);
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
        if (!$this->isCsrfTokenValid('csrf_token', $request->get('csrf_token'))) {
            $this->addFlash('error', 'Token inválido');
            return $this->redirectToRoute('tur_app_compra_ini');
        }

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
     * @Route("/app/tur/compra/opcaoCompra/{viagem}", name="tur_app_compra_opcaoCompra", requirements={"viagem"="\d+"})
     * @param SessionInterface $session
     * @param Viagem $viagem
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function opcaoCompra(SessionInterface $session, Viagem $viagem)
    {
        $session->set('viagemId', $viagem->getId());
        $params['viagem'] = $viagem;
        return $this->render('Turismo/App/form_passagem_opcaoCompra.html.twig', $params);
    }

    /**
     *
     * @Route("/app/tur/compra/selecionarQtde", name="tur_app_compra_selecionarQtde")
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function selecionarQtde(Request $request, SessionInterface $session)
    {
        if ($request->get('opcaoCompra')) {
            $session->set('opcaoCompra', $request->get('opcaoCompra'));
            return $this->render('Turismo/App/form_passagem_selecionarQtde.html.twig');
        } else if ($request->get('qtde')) {
            $qtde = (int)$request->get('qtde');
            $session->set('qtde', $qtde);

            if ($session->get('opcaoCompra') === 'passagens') {
                $rPoltronas = [];
                for ($i = 1; $i <= $qtde; $i++) {
                    $rPoltronas[$i] = 'on'; // simulando a seleção
                }
                $params = [
                    'poltronas' => $rPoltronas
                ];
                return $this->redirectToRoute('tur_app_compra_informarDadosPassageiros', $params);
            } else {
                $session->set('dadosPassageiros', null);
                return $this->redirectToRoute('tur_app_compra_resumo');
            }
        } else {
            $this->addFlash('error', 'Ocorreu um erro ao selecionar a quantidade');
            return $this->render('Turismo/App/form_passagem_selecionarQtde.html.twig');
        }
    }


    /**
     *
     * @Route("/app/tur/compra/selecionarPoltronas", name="tur_app_compra_selecionarPoltronas")
     * @param SessionInterface $session
     * @return Response
     */
    public function selecionarPoltronas(SessionInterface $session)
    {
        $params = [];
        $session->set('opcaoCompra', 'selecionarPoltronas');
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

        if ($request->get('dadosPassageiros')) {
            $dadosPassageiros = $request->get('dadosPassageiros');
            $session->set('dadosPassageiros', $dadosPassageiros);
            $session->set('qtde', count($dadosPassageiros));
            return $this->redirectToRoute('tur_app_compra_resumo');
        }

        $rPoltronas = $request->get('poltronas');
        if (!$rPoltronas) {
            $this->addFlash('warn', 'É necessário selecionar ao menos 1 poltrona');
            return $this->redirectToRoute('tur_app_compra_selecionarPoltronas', ['viagem' => $session->get('viagemId')]);
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
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resumo(SessionInterface $session)
    {
        $viagem = $this->getDoctrine()->getRepository(Viagem::class)->find($session->get('viagemId'));

        $opcaoCompra = $session->get('opcaoCompra');

        $totais = [
            'passagens' => 0,
            'taxas' => 0,
            'bagagens' => 0,
            'geral' => 0,
        ];

        if ($opcaoCompra === 'selecionarPoltronas' or $opcaoCompra === 'passagens') {
            $rDadosPassageiros = $session->get('dadosPassageiros');
            $dadosPassageiros = [];
            foreach ($rDadosPassageiros as $rDadoPassageiro) {

                $rDadoPassageiro['total_bagagens'] = 0;
                if ($rDadoPassageiro['qtdeBagagens'] > 1) {
                    $rDadoPassageiro['total_bagagens'] = bcmul($rDadoPassageiro['qtdeBagagens'] - 1, $viagem->valorBagagem, 2);
                    $totais['bagagens'] = bcadd($totais['bagagens'], $rDadoPassageiro['total_bagagens'], 2);
                }

                if ($opcaoCompra === 'selecionarPoltronas') {
                    $rDadoPassageiro['valorPassagem'] = $viagem->getValorPassagemComEscolhaPoltrona();
                } else {
                    $rDadoPassageiro['valorPassagem'] = $viagem->valorPoltrona;
                }

                $totais['passagens'] = bcadd($totais['passagens'], $rDadoPassageiro['valorPassagem'], 2);
                $totais['taxas'] = bcadd($totais['taxas'], $viagem->valorTaxas, 2);
                $totais['geral'] = $totais['passagens'] + $totais['taxas'] + $totais['bagagens'];

                $rDadoPassageiro['total'] = $rDadoPassageiro['total_bagagens'] + $viagem->valorTaxas + $rDadoPassageiro['valorPassagem'];
                $rDadoPassageiro['nome'] = mb_strtoupper($rDadoPassageiro['nome']);
                $dadosPassageiros[] = $rDadoPassageiro;
            }
            $session->set('dadosPassageiros', $dadosPassageiros);

        } else if ($opcaoCompra === 'bagagens') {
            $totais['bagagens'] = bcmul($viagem->valorBagagem, $session->get('qtde'), 2);
            $totais['geral'] = bcmul($viagem->valorBagagem, $session->get('qtde'), 2);
        } else {
            $this->addFlash('error', 'Opção de compra inválida');
            return $this->redirectToRoute('tur_app_compra_opcaoCompra', ['viagem' => $viagem->getId()]);
        }

        $session->set('totais', $totais);

        $params['viagem'] = $viagem;
        return $this->render('Turismo/App/form_passagem_resumo.html.twig', $params);
    }


    /**
     *
     * @Route("/app/tur/compra/checkCPF", name="tur_app_compra_checkCPF")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     *
     */
    public function checkCPF(Request $request)
    {
        $conn = $this->getDoctrine()->getConnection();
        $cpf = $request->get('cpf');
        $cpf = preg_replace('/[^\d]/', '', $cpf);
        if ($cpf) {
            $rsCpf = $conn->fetchAll('SELECT count(*) as qt FROM iapo_tur_cliente WHERE cpf LIKE :cpf', ['cpf' => $cpf]);
            if ($rsCpf[0]['qt'] > 0) {
                return $this->render('Turismo/App/form_passagem_login.html.twig', ['cpf' => $cpf]);
            } else {
                return $this->render('Turismo/App/form_passagem_cadastroCliente.html.twig', ['cpf' => $cpf]);
            }
        } else {
            throw new \RuntimeException('cpf n/d');
        }
    }


    /**
     * @Route("/app/tur/compra/login", name="tur_app_compra_login")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function login(Request $request, SessionInterface $session)
    {
        try {
            $conn = $this->getDoctrine()->getConnection();
            $cpf = preg_replace('/[^\d]/', '', $request->get('cpf'));
            $senha = $request->get('senha');
            if ($cpf && $senha) {
                $rsCliente = $conn->fetchAll('SELECT * FROM iapo_tur_cliente WHERE cpf LIKE :cpf',
                    ['cpf' => $cpf]);
                if ((count($rsCliente[0]) > 0) && (password_verify($senha, $rsCliente[0]['senha']))) {
                    $session->set('idClienteLogado', $rsCliente[0]['id']);
                    return $this->redirectToRoute('tur_app_compra_pagto');
                } else {
                    throw new ViewException('CPF/Senha inválidos');
                }
            } else {
                throw new ViewException('CPF/Senha inválidos');
            }
        } catch (\Exception $e) {
            $errMsg = 'Ocorreu um erro ao efetuar o login';
            if ($e instanceof ViewException) {
                $errMsg = $e->getMessage();
            }
            $session->set('idClienteLogado', false);
            $this->addFlash('error', $errMsg);
            return $this->render('Turismo/App/form_passagem_login.html.twig', ['cpf' => $cpf ?? '']);
        }
    }


    /**
     *
     * @Route("/app/tur/compra/cadastrarCliente", name="tur_app_compra_cadastrarCliente")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function cadastrarCliente(Request $request, SessionInterface $session)
    {
        try {
            $dadosCliente = $request->get('dadosCliente');
            $cliente = new Cliente();
            $cliente->cpf = preg_replace('/[^\d]/', '', $dadosCliente['cpf']);
            $cliente->nome = $dadosCliente['nome'];
            $cliente->fone = $dadosCliente['telefone'];
            $cliente->celular = $dadosCliente['celular'];
            $cliente->email = mb_strtolower($dadosCliente['email']);
            if ($dadosCliente['senha1'] !== $dadosCliente['senha2']) {
                throw new ViewException('As senhas não são iguais');
            }
            $cliente->senha = password_hash($dadosCliente['senha1'], PASSWORD_BCRYPT);
            $this->clienteEntityHandler->save($cliente);
            $session->set('idClienteLogado', $cliente->getId());
            return $this->redirectToRoute('tur_app_compra_resumo');
        } catch (\Exception $e) {
            $session->set('idClienteLogado', false);
            $errMsg = 'Ocorreu um erro ao cadastrar os dados.';
            if ($e instanceof ViewException) {
                $errMsg = $e->getMessage();
            }
            $this->addFlash('error', $errMsg);
            $params = [];
            return $this->render('Turismo/App/form_passagem_cadastroCliente.html.twig', $params);
        }
    }


    /**
     *
     * @Route("/app/tur/compra/pagto", name="tur_app_compra_pagto")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function pagto(Request $request, SessionInterface $session)
    {
        if ($request->get('result')) {
            try {
                $result = $request->get('result');
                if ($result === 'OK') {
                    $token = $request->get('token');
                    if (!$token) {
                        throw new ViewException('token n/d');
                    }
                    $payment_method = $request->get('payment_method');
                    if (!$payment_method) {
                        throw new ViewException('payment_method n/d');
                    }

                    $compra = new Compra();
                    $compra_jsonData = [
                        'token' => $token,
                        'payment_method' => $payment_method
                    ];

                    $compra_jsonData['dadosPassageiros'] = $session->get('dadosPassageiros');

                    $idClienteLogado = $session->get('idClienteLogado');
                    if (!$idClienteLogado) {
                        throw new ViewException('idClienteLogado n/d');
                    }

                    $repoCliente = $this->getDoctrine()->getRepository(Cliente::class);
                    /** @var Cliente $cliente */
                    $cliente = $repoCliente->find($idClienteLogado);
                    $compra->cliente = $cliente;

                    $viagem = $this->getDoctrine()->getRepository(Viagem::class)->find($session->get('viagemId'));
                    $compra->viagem = $viagem;

                    $compra->dtCompra = new \DateTime();
                    $compra->status = 'AGUARDANDO PAGAMENTO';

                    $totalGeral = $session->get('totais')['geral'];
                    if (!$totalGeral) {
                        throw new ViewException('totalGeral n/d');
                    }
                    $compra->valorTotal = $totalGeral;

                    $pagarme = new Client('ak_test_kvwHf3f5dWpGSI2zH18gJrDhMM3AXl');

                    $transactions = $pagarme->transactions()->get(['id' => $token]);

                    $compra_jsonData['pagarme_transaction'] = (array)$transactions;

                    $compra->jsonData = $compra_jsonData;
                    $this->compraEntityHandler->save($compra);

                    $session->set('ultimaCompra', $compra->getId());

                    $this->addFlash('success', 'Em breve você receberá um e-mail com os dados de sua compra.');

                } else {
                    throw new ViewException('Ocorreu um erro ao registrar sua compra.');
                }
            } catch (\Exception $e) {
                $errMsg = 'Ocorreu um erro';
                if ($e instanceof ViewException) {
                    $errMsg = $e->getMessage();
                }
                $this->addFlash('error', $errMsg);
            }
            return $this->redirectToRoute('tur_app_compra_resumo');
        } else {
            if ($request->get('dadosCliente')) {
                $session->set('dadosCliente', $request->get('dadosCliente'));
            }
            $params = [];
            $params['dadosCliente'] = $session->get('dadosCliente');
            $params['totais'] = $session->get('totais');

            $params['postbackUrl'] = 'https://iapo.crosier.iapo.com.br/app/tur/compra/pagarmeCallback';
            // $params['postbackUrl'] = 'http://ff61070e2f7c.ngrok.io/app/tur/compra/pagarmeCallback';

            return $this->render('Turismo/App/form_passagem_pagto.html.twig', $params);
        }
    }


    /**
     *
     * @Route("/app/tur/compra/pagarmeCallback", name="tur_app_compra_pagarmeCallback")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function pagarmeCallback(Request $request)
    {
        try {
            $pagarme = new Client('ak_test_kvwHf3f5dWpGSI2zH18gJrDhMM3AXl');
            $signature = $request->server->get('HTTP_X_HUB_SIGNATURE');
            $isValidPostback = $pagarme->postbacks()->validate($request->getContent(), $signature);
            if ($isValidPostback) {
                $pagarme_transaction_id = $request->get('id');
                /** @var Connection $conn */
                $conn = $this->getDoctrine()->getConnection();
                $rsCompraId = $conn->fetchAll('SELECT id FROM iapo_tur_compra WHERE json_data->>"$.pagarme_transaction.id" = :pagarme_transaction_id', ['pagarme_transaction_id' => $pagarme_transaction_id]);
                if ($rsCompraId[0]['id'] ?? false) {
                    $repoCompra = $this->getDoctrine()->getRepository(Compra::class);
                    /** @var Compra $compra */
                    $compra = $repoCompra->find($rsCompraId[0]['id']);
                    $postback = $request->request->all();
                    if ($compra->jsonData['postbacks'] ?? false) {
                        $ultimoPostback = $compra->jsonData['postbacks'][count($compra->jsonData['postbacks']) - 1];
                        ArrayUtils::rksort($ultimoPostback);
                        ArrayUtils::rksort($postback);
                        if (json_encode($ultimoPostback) !== json_encode($postback)) {
                            $compra->jsonData['postbacks'][] = $postback;
                        }
                        // else ... mesmo postback já salvo
                    } else {
                        $compra->jsonData['postbacks'][] = $postback;
                    }
                    $this->compraEntityHandler->save($compra);
                } else {
                    throw new ViewException('Compra não encontrada para pagarme_transaction_id = "' . $pagarme_transaction_id . '"');
                }
            } else {
                throw new ViewException('postback inválido');
            }
            return new Response('OK');
        } catch (\Exception $e) {
            $errMsg = 'Erro';
            if ($e instanceof ViewException) {
                $errMsg = $e->getMessage();
            }
            return new Response($errMsg, 401);
        }
    }


    /**
     *
     * @Route("/app/tur/compra/ver", name="tur_app_compra_ver")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function ver()
    {

//        $pagarme = new Client('ak_test_kvwHf3f5dWpGSI2zH18gJrDhMM3AXl');
//
//        $model_id = 'test_transaction_Td5J0x4WHyP1VrxQxahcoM58rMHe26';
//
//        $transactions = $pagarme->transactions()->get(['id' => $model_id]);

        return new Response('<h1>ok');
    }


}