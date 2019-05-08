<?php

namespace App\Controller;

use App\Entity\Refeicao;
use App\EntityHandler\RefeicaoEntityHandler;
use App\Form\RefeicaoType;
use CrosierSource\CrosierLibBaseBundle\Controller\FormListController;
use CrosierSource\CrosierLibBaseBundle\Utils\RepositoryUtils\FilterData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller CRUD para a entidade Refeicao.
 * @package App\Controller\Refeicao
 * @author Andreia Maritsa Azevedo
 */
class RefeicaoController extends FormListController
{

    protected $crudParams =
        [
            'typeClass' => RefeicaoType::class,

            'formView' => '@CrosierLibBase/form.html.twig',
            'formRoute' => 'refeicao_form',
            'formPageTitle' => 'Refeição',
            'form_PROGRAM_UUID' => 'e2713f87-034b-4e59-ab85-f485b53e28b2',

            'listView' => 'refeicaoList.html.twig',
            'listRoute' => 'refeicao_list',
            'listRouteAjax' => 'refeicao_datatablesJsList',
            'listPageTitle' => 'Refeições',
            'listId' => 'refeicaoList',
            'list_PROGRAM_UUID' => 'bce004df-ed52-4ec2-9a92-cea80a1c61fe',

            'normalizedAttrib' => [
                'id',
                'colaborador_id',
                'data',
                'qtde',
                'almoco',
                'jantar',
                'cafe_manha',
                'cafe_tarde',
                'updated',
                'inserted',
                'user_inserted_id',
                'user_inserted_id',
                'estabelecimento_id',
            ],

        ];

    /**
     * @required
     * @param RefeicaoEntityHandler $entityHandler
     */
    public function setEntityHandler(RefeicaoEntityHandler $entityHandler): void
    {
        $this->entityHandler = $entityHandler;
    }

    public function getFilterDatas(array $params): array
    {
        return [
            new FilterData(['nome'], 'LIKE', 'str', $params)
        ];
    }

    /**
     *
     * @Route("/refeicao/form/{id}", name="refeicao_form", defaults={"id"=null}, requirements={"id"="\d+"})
     * @param Request $request
     * @param Refeicao|null $refeicao
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function form(Request $request, Refeicao $refeicao = null)
    {
        return $this->doForm($request, $refeicao);
    }

    /**
     *
     * @Route("/refeicao/list/", name="refeicao_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function list(Request $request): Response
    {
        return $this->doList($request);
    }

    /**
     *
     * @Route("/refeicao/datatablesJsList/", name="refeicao_datatablesJsList")
     * @param Request $request
     * @return Response
     * @throws \CrosierSource\CrosierLibBaseBundle\Exception\ViewException
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function datatablesJsList(Request $request): Response
    {
        return $this->doDatatablesJsList($request);
    }

    /**
     *
     * @Route("/refeicao/delete/{id}/", name="refeicao_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param Refeicao $refeicao
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, Refeicao $refeicao): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->doDelete($request, $refeicao);
    }


}