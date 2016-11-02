<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \AppBundle\Entity\ExtrasRepository;
use \AppBundle\Entity\PagesRepository;

class BaseController extends Controller
{
    /**
     * Data for the template
     * @var array
     */
    protected $templateData = [];

    /**
     * @var \AppBundle\Entity\ExtrasRepository
     */
    protected $extras;

    /**
     * @var \AppBundle\Entity\PagesRepository
     */
    protected $pagesRespository;


    protected function setBasicData()
    {
        $this->extrasEntity = $this->getDoctrine()->getRepository('AppBundle:Extras');
        $this->pagesEntity = $this->getDoctrine()->getRepository('AppBundle:Pages');
        $this->setPanels();
        $this->setMenu();
    }

    /**
     * Updates templateData with setted panels
     * @param AppBundle\Entity\Extras $extras
     */
    private function setPanels() : void
    {
        $panels = $this->extrasEntity->findByType('panel');
        $panelsHead = $this->extrasEntity->findByType('panel_head');

        $this->templateData['panels'] = $panels;
        $this->templateData['panels_head'] = $panelsHead;
    }

    /**
     * Update templateData with pages
     * @param AppBundle\Entity\Pages $pagesEntity
     */
    private function setMenu()
    {
        $pages = $this->pagesEntity->findTopPages();
        $menu = $this->generateMenu($pages);
        $this->templateData['menu'] = $menu;
    }

    private function generateMenu(array $pages)
    {
        $menu = [];
        foreach ($pages as $page) {
            $link['menuTitle'] = $page['menuTitle'];
            $link['id'] = $page['id'];
            $submenu = $this->pagesEntity->findSubPages($page['id']);
            if (!empty($submenu)) {
                $link['submenu'] = $this->generateMenu($submenu);
            } else {
                $link['submenu'] = null;
            }
            $menu[$page['id']] = $link;
        }
        return $menu;
    }
}
