<?php

namespace App\Controller;

use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{

    private $em;
    private $formationRepository;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->em=$emi;
        $this->formationRepository=$this->em->getRepository(Formation::class);
    }

    /**
     * @Route("/formation", name="formation")
     */
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }

    /**
     * @Route("/addformation", name="app_formation_add", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function addFormation(Request $request){
        if($request->isMethod("POST")){
            if($this->isCsrfTokenValid('formation', $request->request->get('formation_token'))){
                if($request->request->get('date_debut')!='' && $request->request->get('etablissement')!='' && $request->request->get('intitule')!=''){
                    $formation=new Formation();
                    $formation->setDateDebut($request->request->get('date_debut'));
                    $formation->setEtablissement($request->request->get('etablissement'));
                    $formation->setIntitule($request->request->get('intitule'));
                    if($request->request->get('date_fin')!=null){
                        $formation->setDateFin($request->request->get('date_fin'));
                    }
                    if($request->request->get('description')){
                        $formation->setDescription($request->request->get('description'));
                    }
                    $formation->setDemandeur($this->getUser()->getDemandeur());
                    $this->em->persist($formation);
                    $this->em->flush();
                    return new RedirectResponse('/demandeurProfile');
                }
            }
        }
        return new RedirectResponse('/demandeurProfile', ["error"=>true]);
    }
}
