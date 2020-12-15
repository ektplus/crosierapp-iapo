<?php

namespace App\Controller\Turismo\App;

use App\Entity\Turismo\Cliente;
use App\Entity\Turismo\Compra;
use App\Entity\Turismo\Passageiro;
use App\Entity\Turismo\Viagem;
use App\EntityHandler\Turismo\ClienteEntityHandler;
use App\EntityHandler\Turismo\CompraEntityHandler;
use App\EntityHandler\Turismo\PassageiroEntityHandler;
use App\Repository\Turismo\CompraRepository;
use App\Repository\Turismo\ViagemRepository;
use CrosierSource\CrosierLibBaseBundle\Business\Config\SyslogBusiness;
use CrosierSource\CrosierLibBaseBundle\Controller\FormListController;
use CrosierSource\CrosierLibBaseBundle\Entity\Config\AppConfig;
use CrosierSource\CrosierLibBaseBundle\Exception\ViewException;
use CrosierSource\CrosierLibBaseBundle\Repository\Config\AppConfigRepository;
use CrosierSource\CrosierLibBaseBundle\Utils\ArrayUtils\ArrayUtils;
use CrosierSource\CrosierLibBaseBundle\Utils\DateTimeUtils\DateTimeUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class CompraController extends FormListController
{

    protected SyslogBusiness $syslog;

    private ClienteEntityHandler $clienteEntityHandler;

    private CompraEntityHandler $compraEntityHandler;

    private PassageiroEntityHandler $passageiroEntityHandler;

    /**
     * @required
     * @param SyslogBusiness $syslog
     */
    public function setSyslog(SyslogBusiness $syslog): void
    {
        $this->syslog->setApp('crosierapp-iapo')->setComponent(self::class);
        $this->syslog = $syslog;
    }


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
     * @return RedirectResponse|Response
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
     * @return RedirectResponse|Response
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
            $viagens = $repoViagens->findViagensBy($filter['dts'], $filter['cidadeOrigem'], $filter['cidadeDestino']);
            if (count($viagens) < 1) {
                $this->addFlash('warn', 'Nenhuma viagem encontrada na pesquisa.');
                return $this->redirectToRoute('tur_app_compra_ini', $filter);
            }

            /** @var AppConfigRepository $repoAppConfig */
            $repoAppConfig = $this->getDoctrine()->getRepository(AppConfig::class);
            $minutos = $repoAppConfig->findAppConfigByChave('minutos_bloqueio_compras_antes_saida_viagem')->getValor();
            /** @var Viagem $viagem */
            foreach ($viagens as $viagem) {
                $viagem->bloqueadaTempoExpirado = false;
                if (DateTimeUtils::diffInMinutes($viagem->dtHrSaida, new \DateTime()) < $minutos) {
                    $viagem->bloqueadaTempoExpirado = true; // dinâmico
                }
            }

            $params['viagens'] = $viagens;
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
     * @return RedirectResponse|Response
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
    public function selecionarQtde(Request $request, SessionInterface $session): Response
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
                        if ($poltrona['status'] === 'desocupada') {
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
    public function selecionarPoltronas(SessionInterface $session): Response
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
     * @return RedirectResponse|Response
     */
    public function informarDadosPassageiros(Request $request, SessionInterface $session)
    {
        $params = [];

        if ($request->get('dadosPassageiros')) {
            $dadosPassageiros = $request->get('dadosPassageiros');
            $session->set('dadosPassageiros', $dadosPassageiros);

            /** @var ViagemRepository $repoViagens */
            $repoViagens = $this->getDoctrine()->getRepository(Viagem::class);
            $viagem = $this->getDoctrine()->getRepository(Viagem::class)->find($session->get('viagemId'));

            try {
                $repoViagens->checkRGs($viagem, $dadosPassageiros);
                $session->set('qtde', count($dadosPassageiros));
                return $this->redirectToRoute('tur_app_compra_resumo');
            } catch (ViewException $e) {
                $this->addFlash('error', $e->getMessage());
            }
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
     * @return RedirectResponse|Response
     * @throws ViewException
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
            $compra->status = 'NOVA';
        }

        $compra->viagem = $viagem;

        $compra->jsonData['dadosPassageiros'] = $session->get('dadosPassageiros');

        $opcaoCompra = $session->get('opcaoCompra');

        $compra->jsonData['opcaoCompra'] = $opcaoCompra;

        if (!in_array($opcaoCompra, ['selecionarPoltronas', 'passagens', 'bagagens'])) {
            $this->addFlash('error', 'Opção de compra inválida');
            return $this->redirectToRoute('tur_app_compra_opcaoCompra', ['viagem' => $viagem->getId()]);
        }

        $this->compraEntityHandler->save($compra);

        $session->set('compra', $compra);

        return $this->render('Turismo/App/form_passagem_resumo.html.twig', []);
    }


    /**
     *
     * @Route("/app/tur/compra/checkCPF", name="tur_app_compra_checkCPF")
     * @param Request $request
     * @return RedirectResponse|Response
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
     * @return RedirectResponse|Response
     */
    public function doLogin(Request $request, SessionInterface $session)
    {
        try {
            $cpf = $request->get('cpf');
            $this->syslog->info('doLogin - cpf: ' . $cpf);
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
            $this->syslog->err('doLogin - erro: ' . $errMsg);
        }
        return $this->render('Turismo/App/form_passagem_login.html.twig', ['cpf' => $cpf ?? '']);
    }


    /**
     *
     * @Route("/app/tur/compra/cadastrarCliente", name="tur_app_compra_cadastrarCliente")
     * @param Request $request
     * @param SessionInterface $session
     * @return RedirectResponse|Response
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
            $this->syslog->info('cadastrarCliente - sucesso (CPF: ' . $cliente->cpf . ', NOME: ' . $cliente->nome . ')');
            return $this->redirectToRoute('tur_app_compra_resumo');
        } catch (\Exception $e) {
            $session->set('idClienteLogado', false);
            $errMsg = 'Ocorreu um erro ao cadastrar os dados.';
            if ($e instanceof ViewException) {
                $errMsg = $e->getMessage();
            }
            $this->addFlash('error', $errMsg);
            $params = [];
            $this->syslog->err($errMsg);
            return $this->render('Turismo/App/form_passagem_cadastroCliente.html.twig', $params);
        }
    }


    /**
     *
     * @Route("/app/tur/compra/pagto", name="tur_app_compra_pagto")
     * @param Request $request
     * @param SessionInterface $session
     * @return RedirectResponse|Response|null
     */
    public function pagto(Request $request, SessionInterface $session)
    {
        try {
            $compra = $this->handleCompra($session);
            $this->syslog->info('pagto - ini - compra: ' . $compra->getId());

            if ($request->get('result')) {

                $result = $request->get('result');
                $this->syslog->info('pagto - result - compra: ' . $compra->getId(), 'result: ' . $result);

                if ($result === 'OK') {
                    $token = $request->get('token');
                    if (!$token) {
                        throw new ViewException('token n/d');
                    }
                    $payment_method = $request->get('payment_method');
                    if (!$payment_method) {
                        throw new ViewException('payment_method n/d');
                    }

                    $compra->jsonData['token'] = $token;
                    $compra->jsonData['payment_method'] = $payment_method;

                    /** @var AppConfigRepository $repoAppConfig */
                    $repoAppConfig = $this->getDoctrine()->getRepository(AppConfig::class);
                    $appConfig_pagarmekey = $repoAppConfig->findAppConfigByChave('pagarme.key');

                    $pagarme = new \PagarMe\Client($appConfig_pagarmekey->getValor());
                    $transactions = $pagarme->transactions()->get(['id' => $token]);
                    $compra->jsonData['pagarme_transaction'] = (array)$transactions;

                    $compra->status = 'PAGAMENTO RECEBIDO';

                    $this->addFlash('success', 'Compra efetuada com sucesso!');
                    $this->addFlash('info', 'Em breve você receberá um e-mail com os dados de sua compra.');
                } else {
                    $compra->status = 'ERRO';
                    $compra->jsonData['pagamento_result'] = [
                        'msg' => $request->get('msg'),
                        'type' => $request->get('type')
                    ];
                    $this->addFlash('error', 'Ocorreu um erro ao registrar sua compra.');
                }

                if ($compra->status !== 'ERRO') {
                    foreach ($compra->jsonData['dadosPassageiros'] as $dadosPassageiro) {
                        $passageiro = new Passageiro();
                        $passageiro->viagem = $compra->viagem;
                        $passageiro->nome = mb_strtoupper($dadosPassageiro['nome']);
                        $passageiro->rg = $dadosPassageiro['rg'];
                        $passageiro->foneCelular = $dadosPassageiro['fone'] ?? '';
                        $passageiro->poltrona = $dadosPassageiro['poltrona'];
                        $passageiro->jsonData['compra_id'] = $compra->getId();
                        $this->passageiroEntityHandler->save($passageiro);
                    }
                }

                $this->compraEntityHandler->save($compra);

                // remove o atributo 'objeto' agora em favor do id da entidade
                $session->remove('compra');
                $session->set('compraId', $compra->getId());


                return $this->redirectToRoute('tur_app_compra_resumo');

            } else {
                if ($request->get('dadosCliente')) {
                    $session->set('dadosCliente', $request->get('dadosCliente'));
                }
                $params = [];
                $params['dadosCliente'] = $session->get('dadosCliente');
                $params['totais'] = $session->get('totais');

                $params['postbackUrl'] = 'https://iapo.crosier.iapo.com.br/app/tur/compra/pagarmeCallback/' . $compra->getId() . '/';

                /** @var AppConfigRepository $repoAppConfig */
                $repoAppConfig = $this->getDoctrine()->getRepository(AppConfig::class);
                $appConfig_pagarmekey = $repoAppConfig->findAppConfigByChave('pagarme.encryption_key');
                $params['pagarme_encryption_key'] = $appConfig_pagarmekey->getValor();

                return $this->render('Turismo/App/form_passagem_pagto.html.twig', $params);
            }
        } catch (\Exception $e) {
            $errMsg = 'Ocorreu um erro';
            if ($e instanceof ViewException) {
                $errMsg = $e->getMessage();
            }
            $this->syslog->err($errMsg, $e->getMessage());
            $this->addFlash('error', $errMsg);
        }
        return $this->redirectToRoute('tur_app_compra_resumo');
    }

    /**
     * @param SessionInterface $session
     * @return Compra
     * @throws ViewException
     */
    private function handleCompra(SessionInterface $session): Compra
    {
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

        /** @var Compra $compraR */
        $compraR = $this->compraEntityHandler->save($compra);
        return $compraR;
    }


    /**
     *
     * @Route("/app/tur/compra/pagarmeCallback/{compra}/", name="tur_app_compra_pagarmeCallback")
     * @param Request $request
     * @param MailerInterface $mailer
     * @param Compra $compra
     * @return RedirectResponse|Response
     */
    public function pagarmeCallback(Request $request, MailerInterface $mailer, Compra $compra)
    {
        try {
            $this->syslog->info('pagarmeCallback...');
            /** @var AppConfigRepository $repoAppConfig */
            $repoAppConfig = $this->getDoctrine()->getRepository(AppConfig::class);
            $appConfig_pagarmekey = $repoAppConfig->findAppConfigByChave('pagarme.key');
            $pagarme = new \PagarMe\Client($appConfig_pagarmekey->getValor());
            $signature = $request->server->get('HTTP_X_HUB_SIGNATURE');
            $this->syslog->info('signature: ' . $signature);
            $isValidPostback = $pagarme->postbacks()->validate($request->getContent(), $signature);

            if ($isValidPostback) {
                $pagarme_transaction_id = $request->get('id');
                $this->syslog->info('pagarme_transaction_id: ' . $pagarme_transaction_id);
                $this->syslog->info('compra: ' . $compra->getId());
                $postback = $request->request->all();
                $this->syslog->info('postback: ' . json_encode($postback));

                // Verifica se veio um novo postback
                if (isset($compra->jsonData['postbacks'])) {
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
                if (!isset($compra->jsonData['pagarme_transaction'])) {
                    if (isset($postback['transaction']) && is_array($postback['transaction'])) {
                        $compra->jsonData['pagarme_transaction'] = $postback['transaction'];
                    }
                }
                $postbackCurrentStatus = ($postback['current_status'] ?? '');
                if (!in_array($postbackCurrentStatus, ['authorized', 'paid'])) {
                    $compra->status = 'PAGAMENTO RECEBIDO';

                    // Realiza a captura da transação
                    $this->syslog->info('Iniciando a captura da transação: ' . $pagarme_transaction_id);
                    $pagarme = new \PagarMe\Client($appConfig_pagarmekey->getValor());
                    $totalEmCentavos = number_format($compra->valorTotal, 2, '', '');
                    $rCaptura = $pagarme->transactions()->capture([
                        'id' => $pagarme_transaction_id,
                        'amount' => $totalEmCentavos
                    ]);
                    $this->syslog->info('Retorno da captura da transação', json_encode($rCaptura));
                    $this->syslog->info('Enviando e-mail para o cliente');
                    $this->emailCompraEfetuada($mailer, $compra);
                } else {
                    $this->syslog->info('Setando status da compra para "ERRO". postback.current_status != "[authorized,paid]" (' . $postbackCurrentStatus . ')');
                    $compra->status = 'ERRO';
                }
                $this->compraEntityHandler->save($compra);
            } else {
                throw new ViewException('postback inválido');
            }
            return new Response('OK');
        } catch (\Throwable $e) {
            $errMsg = 'Erro';
            if ($e instanceof ViewException) {
                $errMsg = $e->getMessage();
            }
            $this->syslog->info('ERRO: ' . $errMsg, $request->getContent());
            return new Response($errMsg, 401);
        }
    }


    /**
     * @param MailerInterface $mailer
     * @param Compra $compra
     * @return void
     * @throws ViewException
     */
    public function emailCompraEfetuada(MailerInterface $mailer, Compra $compra): void
    {
        try {
            $this->syslog->info('emailCompraEfetuada - id:' . $compra->getId() . ' - e-mail: ' . $compra->cliente->email);
            $params['compra'] = $compra;

            $transacaoAprovada = $compra->getPostbackTransacaoAprovada();

            $params['ultimosDigitos'] = $transacaoAprovada['transaction']['card']['last_digits'] ?? '****';
            $params['bandeira'] = $transacaoAprovada['transaction']['card_brand'] ?? 'N/D';
            $params['nsu'] = $transacaoAprovada['transaction']['nsu'] ?? 'N/D';
            $body = $this->renderView('Turismo/App/emails/compra_efetuada.html.twig', $params);
            $email = (new Email())
                ->from(new Address('app@iapo.com.br', 'Iapó'))
                ->to($compra->cliente->email)
                ->subject('Compra efetuada com sucesso!')
                ->html($body);
            $mailer->send($email);
            $this->syslog->info('emailCompraEfetuada - OK');
        } catch (\Throwable $e) {
            $this->syslog->info('emailCompraEfetuada - ERRO', $e->getMessage());
            throw new ViewException('Erro ao enviar e-mail');
        }
    }

    /**
     *
     * @Route("/app/tur/compra/reenviarEmailCompraEfetuada/{compra}", name="tur_app_compra_reenviarEmailCompraEfetuada")
     * @param MailerInterface $mailer
     * @param Compra $compra
     * @return RedirectResponse|Response
     */
    public function reenviarEmailCompraEfetuada(MailerInterface $mailer, Compra $compra): Response
    {
        try {
            $this->syslog->info('reenviarEmailCompraEfetuada');
            $this->emailCompraEfetuada($mailer, $compra);
            return new Response('OK');
        } catch (ViewException $e) {
            print_r($e->getTraceAsString());
            return new Response('ERRO: ' . $e->getMessage());
        }
    }

    /**
     *
     * @Route("/app/tur/compra/ver/{compra}", name="tur_app_compra_ver")
     * @param Compra $compra
     * @return Response
     */
    public function ver(Compra $compra): Response
    {
        $params['compra'] = $compra;
        return $this->render('Turismo/App/emails/compra_efetuada.html.twig', $params);
    }


    /**
     * @Route("/app/tur/menuCliente", name="tur_app_menuCliente")
     * @param SessionInterface $session
     * @return RedirectResponse|Response
     */
    public function menuCliente(SessionInterface $session)
    {
        if (!$session->get('idClienteLogado')) {
            return $this->redirectToRoute('tur_app_login', ['rtb' => true]);
        }
        $params = [];

        try {
            $idClienteLogado = $session->get('idClienteLogado');
            if (!$idClienteLogado) {
                throw new ViewException('idClienteLogado n/d');
            }
            $repoCliente = $this->getDoctrine()->getRepository(Cliente::class);
            /** @var Cliente $cliente */
            $cliente = $repoCliente->find($idClienteLogado);
            /** @var CompraRepository $repoCompra */
            $repoCompra = $this->getDoctrine()->getRepository(Compra::class);
            $params['compras'] = $repoCompra->findByFiltersSimpl([['cliente', 'EQ', $cliente]]);

        } catch (ViewException $e) {
            $this->addFlash('error', 'Erro ao carregar as compras');
        }
        return $this->render('Turismo/App/menu_cliente.html.twig', $params);

    }

    /**
     *
     * @Route("/app/tur/login", name="tur_app_login")s
     */
    public function login(): Response
    {
        $params['redirectTo'] = 'tur_app_compra_ini';
        return $this->render('Turismo/App/form_passagem_login.html.twig', $params);
    }


}

