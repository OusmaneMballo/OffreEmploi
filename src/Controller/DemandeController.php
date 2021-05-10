<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Domaine;
use App\Entity\Offre;
use App\service\FileDeleteService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\service\FileUploader;

class DemandeController extends AbstractController
{
    private $em;
    private $offreRepository;
    private $demandeRepository;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->em=$emi;
        $this->demandeRepository=$this->em->getRepository(Demande::class);
        $this->offreRepository=$this->em->getRepository(Offre::class);
    }
    /**
     * @Route("/demande", name="app_demande")
     */
    public function index(): Response
    {
        return $this->render('demande/index.html.twig', [
            'controller_name' => 'DemandeController',
        ]);
    }

    /**
     * @Route("/addDemande", name="app_demande_add", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addDemande(Request $request, string $uploadLettre, FileUploader $uploaderFile, string $uploadDirCv, FileDeleteService $fileDeleteService): Response
    {
        $exceptionTaille=false;
        if ($request->isMethod("POST")){
            if ($this->isCsrfTokenValid('candidature', $request->request->get('candidature_token'))){
                $demande=new Demande();
                //Cas ou le candidat saisi sa lettre de motivatio
                if($request->request->get('motivationtxt')!=''){
                    $demande->setTextMotivation($request->request->get('motivationtxt'));
                }
                else{
                    //Cas ou le candidat charge une lettre de motivation a partir de son device
                    if(!empty($request->files->get('motivationfile'))){
                        $lettreFile = $request->files->get('motivationfile');

                        //Recuperation du nom du fichier
                        $lettrename = $lettreFile->getClientOriginalName ();

                        //On appelle le service de chargement du fichier dans le repertoir specifié
                        $ok=$uploaderFile->upload($uploadLettre,$lettreFile,$lettrename);

                        //On teste si le fichier est bien chargé
                        if(!$ok){
                            //Erreur
                            $exceptionTaille=true;
                            return $this->render('offre/demandeurViewOffre.html.twig', ['offre' => $this->offreRepository->find($request->request->get('id_offre')), "offres"=>$this->offreRepository->findAll(), 'exceptionTaille'=>$exceptionTaille]);
                        }
                        else{
                            $demande->setTextMotivation($lettrename);
                        }
                    }
                    else{
                        //Message d'erreur!
                    }
                }
                //Cas ou le candidat charge un nouveau cv a partir de son device
                if (!empty($request->files->get('cv'))){
                    //On charge maintenant son cv
                    $cvFile = $request->files->get('cv');
                    //Recuperation du nom du fichier
                    $cvname = $cvFile->getClientOriginalName ();

                    //On appelle le service de chargement du fichier dans le repertoir specifié
                    $ok=$uploaderFile->upload($uploadDirCv,$cvFile,$cvname);

                    //On teste si le fichier est bien chargé
                    if(!$ok){
                        //Erreur
                        $exceptionTaille=true;
                        return $this->render('offre/demandeurViewOffre.html.twig', ['offre' => $this->offreRepository->find($request->request->get('id_offre')), "offres"=>$this->offreRepository->findAll(), 'exceptionTaille'=>$exceptionTaille]);
                    }
                    else{
                        $demande->setCv($cvname);
                    }
                }
                else{
                    $demande->setCv($this->getUser()->getDemandeur()->getCv());
                }
                $demande->setDate(date("Y-m-d H:i:s"));
                $demande->setDemandeur($this->getUser()->getDemandeur());
                $demande->setOffre($this->offreRepository->find($request->request->get('id_offre')));
                $demande->setReponse("En cours");
                $this->em->persist($demande);
                $this->em->flush();
            }
        }
        return $this->render('demande/index.html.twig');
    }

    /**
     * @Route("/demande/{id<[0-9]+>}", name="app_candidature")
     * @param $id
     * @return Response
     */
    public function demande($id): Response
    {
        $demande=$this->demandeRepository->find($id);
        $demandeur=$demande->getDemandeur();
        $offre=$demande->getOffre();
        return $this->render('demande/index.html.twig', ['demande' => $demande, 'demandeur'=>$demandeur, 'offre'=>$offre]);
    }
}
