<?php

namespace App\Controller\Turismo;

use App\Controller\Turismo\App\CompraController;
use App\Entity\Turismo\Compra;
use App\Entity\Turismo\Passageiro;
use App\Entity\Turismo\Viagem;
use App\EntityHandler\Turismo\PassageiroEntityHandler;
use App\EntityHandler\Turismo\ViagemEntityHandler;
use App\Form\Turismo\PassageiroType;
use App\Form\Turismo\ViagemType;
use App\Repository\Turismo\ViagemRepository;
use CrosierSource\CrosierLibBaseBundle\Controller\FormListController;
use CrosierSource\CrosierLibBaseBundle\Exception\ViewException;
use CrosierSource\CrosierLibBaseBundle\Utils\ExceptionUtils\ExceptionUtils;
use CrosierSource\CrosierLibBaseBundle\Utils\RepositoryUtils\FilterData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class ViagemController extends FormListController
{

    private PassageiroEntityHandler $passageiroEntityHandler;

    /**
     * @required
     * @param ViagemEntityHandler $entityHandler
     */
    public function setEntityHandler(ViagemEntityHandler $entityHandler): void
    {
        $this->entityHandler = $entityHandler;
    }

    /**
     * @required
     * @param PassageiroEntityHandler $passageiroEntityHandler
     */
    public function setPassageiroEntityHandler(PassageiroEntityHandler $passageiroEntityHandler): void
    {
        $this->passageiroEntityHandler = $passageiroEntityHandler;
    }


    public function getFilterDatas(array $params): array
    {
        return [
            new FilterData(['pedido'], 'LIKE', 'pedido', $params)
        ];
    }

    /**
     *
     * @Route("/tur/viagem/form/{id}", name="viagem_form", defaults={"id"=null}, requirements={"id"="\d+"})
     * @param Request $request
     * @param Viagem|null $viagem
     * @return RedirectResponse|Response
     * @throws \Exception
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function form(Request $request, Viagem $viagem = null)
    {
        $params = [
            'typeClass' => ViagemType::class,
            'formView' => 'Turismo/viagem_form.html.twig',
            'formRoute' => 'viagem_form',
            'formPageTitle' => 'Viagem'
        ];
        if ($viagem && $viagem->getId()) {
            /** @var ViagemRepository $repoViagem */
            $repoViagem = $this->getDoctrine()->getRepository(Viagem::class);
            $params['poltronas'] = $repoViagem->handlePoltronas($viagem);
        }
        return $this->doForm($request, $viagem, $params);
    }

    /**
     *
     * @Route("/tur/viagem/list/", name="viagem_list")
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function list(Request $request): Response
    {
        $params = [
            'formRoute' => 'viagem_form',
            'listView' => '@CrosierLibBase/list.html.twig',
            'listJS' => 'Turismo/viagemList.js',
            'listRoute' => 'viagem_list',
            'listRouteAjax' => 'viagem_datatablesJsList',
            'listPageTitle' => 'Viagens',
            'deleteRoute' => 'viagem_delete',
            'listId' => 'viagemList'
        ];
        return $this->doList($request, $params);
    }

    /**
     *
     * @Route("/tur/viagem/datatablesJsList/", name="viagem_datatablesJsList")
     * @param Request $request
     * @return Response
     * @throws ViewException
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function datatablesJsList(Request $request): Response
    {
        return $this->doDatatablesJsList($request);
    }

    /**
     *
     * @Route("/tur/viagem/delete/{id}/", name="viagem_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param Viagem $viagem
     * @return RedirectResponse
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function delete(Request $request, Viagem $viagem): RedirectResponse
    {
        return $this->doDelete($request, $viagem, []);
    }


    /**
     *
     * @Route("/tur/viagem/passageiro/form/{viagem}/{passageiro}", name="viagem_passageiro_form", defaults={"passageiro"=null}, requirements={"viagem"="\d+","passageiro"="\d+"})
     *
     * @ParamConverter("viagem", class="App\Entity\Turismo\Viagem", options={"id" = "viagem"})
     * @ParamConverter("passageiro", class="App\Entity\Turismo\Passageiro", options={"id" = "passageiro"})
     * @param Request $request
     * @param SessionInterface $session
     * @param Viagem|null $viagem
     * @param Passageiro|null $passageiro
     * @return RedirectResponse|Response
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function passageiroForm(Request $request, SessionInterface $session, Viagem $viagem, ?Passageiro $passageiro = null)
    {
        $params = [
            'formView' => 'Turismo/viagem_formPassageiro.html.twig',
            'formJS' => 'Turismo/viagemPassageiroForm.js',
            'formRoute' => 'viagem_passageiro_form',
            'formRouteParams' => ['viagem' => $viagem->getId()],
            'page_title' => 'Passageiro',
            'formPageTitle' => 'Passageiro',
            'formPageSubTitle' => 'Viagem: ' . $viagem->itinerario->getDescricaoMontada(),
            'viagem' => $viagem
        ];

        if ($request->get('rtf') && strpos($request->server->get('HTTP_REFERER'), $request->server->get('REQUEST_URI')) === FALSE) {
            $sessRtb = ['viagem_passageiro_form' => $request->server->get('HTTP_REFERER')];
            $session->set('refstoback', $sessRtb);
        }

        $form = $this->createForm(PassageiroType::class, $passageiro);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    /** @var Passageiro $passageiro */
                    $passageiro = $form->getData();
                    $passageiro->viagem = $viagem;
                    $passageiro = $this->passageiroEntityHandler->save($passageiro);
                    $this->addFlash('success', 'Registro salvo com sucesso!');
                    return $this->redirectTo($request, $viagem, $params['formRoute'], $params['formRouteParams']);
                } catch (ViewException $e) {
                    $this->addFlash('error', $e->getMessage());
                } catch (\Exception $e) {
                    $msg = ExceptionUtils::treatException($e);
                    $this->addFlash('error', $msg);
                    $this->addFlash('error', 'Erro ao salvar!');
                }
            } else {
                $errors = $form->getErrors(true, true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }
        // Pode ou não ter vindo algo no $params. Independentemente disto, só adiciono form e foi-se.
        $params['form'] = $form->createView();
        $params['e'] = $passageiro;

        return $this->doRender($params['formView'], $params);
    }


    /**
     *
     * @Route("/tur/viagem/passageiro/delete/{passageiro}", name="viagem_passageiro_delete", requirements={"passageiro"="\d+"})
     *
     * @ParamConverter("passageiro", class="App\Entity\Turismo\Passageiro", options={"id" = "passageiro"})
     *
     * @param Passageiro $passageiro
     * @return RedirectResponse
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function passageiroDelete(Passageiro $passageiro): RedirectResponse
    {
        try {
            $this->passageiroEntityHandler->delete($passageiro);
            $this->addFlash('success', 'Passageiro removido com sucesso!');
        } catch (ViewException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('viagem_form', ['id' => $passageiro->viagem->getId(), '_fragment' => 'passageiros']);
    }


    /**
     *
     * @Route("/app/tur/viagem/reenviarEmailCompraEfetuada/{compra}", name="tur_viagem_reenviarEmailCompraEfetuada")
     * @param Request $request
     * @param CompraController $compraController
     * @param MailerInterface $mailer
     * @param Compra $compra
     * @return RedirectResponse|Response
     */
    public function reenviarEmailCompraEfetuada(Request $request, CompraController $compraController, MailerInterface $mailer, Compra $compra): Response
    {
        try {
            if (!$this->isCsrfTokenValid('reenviarEmailCompraEfetuada', $request->get('token'))) {
                throw new ViewException('Token inválido');
            }
            $compraController->emailCompraEfetuada($mailer, $compra);
            $this->addFlash('success', 'E-mail enviado com sucesso');
        } catch (ViewException $e) {
            $this->addFlash('error', 'Erro ao reenviar o e-mail');
            $this->addFlash('error', $e->getMessage());
        }
        if ($request->get('rtr')) {
            return $this->redirect($request->server->get('HTTP_REFERER'));
        }
        return $this->redirectToRoute('viagem_form', ['id' => $compra->viagem->getId()]);
    }

    /**
     *
     * @Route("/tur/viagem/visualizar/{id}", name="viagem_visualizar", requirements={"id"="\d+"})
     * @param Request $request
     * @param Viagem|null $viagem
     * @return RedirectResponse|Response
     * @throws \Exception
     *
     * @Security("is_granted('ROLE_TURISMO') or is_granted('ROLE_AGENCIA')")
     */
    public function visualizar(Request $request, Viagem $viagem = null)
    {
        $params['viagem'] = $viagem;
        /** @var ViagemRepository $repoViagem */
        $repoViagem = $this->getDoctrine()->getRepository(Viagem::class);
        $params['poltronas'] = $repoViagem->handlePoltronas($viagem);
        return $this->doRender('Turismo/viagem_visualizar.html.twig', $params);
    }


}