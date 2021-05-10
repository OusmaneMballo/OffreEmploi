<?php

namespace App\Controller;

use App\Entity\Domaine;
use App\Entity\Offre;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
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
     * @Route("/offres", name="app_offre")
     */
    public function index(): Response
    {
        $offres=$this->offreRepository->findByEntreprise($this->getUser()->getEntreprise());
        $size=0;
        foreach ($offres as $offre){
            $size++;
        }
        return $this->render('offre/listeoffres.html.twig', ['offres' => $offres, 'size'=>$size]);
    }

    /**
     * @Route("/offreadd", name="app_offre_form")
     */
    public function form(): Response
    {
        return $this->render('offre/index.html.twig', ['domaines' => $this->domaineRepository->findAll(),]);
    }

    /**
     * @Route("/offre/{id<[0-9]+>}", name="app_one_offre")
     * @param $id
     * @return Response
     */
    public function offre($id): Response
    {
        $offres=$this->offreRepository->findByEntreprise($this->getUser()->getEntreprise());
        $offreDetail=$this->offreRepository->find($id);

        $size=0;
        foreach ($offres as $offre){
            $size++;
        }

        $nbrCandidats=0;
        foreach ($offreDetail->getDemandes() as $demande){
            $nbrCandidats++;
        }
        return $this->render('offre/offre.html.twig', ['offre' => $offreDetail, "entreprise"=>$this->getUser()->getEntreprise(), "offres"=>$offres, "size"=>$size, 'nbrCandidats'=>$nbrCandidats]);
    }

    /**
     * @Route("/demandeur/offre/{id<[0-9]+>}", name="app_demandeur_offre")
     * @param $id
     * @return Response
     */
    public function demandeurViewOffre($id): Response
    {
        return $this->render('offre/demandeurViewOffre.html.twig', ['offre' => $this->offreRepository->find($id), "offres"=>$this->offreRepository->findAll()]);
    }

    /**
     * @Route("/addoffre", name="app_offre_add", methods={"POST"})
     * @param Request $request
     * @return Response
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

    /**
     * @Route("/edit/{id<[0-9]+>}", name="app_offre_edit")
     * @param $id
     * @return Response
     */
    public function edit($id): Response
    {
        if($id!=null){
            return $this->render('offre/edit.html.twig', ['offre' => $this->offreRepository->find($id), "domaines"=>$this->domaineRepository->findAll()]);
        }
        return $this->redirectToRoute("app_offre");
    }

    /**
     * @Route("/updateOffre", name="app_offre_update", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function update(Request $request){
        $id=$request->request->get('id');
        if ($request->isMethod("POST")){
            if ($this->isCsrfTokenValid('edit', $request->request->get('offre_edit_token'))){
                $offre=$this->offreRepository->find($id);
                $offre->setDescription($request->request->get('description'));
                $offre->setDomaines($this->domaineRepository->find($request->request->get('domaine')));
                $offre->setTitre($request->request->get('titre'));
                $offre->setType($request->request->get('jobType'));
                $offre->setVille($request->request->get('ville'));
                $offre->setNiveauEtude($request->request->get('niveauEtude'));
                $offre->setNbrAnneeExperience($request->request->get('anneeExperience'));
                $offre->setEntreprise($this->getUser()->getEntreprise());
                $this->em->flush();
                return $this->redirectToRoute("app_one_offre", ['id'=>$id]);
            }
        }
        return $this->redirectToRoute("app_offre_edit", ['id'=>$id]);
    }

    /**
     * @Route("/deleteoffre/{id<[0-9]+>}", name="app_offre_delete")
     * @param $id
     * @return Response
     */
    public function delete($id): Response
    {
        if($id!=null){
            $this->em->remove($this->offreRepository->find($id));
            $this->em->flush();
            return $this->redirectToRoute("app_offre");
        }
        return $this->redirectToRoute("app_one_offre", ['id'=>$id]);
    }

    /**
     * @Route("/poster/{id<[0-9]+>}", name="app_poste")
     * @param $id
     * @return Response
     */
    public function poster($id):Response{
        if($id!=null){
            $offre=$this->offreRepository->find($id);
            $user = $this->getUser();
            if ($user!=null && $user->getRoles()[1]=="ROLE_DEMANDEUR"){
                return $this->render('offre/edit.html.twig', ['offre' => $offre]);
            }
            else{
                dd("Non connected!");
            }
            return $this->render('offre/edit.html.twig', ['offre' => $offre]);
        }
        return $this->redirectToRoute("app_offre");
    }
}
