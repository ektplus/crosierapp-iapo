<?php

namespace App\Controller\Turismo;

use App\Entity\Turismo\Veiculo;
use App\EntityHandler\Turismo\VeiculoEntityHandler;
use App\Form\Turismo\VeiculoType;
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
class VeiculoController extends FormListController
{

    /**
     * @required
     * @param VeiculoEntityHandler $entityHandler
     */
    public function setEntityHandler(VeiculoEntityHandler $entityHandler): void
    {
        $this->entityHandler = $entityHandler;
    }

    public function getFilterDatas(array $params): array
    {
        return [
            new FilterData(['apelido'], 'LIKE', 'apelido', $params)
        ];
    }

    /**
     *
     * @Route("/tur/veiculo/form/{id}", name="veiculo_form", defaults={"id"=null}, requirements={"id"="\d+"})
     * @param Request $request
     * @param Veiculo|null $veiculo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function form(Request $request, Veiculo $veiculo = null)
    {
        $params = [
            'typeClass' => VeiculoType::class,
            'formView' => '@CrosierLibBase/form.html.twig',
            'formRoute' => 'veiculo_form',
            'formPageTitle' => 'Veículo'
        ];
        return $this->doForm($request, $veiculo, $params);
    }

    /**
     *
     * @Route("/tur/veiculo/list/", name="veiculo_list")
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function list(Request $request): Response
    {
        $params = [
            'formRoute' => 'veiculo_form',
            'listView' => '@CrosierLibBase/list.html.twig',
            'listJS' => 'Turismo/veiculoList.js',
            'listRoute' => 'veiculo_list',
            'listRouteAjax' => 'veiculo_datatablesJsList',
            'listPageTitle' => 'Veículos',
            'deleteRoute' => 'veiculo_delete',
            'listId' => 'veiculoList'
        ];
        return $this->doList($request, $params);
    }

    /**
     *
     * @Route("/tur/veiculo/datatablesJsList/", name="veiculo_datatablesJsList")
     * @param Request $request
     * @return Response
     * @throws \CrosierSource\CrosierLibBaseBundle\Exception\ViewException
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function datatablesJsList(Request $request): Response
    {
        return $this->doDatatablesJsList($request);
    }

    /**
     *
     * @Route("/tur/veiculo/delete/{id}/", name="veiculo_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param Veiculo $veiculo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function delete(Request $request, Veiculo $veiculo): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->doDelete($request, $veiculo);
    }


}