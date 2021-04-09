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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

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
        return $this->render('demandeur/index.html.twig');
    }

    /**
     * @Route("/demandeurProfile", name="app_demandeur_profile")
     */
    public function profile(): Response
    {
        return $this->render('demandeur/profile.html.twig');
    }

    /**
     * @Route("/adddemandeur", name="app_demandeur_add", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addDemandeur(Request $request, UploadedFile $uploadedFile){
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
                    $demandeur->setPhoto($request->request->get('photoProfile'));
                    $demandeur->setUser($this->getUser());
                    dd($request->request->get('photoProfile'));
                    if(!empty($request->files->get(photoProfile))){
                        $photoFile = $request->files->get('photoProfile');
                        $photoFile = $request->files->get('photoProfile');
                        $photoname = $photoFile->getClientOriginalName ();
                        $originalFileName=pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // Move the file to the directory where photo are stored
                        try {
                                $uploadedFile->
                            $photoFile->move(
                                $this->getParameter('C:\MyProject\Emploi\emploi\public\photoprofile'),

                            );
                        } catch (FileException $e) {
                            // ... handle exception if something happens during file upload
                            dd("Oups!... erreur de chargement de la photo de profile");
                        }

                        // updates the 'photoFilename' property to store the PDF file name
                        // instead of its contents
                        $demandeur->setPhoto($newFilename);
                        dd("photo");
                    }
                    dd("okey");
                    $this->em->persist($demandeur);
                    $this->em->flush();
                    return $this->render('demandeur/index.html.twig', ["demandeur"=>$demandeur]);
                }

            }
        }
    }
}
