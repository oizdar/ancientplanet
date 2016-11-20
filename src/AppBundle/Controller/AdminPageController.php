<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Pages;

use Symfony\Component\Form\Extension\Core\Type\{TextType, HiddenType, ChoiceType, SubmitType };
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class AdminPageController extends Controller
{
    private $templateData = [];

    /**
     * @Route("/admin/pages/", name="admin_pages")
     */
    public function pagesAction()
    {
            return $this->forward('AppBundle:AdminPage:pagesOffset', ['offset' => 1]);
    }

    /**
     * @Route("/admin/pages/{offset}", name="admin_pages_offset")
     */
    public function pagesOffsetAction(Request $request, int $offset)
    {
        $this->templateData['deleted'] = $request->get('deleted');

        $pages = $this->getDoctrine()->getRepository('AppBundle:Pages');
        $count = $pages->countAll();
        $this->templateData['pages'] = $pages->findBy([], [], 5, ($offset-1)*5);
        if ($count > 5) {
            $allPages = ceil($count/5);
            $this->templateData['pagination'] = [
                'pages' => $allPages,
                'page' => $offset,
                'previous' => ($offset > 1) ? $offset-1 : false,
                'next' => ($offset < $allPages) ? $offset+1 : false,
            ];
        }
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
            return $this->redirectToRoute('admin_page_edit', ['id' => $added, 'added' => true]);
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
    private function generateForm(Pages $page, string $label = 'Save', int $id = null)
    {
        $pages = $this->getDoctrine()->getRepository('AppBundle:Pages');
        $options = $pages->getPossibleParents($id);
        $choices['Leave and set as TOP'] = null;
        foreach ($options as $option) {
            $choices[$option->getMenuTitle()] = $option;
        }
        $form = $this->createFormBuilder($page)
            ->add('menuTitle', TextType::class)
            ->add('title', TextType::class, ['required' => false])
            ->add('content', CKEditorType::class, ['config_name' => 'full_toolbar'])
            ->add('parent', ChoiceType::class, [
                'choices' => $choices
            ])
            ->add('submit', SubmitType::class, [
                'label' => $label,
                'attr' => ['class' => 'btn btn-warning']
            ]);

        return $form->getForm();
    }

    private function formHandleRequest(Request $request, \Symfony\Component\Form\Form $form)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();
                return $data->getId();
            } else {
                $this->templateData['error'] = true;
            }
        }
    }

    /**
     * @Route("/admin/page/{id}", name="admin_page_edit")
     */
    public function pagesEditAction(int $id, Request $request)
    {
        $token = null;
        if ($request->get('added')) {
            $this->templateData['success'] = 'Page successfully added';
        }
        $pages = $this->getDoctrine()->getRepository('AppBundle:Pages');
        $page = $pages->find($id);
        $form = $this->generateForm($page, 'Save Changes', $id);

        $updated = $this->formHandleRequest($request, $form);
        if (isset($updated)) {
            $this->templateData['success'] = 'Page data updated';
        }

        $this->templateData['form'] = $form->createView();
        return $this->render('admin/pages_edit.html.twig', $this->templateData);
    }

    /**
     * @Route("/admin/page/delete/{id}", name="admin_page_delete")
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
        return $this->forward('AppBundle:AdminPage:pagesOffset', ['offset' => 1, 'deleted' => true]);
    }
}
