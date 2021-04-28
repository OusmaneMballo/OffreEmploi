<?php

namespace App\Controller;

use App\Entity\Domaine;
use App\Entity\Offre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreController extends AbstractController
{
    private $em;
    private $offreRepository;
    private $domaineRepository;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->em=$emi;
        $this->domaineRepository=$this->em->getRepository(Domaine::class);
        $this->offreRepository=$this->em->getRepository(Offre::class);
    }

    /**
     * @Route("/offre", name="app_offre")
     */
    public function index(): Response
    {
        return $this->render('offre/index.html.twig', ['domaines' => $this->domaineRepository->findAll(),]);
    }
}
