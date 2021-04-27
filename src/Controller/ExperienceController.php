<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExperienceController extends AbstractController
{
    private $em;
    private $experienceRepos;
    public function __construct(EntityManagerInterface $emi)
    {
        $this->em =$emi;
        $this->experienceRepos=$this->em->getRepository(Experience::class);
    }

    /**
     * @Route("/experience", name="experience")
     */
    public function index(): Response
    {
        return $this->render('experience/index.html.twig', [
            'controller_name' => 'ExperienceController',
        ]);
    }

    /**
     * @Route("/addexperience", name="app_experience_add", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function addExperience(Request $request){
        if($request->isMethod("POST")){
            if($this->isCsrfTokenValid('experience', $request->request->get('experience_token'))){
                if($request->request->get('date_debut')!='' && $request->request->get('organisation')!='' && $request->request->get('post')!=''){
                    $experience=new Experience();
                    $experience->setDateDebut($request->request->get('date_debut'));
                    $experience->setOrganisation($request->request->get('organisation'));
                    $experience->setPoste($request->request->get('post'));
                    if($request->request->get('date_fin')!=null){
                        $experience->setDateFin($request->request->get('date_fin'));
                    }
                    if($request->request->get('mission')){
                        $experience->setMission($request->request->get('mission'));
                    }
                    $experience->setDemandeur($this->getUser()->getDemandeur());
                    $this->em->persist($experience);
                    $this->em->flush();
                    return new RedirectResponse('/demandeurProfile');
                }
            }
        }
        return new RedirectResponse('/demandeurProfile', ["error"=>true]);
    }

    /**
     * @Route("/experience/delete/{id<[0-9]+>}", name="app_experience_delete")
     * @param $id
     * @return RedirectResponse
     */
    public function deleteExperience($id){
        if($id!=null){
            $this->em->remove($this->experienceRepos->find($id));
            $this->em->flush();
        }
        return new RedirectResponse('/demandeurProfile');
    }
}
