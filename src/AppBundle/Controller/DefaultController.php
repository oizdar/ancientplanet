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
        $panels = $extras->findByType('panel');
        $panelsHead = $extras->findByType('panel_head');
        $this->templateData['jumbotron']['header'] = $extra->getHeader();
        $this->templateData['jumbotron']['content'] = $extra->getContent();

        $this->templateData['panels'] = $panels;
        $this->templateData['panels_head'] = $panelsHead;

        return $this->render('default/index.html.twig', $this->templateData);
    }
}
