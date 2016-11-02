<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    protected $templateData =[];
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $this->setBasicData();
        $this->setJumbotron();

        return $this->render('default/index.html.twig', $this->templateData);
    }

    /**
     * @Route("/page/{id}", name="page")
     */
    public function pageAction(int $id)
    {
        $this->setBasicData();

        $this->templateData['page'] = $this->pagesEntity->findById($id)[0];
        return $this->render('default/page.html.twig', $this->templateData);
    }

    /**
     * Sets templateData['jumbotron']
     * @param AppBundle\Entity\Extras $extras
     */
    private function setJumbotron() : void
    {
        $extra = $this->extrasEntity->findOneByType('jumbotron');

        $this->templateData['jumbotron']['header'] = $extra->getHeader();
        $this->templateData['jumbotron']['content'] = $extra->getContent();
    }
}
