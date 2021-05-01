<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Entreprise;
use App\Entity\Offre;
use App\service\ApplicationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomController extends AbstractController
{
    private $offreRepository;
    private $entrepriseRepository;
    private $demandeRepository;
    private $em;
    private $appService;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->em=$emi;
        $this->offreRepository=$this->em->getRepository(Offre::class);
        $this->entrepriseRepository=$this->em->getRepository(Entreprise::class);
        $this->demandeRepository=$this->em->getRepository(Demande::class);
        $this->appService=new ApplicationService();
    }

    /**
     * @Route("/", name="welcom")
     */
    public function index(): Response
    {
        $offres=$this->offreRepository->findAll();
        $nbrOffres=$this->appService->count($offres);
        $nbrEntreprise=$this->appService->count($this->entrepriseRepository->findAll());
        $nbrDemande=$this->appService->count($this->demandeRepository->findAll());
        return $this->render('welcom/index.html.twig', ['offres' => $offres, 'nbrEntreprise'=>$nbrEntreprise, 'nbrOffre'=>$nbrOffres, 'nbrCandidature'=>$nbrDemande]);
    }

    /**
     * @Route("/propos", name="welcom_propos")
     */
    public function propos(): Response
    {
        return $this->render('welcom/propos.html.twig');
    }

    /**
     * @Route("/contacts", name="welcom_contact")
     */
    public function contact(): Response
    {
        return $this->render('welcom/contact.html.twig');
    }
}
