<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Demandeur;
use App\Entity\Domaine;
use App\Entity\Profile;
use App\Entity\Role;
use App\Entity\User;
use App\service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class DemandeurController extends AbstractController
{
    private $em;
    private $userRepository;
    private $roleRepository;
    private $demandeurRepository;
    private $domaineRepository;
    private $profileRepository;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->em=$emi;
        $this->roleRepository=$this->em->getRepository(Role::class);
        $this->userRepository=$this->em->getRepository(User::class);
        $this->demandeurRepository=$this->em->getRepository(Demandeur::class);
        $this->domaineRepository=$this->em->getRepository(Domaine::class);
        $this->profileRepository=$this->em->getRepository(Profile::class);
    }

    /**
     * @Route("/demandeur", name="app_demandeur")
     */
    public function index(): Response
    {
        return $this->render('demandeur/index.html.twig',["domaines"=>$this->domaineRepository->findAll()]);
    }

    /**
     * @Route("/demandeurProfile", name="app_demandeur_profile")
     */
    public function profile(): Response
    {
        return $this->render('demandeur/profile.html.twig', ["demandeurs"=>$this->demandeurRepository->findAll()]);
    }

    /**
     * @Route("/adddemandeur", name="app_demandeur_add", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addDemandeur(Request $request, FileUploader $uploaderFile){
        if($request->isMethod("POST")){
            if ($this->isCsrfTokenValid('demandeur', $request->request->get('demandeur_token'))){
                $demandeur=new Demandeur();
                if($request->request->get('nomPrenom')!='' && $request->request->get('email')!='' && $request->request->get('sexe')!=''){
                    $demandeur->setNomPrenom($request->request->get('nomPrenom'));
                    $demandeur->setEmail($request->request->get('email'));
                    $demandeur->setAdresse($request->request->get('adresse'));
                    $demandeur->setPhoto($request->request->get('photoProfile'));
                    $demandeur->setSexe($request->request->get('sexe'));
                    $demandeur->setLieuNaissance($request->request->get('lieuNaiss'));
                    $demandeur->setDateNaissance($request->request->get('dateNaiss'));
                    $demandeur->setDomaine($this->domaineRepository->find($request->request->get('domaine')));
                    $demandeur->setProfile($this->profileRepository->find($request->request->get('profile')));
                    $demandeur->setUser($this->getUser());
                    if(!empty($request->files->get('photoProfile'))){
                        $photoFile = $request->files->get('photoProfile');

                        //Recuperation du nom du fichier
                        $photoname = $photoFile->getClientOriginalName ();

                        //On appelle le service de chargement du fichier dans le repertoir specifié
                        $ok=$uploaderFile->upload('C:\MyProject\Emploi\emploi\public\photoprofile',$photoFile,$photoname);

                        //On teste si le fichier est bien chargé
                        if(!$ok){
                            return $this->render('demandeur/index.html.twig', ["demandeur"=>$demandeur]);
                        }
                        else{
                            $demandeur->setPhoto($photoname);
                        }
                    }
                    $this->em->persist($demandeur);
                    $this->em->flush();
                    return $this->render('demandeur/profile.html.twig', ["demandeur"=>$demandeur]);
                }

            }
        }
    }

    /**
     * @Route("/demandeur/{id<[0-9]+>}", name="app_demandeur_edit")
     *
     */
    public function getDemandeurById(int $id){
        $demandeur=$this->demandeurRepository->find($id);
        if ($demandeur!=null){
            return $this->render('demandeur/edit.html.twig',
                ["demandeur"=>$demandeur, "domaines"=>$this->domaineRepository->findAll()]);
        }
    }

    /**
     * @Route("/adddemandeur", name="app_demandeur_add", methods={"POST"})
     * @param Request $request
     * @param FileUploader $uploaderFile
     * @return Response
     */
    public function update(Request $request, FileUploader $uploaderFile){
        if($request->isMethod("POST")){
            if ($this->isCsrfTokenValid('edit_demandeur', $request->request->get('edit_demandeur_token'))){
                $demandeur=$this->demandeurRepository->find($request->request->get('id'));
                $demandeur->setEmail($request->request->get('email'));
                $demandeur->setNomPrenom($request->request->get('nomPrenom'));
                $demandeur->setAdresse($request->request->get('adresse'));
                $demandeur->setSexe($request->request->get('sexe'));
                $demandeur->setLieuNaissance($request->request->get('lieuNaiss'));
                $demandeur->setDateNaissance($request->request->get('dateNaiss'));
                $demandeur->setDomaine($this->domaineRepository->find($request->request->get('domaine')));
                $demandeur->setProfile($this->profileRepository->find($request->request->get('profile')));
                if($request->files->get('photoProfile')!=null){
                    $photoFile = $request->files->get('photoProfile');
                    //Recuperation du nom du fichier
                    $photoname = $photoFile->getClientOriginalName ();

                    //On appelle le service de chargement du fichier dans le repertoir specifié
                    $ok=$uploaderFile->upload('C:\MyProject\Emploi\emploi\public\photoprofile',$photoFile,$photoname);

                    //On teste si le fichier est bien chargé
                    if(!$ok){
                        return $this->render('demandeur/index.html.twig', ["demandeur"=>$demandeur]);
                    }
                    else{
                        $demandeur->setPhoto($photoname);
                    }
                }

                $this->em->flush();
                return $this->render('demandeur/profile.html.twig', ["demandeur"=>$demandeur]);

            }
        }
        return $this->render('demandeur/profile.html.twig');
    }
}
