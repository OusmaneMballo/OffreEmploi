<?php

namespace App\Controller;

use App\Entity\Demandeur;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemandeurController extends AbstractController
{
    private $em;
    private $userRepository;
    private $roleRepository;
    private $demandeurRepository;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->em=$emi;
        $this->roleRepository=$this->em->getRepository(Role::class);
        $this->userRepository=$this->em->getRepository(User::class);
        $this->demandeurRepository=$this->em->getRepository(Demandeur::class);
    }

    /**
     * @Route("/demandeur", name="app_demandeur")
     */
    public function index(): Response
    {
        return $this->render('demandeur/index.html.twig', [
            'controller_name' => 'DemandeurController',
        ]);
    }

    /**
     * @Route("/adddemandeur", name="app_demandeur_add", methods={"POST"})
     */
    public function addDemandeur(Request $request){
        if($request->isMethod("POST")){
            if ($this->isCsrfTokenValid('demandeur', $request->request->get('demandeur_token'))){
                $demandeur=new Demandeur();
                if($request->request->get('nomPrenom')!='' && $request->request->get('email')!='' && $request->request->get('sexe')!=''){
                    $demandeur->setEmail($request->request->get('email'));
                    $demandeur->setAdresse($request->request->get('adresse'));
                    $demandeur->setPhoto($request->request->get('photoProfile'));
                    $demandeur->setSexe($request->request->get('sexe'));
                }

            }
        }
    }
}
