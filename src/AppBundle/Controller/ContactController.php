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
            ->add('title', TextType::class/*, ['required' => false]*/)
            ->add('content', TextareaType::class)
            ->add('name', TextType::class/*, ['required' => false]*/)
            ->add('email', TextType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Send message',
                'attr' => ['class' => 'btn btn-warning']
            ]);
        $form = $form->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                var_dump($data);
                $this->sendEmail($data);
            }
        }
        $this->templateData['form'] = $form->createView();

        return $this->render('default/contact.html.twig', $this->templateData);
    }

    private function sendEmail(array $data)
    {
        $message = \Swift_Message::newInstance()
        ->setSubject($data['title'])
        ->setFrom($data['email'])
        ->setTo('malinowski.rad@gmail.com')
        ->setBody(
            $this->renderView(
                'emails/send.html.twig',
                [
                    'content' => $data['content'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]
            ),
            'text/html'
        );
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'Emails/registration.txt.twig',
                array('name' => $name)
            ),
            'text/plain'
        )
        */
        var_dump($this->get('mailer')->send($message));
    }
}
