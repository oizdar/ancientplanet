<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Pages;

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
     * @Route("/admin/add/page/", name="admin_add_page")
     */
    public function pageAddAction(Request $request)
    {
        $page = new Pages();
        $form = $this->generateForm($page, 'Add Page');

        $added = $this->formHandleRequest($request, $form);
        if (isset($added)) {
            $this->templateData['success'] = 'New page added';
            return $this->redirectToRoute('admin_pages_edit', ['id' => $added]);
        }
        $this->templateData['addPage'] = true;
        $this->templateData['form'] = $form->createView();
        return $this->render('admin/pages_edit.html.twig', $this->templateData);
    }

    /**
     * Creates form to adding pages
     * @param  string $label Button Label
     * @return $form
     */
    private function generateForm($page, string $label = 'Save')
    {
        $form = $this->createFormBuilder($page)
            ->add('menuTitle', TextType::class)
            ->add('title', TextType::class, ['required' => false])
            ->add('content', CKEditorType::class, ['config_name' => 'full_toolbar'])
            ->add('parent', TextType::class, ['required' => false])
            ->add('submit', SubmitType::class, [
                'label' => $label,
                'attr' => ['class' => 'btn btn-warning']
            ]);

        return $form->getForm();
    }

    private function formHandleRequest(Request $request, \Symfony\Component\Form\Form $form)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            return $data->getId();
        }
    }

    /**
     * @Route("/admin/pages/{id}", name="admin_pages_edit")
     */
    public function pagesEditAction(int $id, Request $request)
    {
        $pages = $this->getDoctrine()->getRepository('AppBundle:Pages');
        $page = $pages->find($id);
        $form = $this->generateForm($page, 'Save Changes');

        $updated = $this->formHandleRequest($request, $form);
        if (isset($updated)) {
            $this->templateData['success'] = 'Page data updated';
        }

        $this->templateData['form'] = $form->createView();

        return $this->render('admin/pages_edit.html.twig', $this->templateData);
    }

    /**
     * @Route("/admin/pages/delete/{id}", name="admin_pages_delete")
     */
    public function pagesDeleteAction(int $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $page = $em->getRepository('AppBundle:Pages')->find($id);

        if (!$page) {
            throw $this->createNotFoundException('No Page found for id '.$id);
        }

        $em->remove($page);
        $em->flush();
        $success = ['success' => 'Page deleted'];
        return $this->forward('AppBundle:AdminPage:pages');
    }
}
