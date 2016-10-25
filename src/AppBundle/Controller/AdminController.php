<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminController extends Controller
{
    private $templateData = [];

    /**
     * @Route("/admin", name="admin_homepage")
     */
    public function indexAction()
    {
            return $this->render('admin/index.html.twig', $this->templateData);
    }

    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function loginAction(Request $request)
    {
        /*$user = new \AppBundle\Entity\User();
$plainPassword = 'pass';
$encoder = $this->container->get('security.password_encoder');
$encoded = $encoder->encodePassword($user, $plainPassword);

$user->setPassword($encoded);*/

        $authenticationUtils = $this->get('security.authentication_utils');
        $this->templateData['error'] = $authenticationUtils->getLastAuthenticationError();
        $this->templateData['username'] = $authenticationUtils->getLastUsername();
        return $this->render('admin/login.html.twig', $this->templateData);
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logoutAction(Request $request)
    {
    }
}
