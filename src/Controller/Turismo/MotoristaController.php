<?php

namespace App\Controller\Turismo;

use App\Entity\Turismo\Motorista;
use App\EntityHandler\Turismo\MotoristaEntityHandler;
use App\Form\Turismo\MotoristaType;
use CrosierSource\CrosierLibBaseBundle\Controller\FormListController;
use CrosierSource\CrosierLibBaseBundle\Utils\RepositoryUtils\FilterData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class MotoristaController extends FormListController
{

    /**
     * @required
     * @param MotoristaEntityHandler $entityHandler
     */
    public function setEntityHandler(MotoristaEntityHandler $entityHandler): void
    {
        $this->entityHandler = $entityHandler;
    }

    public function getFilterDatas(array $params): array
    {
        return [
            new FilterData(['nome'], 'LIKE', 'nome', $params)
        ];
    }

    /**
     *
     * @Route("/tur/motorista/form/{id}", name="motorista_form", defaults={"id"=null}, requirements={"id"="\d+"})
     * @param Request $request
     * @param Motorista|null $motorista
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     *
     * @IsGranted({"ROLE_TURISMO_ADMIN"}, statusCode=403)
     */
    public function form(Request $request, Motorista $motorista = null)
    {
        $params = [
            'typeClass' => MotoristaType::class,
            'formView' => '@CrosierLibBase/form.html.twig',
            'formRoute' => 'motorista_form',
            'formPageTitle' => 'Motorista'
        ];
        return $this->doForm($request, $motorista, $params);
    }

    /**
     *
     * @Route("/tur/motorista/list/", name="motorista_list")
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @IsGranted({"ROLE_TURISMO_ADMIN"}, statusCode=403)
     */
    public function list(Request $request): Response
    {
        $params = [
            'formRoute' => 'motorista_form',
            'listView' => '@CrosierLibBase/list.html.twig',
            'listJS' => 'Turismo/motoristaList.js',
            'listRoute' => 'motorista_list',
            'listRouteAjax' => 'motorista_datatablesJsList',
            'listPageTitle' => 'Motoristas',
            'deleteRoute' => 'motorista_delete',
            'listId' => 'motoristaList'
        ];
        return $this->doList($request, $params);
    }

    /**
     *
     * @Route("/tur/motorista/datatablesJsList/", name="motorista_datatablesJsList")
     * @param Request $request
     * @return Response
     * @throws \CrosierSource\CrosierLibBaseBundle\Exception\ViewException
     *
     * @IsGranted({"ROLE_TURISMO_ADMIN"}, statusCode=403)
     */
    public function datatablesJsList(Request $request): Response
    {
        return $this->doDatatablesJsList($request);
    }

    /**
     *
     * @Route("/tur/motorista/delete/{id}/", name="motorista_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param Motorista $motorista
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @IsGranted({"ROLE_TURISMO_ADMIN"}, statusCode=403)
     */
    public function delete(Request $request, Motorista $motorista): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->doDelete($request, $motorista);
    }


}