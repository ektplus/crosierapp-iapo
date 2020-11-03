<?php

namespace App\Controller\Turismo\App;

use App\Entity\Turismo\Cliente;
use App\Entity\Turismo\Compra;
use App\Entity\Turismo\Passageiro;
use App\Entity\Turismo\Viagem;
use App\EntityHandler\Turismo\ClienteEntityHandler;
use App\EntityHandler\Turismo\CompraEntityHandler;
use App\EntityHandler\Turismo\PassageiroEntityHandler;
use App\Repository\Turismo\ViagemRepository;
use CrosierSource\CrosierLibBaseBundle\Controller\FormListController;
use CrosierSource\CrosierLibBaseBundle\Entity\Config\AppConfig;
use CrosierSource\CrosierLibBaseBundle\Exception\ViewException;
use CrosierSource\CrosierLibBaseBundle\Repository\Config\AppConfigRepository;
use CrosierSource\CrosierLibBaseBundle\Utils\ArrayUtils\ArrayUtils;
use CrosierSource\CrosierLibBaseBundle\Utils\DateTimeUtils\DateTimeUtils;
use Doctrine\DBAL\Connection;
use PagarMe\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class CompraController extends FormListController
{

    private ClienteEntityHandler $clienteEntityHandler;

    private CompraEntityHandler $compraEntityHandler;

    private PassageiroEntityHandler $passageiroEntityHandler;

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
     * @required
     * @param PassageiroEntityHandler $passageiroEntityHandler
     */
    public function setPassageiroEntityHandler(PassageiroEntityHandler $passageiroEntityHandler): void
    {
        $this->passageiroEntityHandler = $passageiroEntityHandler;
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

        $session->remove('compra');
        $session->remove('compraId');
        $session->remove('viagemId');
        $session->remove('opcaoCompra');
        $session->remove('dadosPassageiros');
        $session->remove('qtde');

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
        try {
            if ($request->get('opcaoCompra')) {
                $session->set('opcaoCompra', $request->get('opcaoCompra'));
                return $this->render('Turismo/App/form_passagem_selecionarQtde.html.twig');
            } else if ($request->get('qtde')) {
                $qtde = (int)$request->get('qtde');
                $session->set('qtde', $qtde);

                if ($session->get('opcaoCompra') === 'passagens') {
                    /** @var ViagemRepository $repoViagem */
                    $repoViagem = $this->getDoctrine()->getRepository(Viagem::class);
                    /** @var Viagem $viagem */
                    $viagem = $repoViagem->find($session->get('viagemId'));
                    /** @var ViagemRepository $repoViagem */
                    $repoViagem = $this->getDoctrine()->getRepository(Viagem::class);
                    $poltronas = $repoViagem->handlePoltronas($viagem);

                    $rPoltronas = [];
                    $totalSelecionado = 0;

                    foreach ($poltronas as $numPoltrona => $poltrona) {
                        if ($poltrona === 'desocupada') {
                            $rPoltronas[(int)$numPoltrona] = 'on'; // simulando a seleção
                            $totalSelecionado++;
                        }
                        if ($totalSelecionado >= $qtde) {
                            break;
                        }
                    }

                    if ($totalSelecionado < $qtde) {
                        $this->addFlash('warn', 'Qtde de poltronas não disponível');
                        return $this->render('Turismo/App/form_passagem_selecionarQtde.html.twig');
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
        } catch (ViewException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('tur_app_compra_ini');
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
        try {
            $params = [];
            $session->set('opcaoCompra', 'selecionarPoltronas');
            /** @var ViagemRepository $repoViagem */
            $repoViagem = $this->getDoctrine()->getRepository(Viagem::class);
            /** @var Viagem $viagem */
            $viagem = $repoViagem->find($session->get('viagemId'));
            $params['poltronas'] = $repoViagem->handlePoltronas($viagem);
            $croqui = $viagem->veiculo->croqui;
            return $this->render('Turismo/App/form_passagem_selecionarPoltronas_' . $croqui . '.html.twig', $params);
        } catch (ViewException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('tur_app_compra_ini');
        }
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


        $compraId = $session->get('compraId');
        if ($compraId) {
            $repoCompra = $this->getDoctrine()->getRepository(Compra::class);
            /** @var Compra $compra */
            $compra = $repoCompra->find($compraId);
        } elseif ($session->get('compra')) {
            $compra = $session->get('compra');
        } else {
            $compra = new Compra();
        }

        $compra->viagem = $viagem;

        $compra->jsonData['dadosPassageiros'] = $session->get('dadosPassageiros');

        $opcaoCompra = $session->get('opcaoCompra');

        $compra->jsonData['opcaoCompra'] = $opcaoCompra;


        if (!in_array($opcaoCompra, ['selecionarPoltronas', 'passagens', 'bagagens'])) {
            $this->addFlash('error', 'Opção de compra inválida');
            return $this->redirectToRoute('tur_app_compra_opcaoCompra', ['viagem' => $viagem->getId()]);
        }

        $session->set('compra', $compra);

        return $this->render('Turismo/App/form_passagem_resumo.html.twig', []);
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
     * @Route("/app/tur/compra/doLogin", name="tur_app_compra_doLogin")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function doLogin(Request $request, SessionInterface $session)
    {
        try {
            $cpf = $request->get('cpf');
            if ($cpf) {
                $conn = $this->getDoctrine()->getConnection();
                $cpf = preg_replace('/[^\d]/', '', $cpf);
                $senha = $request->get('senha');
                if ($cpf && $senha) {
                    $rsCliente = $conn->fetchAll('SELECT * FROM iapo_tur_cliente WHERE cpf LIKE :cpf',
                        ['cpf' => $cpf]);
                    if ((count($rsCliente[0]) > 0) && (password_verify($senha, $rsCliente[0]['senha']))) {
                        $session->set('idClienteLogado', $rsCliente[0]['id']);
                        $redirectTo = $request->get('redirectTo') ?? 'tur_app_compra_pagto';
                        return $this->redirectToRoute($redirectTo);
                    } else {
                        throw new ViewException('CPF/Senha inválidos');
                    }
                } else {
                    throw new ViewException('CPF/Senha inválidos');
                }
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response|null
     */
    public function pagto(Request $request, SessionInterface $session)
    {
        if ($request->get('result')) {
            try {
                $result = $request->get('result');

                $compraId = $session->get('compraId');
                if ($compraId) {
                    $repoCompra = $this->getDoctrine()->getRepository(Compra::class);
                    /** @var Compra $compra */
                    $compra = $repoCompra->find($compraId);
                } else {
                    $compra = $session->get('compra');
                }

                $compra_jsonData = $compra->jsonData;
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


                $totalGeral = $compra->getTotais()['geral'];
                if (!$totalGeral) {
                    throw new ViewException('totalGeral n/d');
                }
                $compra->valorTotal = $totalGeral;

                if ($result === 'OK') {
                    $token = $request->get('token');
                    if (!$token) {
                        throw new ViewException('token n/d');
                    }
                    $payment_method = $request->get('payment_method');
                    if (!$payment_method) {
                        throw new ViewException('payment_method n/d');
                    }

                    $compra_jsonData['token'] = $token;
                    $compra_jsonData['payment_method'] = $payment_method;

                    /** @var AppConfigRepository $repoAppConfig */
                    $repoAppConfig = $this->getDoctrine()->getRepository(AppConfig::class);
                    $appConfig_pagarmekey = $repoAppConfig->findAppConfigByChave('pagarme.key');

                    $pagarme = new Client($appConfig_pagarmekey->getValor());
                    $transactions = $pagarme->transactions()->get(['id' => $token]);
                    $compra_jsonData['pagarme_transaction'] = (array)$transactions;

                    $compra->status = 'PAGAMENTO RECEBIDO';

                    $this->addFlash('success', 'Em breve você receberá um e-mail com os dados de sua compra.');
                } else {
                    $compra->status = 'ERRO';
                    $compra_jsonData['pagamento_result'] = [
                        'msg' => $request->get('msg'),
                        'type' => $request->get('type')
                    ];
                    $this->addFlash('error', 'Ocorreu um erro ao registrar sua compra.');
                }

                $compra->jsonData = $compra_jsonData;

                $this->compraEntityHandler->save($compra);

                if ($compra->status !== 'ERRO') {
                    foreach ($compra_jsonData['dadosPassageiros'] as $dadosPassageiro) {
                        $passageiro = new Passageiro();
                        $passageiro->viagem = $viagem;
                        $passageiro->nome = $dadosPassageiro['nome'];
                        $passageiro->rg = $dadosPassageiro['rg'];
                        $passageiro->foneCelular = $dadosPassageiro['fone'] ?? '';
                        $passageiro->poltrona = $dadosPassageiro['poltrona'];
                        $passageiro->jsonData['compra_id'] = $compra->getId();
                        $this->passageiroEntityHandler->save($passageiro);
                    }
                }

                // remove o atributo 'objeto' agora em favor do id da entidade
                $session->remove('compra');
                $session->set('compraId', $compra->getId());

            } catch (\Exception $e) {
                $errMsg = 'Ocorreu um erro';
                if ($e instanceof ViewException) {
                    $errMsg = $e->getMessage();
                }
                $this->addFlash('error', $errMsg);
            }
            if (!isset($compra) || $compra->status !== 'ERRO') {
                return $this->redirectToRoute('tur_app_compra_resumo');
            }
        } else {
            if ($request->get('dadosCliente')) {
                $session->set('dadosCliente', $request->get('dadosCliente'));
            }
            $params = [];
            $params['dadosCliente'] = $session->get('dadosCliente');
            $params['totais'] = $session->get('totais');

            $params['postbackUrl'] = 'https://iapo.crosier.iapo.com.br/app/tur/compra/pagarmeCallback';

            return $this->render('Turismo/App/form_passagem_pagto.html.twig', $params);
        }
        return null;
    }


    /**
     *
     * @Route("/app/tur/compra/pagarmeCallback", name="tur_app_compra_pagarmeCallback")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function pagarmeCallback(Request $request, MailerInterface $mailer)
    {
        try {
            $pagarme = new Client($_SERVER['pagarme.key']);
            $signature = $request->server->get('HTTP_X_HUB_SIGNATURE');
            $isValidPostback = $pagarme->postbacks()->validate($request->getContent(), $signature);
            if ($isValidPostback) {
                $pagarme_transaction_id = $request->get('id');
                /** @var Connection $conn */
                $conn = $this->getDoctrine()->getConnection();
                $rsCompraId = $conn->fetchAllAssociative('SELECT id FROM iapo_tur_compra WHERE json_data->>"$.pagarme_transaction.id" = :pagarme_transaction_id', ['pagarme_transaction_id' => $pagarme_transaction_id]);
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
                    $this->emailCompraEfetuada($mailer, $compra);
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
     * @param MailerInterface $mailer
     * @param Compra $compra
     * @return null
     * @throws ViewException
     */
    public function emailCompraEfetuada(MailerInterface $mailer, Compra $compra)
    {
        try {
            $params['compra'] = $compra;
            $body = $this->renderView('Turismo/App/emails/compra_efetuada.html.twig', $params);
            $email = (new Email())
                ->from('app@iapo.com.br')
                ->to($compra->cliente->email)
                ->subject('Compra efetuada com sucesso!')
                ->html($body);
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new ViewException('Erro ao enviar e-mail');
        }
    }


    /**
     *
     * @Route("/app/tur/compra/ver/{compra}", name="tur_app_compra_ver")
     */
    public function ver(Compra $compra)
    {
        $params['compra'] = $compra;
        return $this->render('Turismo/App/emails/compra_efetuada.html.twig', $params);
    }


    /**
     *
     * @Route("/app/tur/menuCliente", name="tur_app_menuCliente")
     */
    public function menuCliente()
    {
        $params = [];
        return $this->render('Turismo/App/menu_cliente.html.twig', $params);
    }

    /**
     *
     * @Route("/app/tur/login", name="tur_app_login")
     */
    public function login()
    {
        return $this->render('Turismo/App/form_passagem_login.html.twig');
    }


}

