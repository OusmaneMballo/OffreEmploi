<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $em;
    private $userRepository;
    private $roleRepository;
    private $encoder;
    private static $is_signin;
    private $error=false;

    public function __construct(EntityManagerInterface $emi, UserPasswordEncoderInterface $uspi)
    {
        $this->em=$emi;
        $this->encoder=$uspi;
        $this->userRepository=$this->em->getRepository(User::class);
        $this->roleRepository=$this->em->getRepository(Role::class);
    }

    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $roles=$this->roleRepository->findAll();
        $data=['last_username' => $lastUsername, 'error' => $error, 'roles'=>$roles, "is_signin"=>$this::$is_signin];
        return $this->render('security/login.html.twig', $data);
    }

    /**
     * @Route("/signin", name="app_signin", methods={"POST"})
     */
    public function signin(Request $request){
        if($request->isMethod("POST")){
            if ($this->isCsrfTokenValid('inscription', $request->request->get('signin_token'))){
                $user=new User();
                if($request->request->get('email')!='' && $request->request->get('passwd')!=''){
                    if($request->request->get('role')!=''){
                        $user->setEmail($request->request->get('email'));
                        $hash =$this->encoder->encodePassword($user, $request->request->get("passwd"));
                        $user->setPassword($hash);
                        $user->addRole($this->roleRepository->findOneBylibelle($request->request->get("role")));
                        $this->em->persist($user);
                        $this->em->flush();
                        $this::$is_signin=true;
                        return new RedirectResponse('/login');
                    }

                }
            }
        }
        $this->error=true;
        return new RedirectResponse('/login');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
