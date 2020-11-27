<?php

namespace App\Controller\Turismo;

use App\Entity\Turismo\Agencia;
use App\Entity\Turismo\Viagem;
use App\EntityHandler\Turismo\AgenciaEntityHandler;
use App\Form\Turismo\AgenciaType;
use CrosierSource\CrosierLibBaseBundle\Controller\FormListController;
use CrosierSource\CrosierLibBaseBundle\Utils\RepositoryUtils\FilterData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Carlos Eduardo Pauluk
 */
class AgenciaController extends FormListController
{

    /**
     * @required
     * @param AgenciaEntityHandler $entityHandler
     */
    public function setEntityHandler(AgenciaEntityHandler $entityHandler): void
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
     * @Route("/tur/agencia/form/{id}", name="agencia_form", defaults={"id"=null}, requirements={"id"="\d+"})
     * @param Request $request
     * @param Agencia|null $agencia
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function form(Request $request, Agencia $agencia = null)
    {
        $params = [
            'typeClass' => AgenciaType::class,
            'formView' => '@CrosierLibBase/form.html.twig',
            'formRoute' => 'agencia_form',
            'formPageTitle' => 'Agência'
        ];
        return $this->doForm($request, $agencia, $params);
    }

    /**
     *
     * @Route("/tur/agencia/list/", name="agencia_list")
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function list(Request $request): Response
    {
        $params = [
            'formRoute' => 'agencia_form',
            'listView' => '@CrosierLibBase/list.html.twig',
            'listJS' => 'Turismo/agenciaList.js',
            'listRoute' => 'agencia_list',
            'listRouteAjax' => 'agencia_datatablesJsList',
            'listPageTitle' => 'Agências',
            'deleteRoute' => 'agencia_delete',
            'listId' => 'agenciaList'
        ];
        return $this->doList($request, $params);
    }

    /**
     *
     * @Route("/tur/agencia/datatablesJsList/", name="agencia_datatablesJsList")
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
     * @Route("/tur/agencia/delete/{id}/", name="agencia_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param Agencia $agencia
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @IsGranted("ROLE_TURISMO_ADMIN", statusCode=403)
     */
    public function delete(Request $request, Agencia $agencia): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->doDelete($request, $agencia, ['listRoute' => 'agencia_list']);
    }


    /**
     *
     * @Route("/tur/agencia/listarViagens/", name="agencia_listarViagens")
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @IsGranted("ROLE_AGENCIA", statusCode=403)
     */
    public function listarViagens(Request $request): Response
    {
        $repoAgencia = $this->getDoctrine()->getRepository(Agencia::class);
        /** @var Agencia $agencia */
        $agencia = $repoAgencia->findOneByFiltersSimpl([['user', 'EQ', $this->getUser()]]);

        $params = [
            'agencia' => $agencia,
            'listId' => 'agencia_listarViagens',
            'page_title' => 'Viagens',
            'page_subTitle' => 'Agência: ' . $agencia->nome
        ];

        $repoViagem = $this->getDoctrine()->getRepository(Viagem::class);
        $viagens = $repoViagem->findByFiltersSimpl([['agencia', 'EQ', $agencia]], ['dtHrRetorno' => 'DESC']);
        $params['viagens'] = $viagens;

        return $this->doRender('Turismo/agencia_viagens.html.twig', $params);
    }


}