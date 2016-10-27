<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminPageController extends Controller
{
    private $templateData = [];

    /**
     * @Route("/admin/pages/", name="admin_pages")
     */
    public function pagesAction()
    {
            $pages = $this->getDoctrine()->getRepository('AppBundle:Pages');
            $this->templateData['pages'] = $pages->findAll();
            return $this->render('admin/pages.html.twig', $this->templateData);
    }

    /**
     * @Route("/admin/pages/{id}", name="admin_pages_edit")
     */
    public function pagesEditAction(int $id, Request $request)
    {
        $pages = $this->getDoctrine()->getRepository('AppBundle:Pages');
        $page = $pages->find($id);
        $form = $this->createFormBuilder($page)
            ->add('menuTitle', TextType::class)
            ->add('title', TextType::class, ['required' => false])
            ->add('content', CKEditorType::class, ['config_name' => 'full_toolbar'])
            ->add('parent', TextType::class, ['required' => false])
            ->add('submit', SubmitType::class, [
                'label' => 'Save changes',
                'attr' => ['class' => 'btn btn-warning']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
        }

        $this->templateData['form'] = $form->createView();

        return $this->render('admin/pages_edit.html.twig', $this->templateData);
    }
}
