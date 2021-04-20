<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomController extends AbstractController
{
    /**
     * @Route("/", name="welcom")
     */
    public function index(): Response
    {
        return $this->render('welcom/index.html.twig', [
            'controller_name' => 'WelcomController',
        ]);
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
