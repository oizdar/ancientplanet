<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\{TextType, TextareaType, SubmitType };

class ContactController extends BaseController
{

    /**
     * @Route("/contact/", name="contact")
     */
    public function indexAction(Request $request)
    {
        $this->setBasicData();

        $form = $this->createFormBuilder()
            ->add('title', TextType::class, ['required' => false])
            ->add('content', TextareaType::class)
            ->add('name', TextType::class, ['required' => false])
            ->add('email', TextType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Send message',
                'attr' => ['class' => 'btn btn-warning']
            ]);
        $form = $form->getForm();

        $this->templateData['form'] = $form->createView();

        return $this->render('default/contact.html.twig', $this->templateData);
    }
}
