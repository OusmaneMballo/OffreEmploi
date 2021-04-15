<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\SecteurActivite;
use App\service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseController extends AbstractController
{
    private $em;
    private $entrepriseRepository;
    private $secteurRepository;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
        $this->entrepriseRepository=$this->em->getRepository(Entreprise::class);
        $this->secteurRepository=$this->em->getRepository(SecteurActivite::class);
    }

    /**
     * @Route("/entreprise", name="app_entreprise")
     */
    public function index(): Response
    {
        return $this->render('entreprise/index.html.twig', [
            'secteurs' => $this->secteurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/entreprise/edit", name="app_entreprise_edit", methods={"POST"})
     * @param Request $request
     * @param string $uploadDir
     * @param FileUploader $uploaderFile
     * @return Response
     */
    public function profil(Request $request, FileUploader $uploaderFile): Response
    {
        if($request->isMethod("POST")) {
            if ($this->isCsrfTokenValid('entreprise', $request->request->get('entreprise_token'))) {
                $entreprise = new Entreprise();
                if ($request->request->get('denomination') != '' && $request->request->get('telephone') != '' && $request->request->get('email') != '' && $request->request->get('adresse') != '' && $request->request->get('description') != '') {
                    $entreprise->setNom($request->request->get('denomination'));
                    $entreprise->setEmail($request->request->get('email'));
                    $entreprise->setAdresse($request->request->get('adresse'));
                    $entreprise->setTelephone($request->request->get('telephone'));
                    $entreprise->setLogo($request->request->get('logo'));
                    $entreprise->setDescription($request->request->get('description'));
                    $entreprise->setUser($this->getUser());
                    $secteursId=$request->request->get('secteurs');
                    foreach ($secteursId as $s) {
                        $entreprise->addSecteur($this->secteurRepository->find($s));
                    }

                    if (!empty($request->files->get('logo'))) {
                        $logoFile = $request->files->get('logo');

                        //Recuperation du nom du fichier
                        $logoname = $logoFile->getClientOriginalName();

                        //C:\MyProject\Emploi\emploi\public\photoprofile On appelle le service de chargement du fichier dans le repertoir specifié
                        $ok = $uploaderFile->upload('./../../public/logo', $logoFile, $logoname);

                        //On teste si le fichier est bien chargé
                        if (!$ok) {
                            return $this->redirectToRoute('app_entreprise');
                        } else {
                            $entreprise->setLogo($logoname);
                        }
                    }
                    $this->em->persist($entreprise);
                    $this->em->flush();
                    return $this->redirectToRoute('welcom');
                }

            }
        }
        return $this->redirectToRoute('app_entreprise');

    }
}
