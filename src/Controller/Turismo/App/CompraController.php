<?php

namespace App\Controller\Turismo\App;

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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class CompraController extends FormListController
{



    /**
     *
     * @Route("/app/tur/compra/ini", name="tur_app_compra_ini")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     *
     */
    public function ini(Request $request)
    {
        $params = [];

        /** @var ViagemRepository $repoViagens */
        $repoViagens = $this->getDoctrine()->getRepository(Viagem::class);
        $cidadesOrigens = $repoViagens->buildSelect2CidadesOrigensViagensProgramadas();

        $params['cidadesOrigens'] = json_encode($cidadesOrigens);
        return $this->render('Turismo/App/form_passagem_pesquisarViagens.html.twig', $params);
    }




}