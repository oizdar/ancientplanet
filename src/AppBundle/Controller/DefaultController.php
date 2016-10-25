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
        $extras = $this->getDoctrine()->getRepository('AppBundle:Extras');
        $extra = $extras->findOneByType('jumbotron');
        $this->templateData['jumbotron']['header'] = $extra->getHeader();
        $this->templateData['jumbotron']['content'] = $extra->getContent();
        return $this->render('default/index.html.twig', $this->templateData);
    }
}
