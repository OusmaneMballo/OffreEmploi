<?php

namespace App\Controller;

use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/adddemandeur", name="app_formation_add", methods={"POST"})
     */
    public function addFormation(Request $request){
        if($request->isMethod("POST")){

        }
    }
}
