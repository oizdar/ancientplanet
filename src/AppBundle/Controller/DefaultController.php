<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    private $templateData =[];
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $extras = $this->getDoctrine()->getRepository('AppBundle:Extras');

        $this->setJumbotron($extras);
        $this->setPanels($extras);

        $pagesEntity = $this->getDoctrine()->getRepository('AppBundle:Pages');
        $this->setMenu($pagesEntity);

        return $this->render('default/index.html.twig', $this->templateData);
    }

    /**
     * @Route("/page/{id}", name="page")
     */
    public function pageAction(int $id)
    {
        $extras = $this->getDoctrine()->getRepository('AppBundle:Extras');

        $this->setPanels($extras);

        $pagesEntity = $this->getDoctrine()->getRepository('AppBundle:Pages');
        $this->setMenu($pagesEntity);

        $this->templateData['page'] = $pagesEntity->findById($id)[0];
        return $this->render('default/page.html.twig', $this->templateData);
    }

    /**
     * Sets templateData['jumbotron']
     * @param AppBundle\Entity\Extras $extras
     */
    private function setJumbotron(\AppBundle\Entity\ExtrasRepository $extras) : void
    {
        $extra = $extras->findOneByType('jumbotron');

        $this->templateData['jumbotron']['header'] = $extra->getHeader();
        $this->templateData['jumbotron']['content'] = $extra->getContent();
    }

    /**
     * Updates templateData with setted panels
     * @param AppBundle\Entity\Extras $extras
     */
    private function setPanels(\AppBundle\Entity\ExtrasRepository $extras) : void
    {
        $panels = $extras->findByType('panel');
        $panelsHead = $extras->findByType('panel_head');

        $this->templateData['panels'] = $panels;
        $this->templateData['panels_head'] = $panelsHead;
    }

    /**
     * Update templateData with pages
     * @param AppBundle\Entity\Pages $pagesEntity
     */
    private function setMenu(\AppBundle\Entity\PagesRepository $pagesEntity)
    {
        $pages = $pagesEntity->findTopPages();
        $menu = $this->generateMenu($pages, $pagesEntity);
        $this->templateData['menu'] = $menu;
    }

    private function generateMenu(array $pages, $pagesEntity)
    {
        $menu = [];
        foreach ($pages as $page) {
            $link['menuTitle'] = $page['menuTitle'];
            $link['id'] = $page['id'];
            $submenu = $pagesEntity->findSubPages($page['id']);
            if (!empty($submenu)) {
                $link['submenu'] = $this->generateMenu($submenu, $pagesEntity);
            } else {
                $link['submenu'] = null;
            }
            $menu[] = $link;
        }
        return $menu;
    }
}
