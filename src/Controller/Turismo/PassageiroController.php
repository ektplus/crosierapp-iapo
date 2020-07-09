<?php

namespace App\Controller\Turismo;

use App\Entity\Turismo\Passageiro;
use App\EntityHandler\Turismo\PassageiroEntityHandler;
use App\Form\Turismo\PassageiroType;
use App\Repository\Turismo\PassageiroRepository;
use CrosierSource\CrosierLibBaseBundle\Controller\FormListController;
use CrosierSource\CrosierLibBaseBundle\Utils\EntityIdUtils\EntityIdUtils;
use CrosierSource\CrosierLibBaseBundle\Utils\RepositoryUtils\FilterData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class PassageiroController extends FormListController
{

    /**
     * @required
     * @param PassageiroEntityHandler $entityHandler
     */
    public function setEntityHandler(PassageiroEntityHandler $entityHandler): void
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
     * @Route("/tur/passageiro/form/{id}", name="passageiro_form", defaults={"id"=null}, requirements={"id"="\d+"})
     * @param Request $request
     * @param Passageiro|null $passageiro
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     *
     * @IsGranted({"ROLE_TURISMO_ADMIN"}, statusCode=403)
     */
    public function form(Request $request, Passageiro $passageiro = null)
    {
        $params = [
            'typeClass' => PassageiroType::class,
            'formView' => '@CrosierLibBase/form.html.twig',
            'formRoute' => 'passageiro_form',
            'formPageTitle' => 'Passageiro'
        ];
        return $this->doForm($request, $passageiro, $params);
    }

    /**
     *
     * @Route("/tur/passageiro/list/", name="passageiro_list")
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @IsGranted({"ROLE_TURISMO_ADMIN"}, statusCode=403)
     */
    public function list(Request $request): Response
    {
        $params = [
            'formRoute' => 'passageiro_form',
            'listView' => '@CrosierLibBase/list.html.twig',
            'listJS' => 'Turismo/passageiroList.js',
            'listRoute' => 'passageiro_list',
            'listRouteAjax' => 'passageiro_datatablesJsList',
            'listPageTitle' => 'Passageiros',
            'deleteRoute' => 'passageiro_delete',
            'listId' => 'passageiroList'
        ];
        return $this->doList($request, $params);
    }

    /**
     *
     * @Route("/tur/passageiro/datatablesJsList/", name="passageiro_datatablesJsList")
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
     * @Route("/tur/passageiro/delete/{id}/", name="passageiro_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param Passageiro $passageiro
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @IsGranted({"ROLE_TURISMO_ADMIN"}, statusCode=403)
     */
    public function delete(Request $request, Passageiro $passageiro): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->doDelete($request, $passageiro);
    }


    /**
     *
     * @Route("/tur/passageiro/findPassageiroBy/", name="passageiro_findPassageiroBy")
     * @param Request $request
     * @param EntityIdUtils $entityIdUtils
     * @return JsonResponse
     * @IsGranted({"ROLE_TURISMO_ADMIN"}, statusCode=403)
     */
    public function findPassageiroBy(Request $request, EntityIdUtils $entityIdUtils): JsonResponse
    {
        $v = $request->get('v') ?? '';
        $v = preg_replace("/[^0-9]/", "", $v);
        $tipoDoc = $request->get('tipoDoc') ?? '';
        /** @var PassageiroRepository $repoPassageiro */
        $repoPassageiro = $this->getDoctrine()->getRepository(Passageiro::class);
        $r = $repoPassageiro->findOneBy([$tipoDoc => $v], ['inserted' => 'DESC']);
        if ($r) {
            return new JsonResponse(['result' => $entityIdUtils->serialize($r)]);
        }


        return  new JsonResponse(null);
    }


}