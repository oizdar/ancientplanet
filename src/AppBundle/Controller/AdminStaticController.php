<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminStaticController extends Controller
{
    private $templateData = [];

    /**
     * @Route("/admin/extras/", name="admin_extras")
     */
    public function extrasAction()
    {
            $extras = $this->getDoctrine()->getRepository('AppBundle:Extras');
            $this->templateData['extras'] = $extras->findAll();
            return $this->render('admin/extras.html.twig', $this->templateData);
    }

    /**
     * @Route("/admin/extras/{id}", name="admin_extras_edit")
     */
    public function extrasEditAction(int $id, Request $request)
    {
        $extras = $this->getDoctrine()->getRepository('AppBundle:Extras');
        $extra = $extras->find($id);
        switch ($extra->getType()) {
            case 'jumbotron':
            case 'panel_head':
                $this->templateData['disabled']['header'] = false;
                $this->templateData['disabled']['footer'] = true;
                break;
            case 'panel':
                $this->templateData['disabled']['header'] = true;
                $this->templateData['disabled']['footer'] = true;
                break;
            default:
                $this->templateData['disabled']['header'] = false;
                $this->templateData['disabled']['footer'] = false;
                break;

        }
        $form = $this->createFormBuilder($extra)
            ->add('id', HiddenType::class)
            ->add('header', TextType::class)
            ->add('content', CKEditorType::class, ['config_name' => 'main_toolbar'])
            ->add('footer', TextType::class)
            ->add('type', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Save changes',
                'attr' => ['class' => 'btn btn-warning']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();
                $this->templateData['success'] = 'Element updated';
            } else {
                $this->templateData['error'] = true;
            }
        }

        $this->templateData['form'] = $form->createView();

        return $this->render('admin/extras_edit.html.twig', $this->templateData);
    }
}
