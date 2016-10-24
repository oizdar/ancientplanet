<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    private $templateData;
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $this->templateData['jumbotron']['header'] = 'Powitanie';
        $this->templateData['jumbotron']['content'] = 'Lorem ipsum .. .. .. .. ';
        return $this->render('default/index.html.twig', $this->templateData);
    }
}
