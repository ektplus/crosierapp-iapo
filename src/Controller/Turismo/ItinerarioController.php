<?php

namespace App\Controller\Turismo;

use App\Entity\Turismo\Itinerario;
use App\EntityHandler\Turismo\ItinerarioEntityHandler;
use App\Form\Turismo\ItinerarioType;
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
class ItinerarioController extends FormListController
{

    /**
     * @required
     * @param ItinerarioEntityHandler $entityHandler
     */
    public function setEntityHandler(ItinerarioEntityHandler $entityHandler): void
    {
        $this->entityHandler = $entityHandler;
    }

    public function getFilterDatas(array $params): array
    {
        return [
            new FilterData(['cidadeOrigem', 'cidadeDestino'], 'LIKE', 'str', $params)
        ];
    }

    /**
     *
     * @Route("/tur/itinerario/form/{id}", name="itinerario_form", defaults={"id"=null}, requirements={"id"="\d+"})
     * @param Request $request
     * @param Itinerario|null $itinerario
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function form(Request $request, Itinerario $itinerario = null)
    {
        $params = [
            'typeClass' => ItinerarioType::class,
            'formView' => '@CrosierLibBase/form.html.twig',
            'formRoute' => 'itinerario_form',
            'formPageTitle' => 'Itinerário'
        ];
        return $this->doForm($request, $itinerario, $params);
    }

    /**
     *
     * @Route("/tur/itinerario/list/", name="itinerario_list")
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function list(Request $request): Response
    {
        $params = [
            'formRoute' => 'itinerario_form',
            'listView' => '@CrosierLibBase/list.html.twig',
            'listJS' => 'Turismo/itinerarioList.js',
            'listRoute' => 'itinerario_list',
            'listRouteAjax' => 'itinerario_datatablesJsList',
            'listPageTitle' => 'Itinerários',
            'deleteRoute' => 'itinerario_delete',
            'listId' => 'itinerarioList'
        ];
        return $this->doList($request, $params);
    }

    /**
     *
     * @Route("/tur/itinerario/datatablesJsList/", name="itinerario_datatablesJsList")
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
     * @Route("/tur/itinerario/delete/{id}/", name="itinerario_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param Itinerario $itinerario
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function delete(Request $request, Itinerario $itinerario): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->doDelete($request, $itinerario);
    }


}