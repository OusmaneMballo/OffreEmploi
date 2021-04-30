<?php

namespace App\Controller;

use App\Entity\Domaine;
use App\Entity\Offre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    /**
     * @Route("/addoffre", name="app_offre_add", methods={"POST"})
     */
    public function addOffre(Request $request): Response
    {
        if ($request->isMethod("POST")){
            if ($this->isCsrfTokenValid('offre', $request->request->get('offre_token'))){
                $offre=new Offre();
                if($request->request->get('titre')!='' && $request->request->get('domaine')!='' && $request->request->get('ville')!='' && $request->request->get('jobType')!='' && $request->request->get('description')!=''){
                    $offre->setDescription($request->request->get('description'));
                    $offre->setDomaines($this->domaineRepository->find($request->request->get('domaine')));
                    $offre->setTitre($request->request->get('titre'));
                    $offre->setType($request->request->get('jobType'));
                    $offre->setVille($request->request->get('ville'));
                    $offre->setNiveauEtude($request->request->get('niveauEtude'));
                    $offre->setNbrAnneeExperience($request->request->get('anneeExperience'));
                    $offre->setEntreprise($this->getUser()->getEntreprise());
                    $this->em->persist($offre);
                    $this->em->flush();
                }
            }
        }
        return $this->redirectToRoute("app_offre");
    }
}
