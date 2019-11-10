<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Person;
use App\Form\ContactType;
use Symfony\Component\Form\Extension\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

//    /**
//     * @Route("/contact/save", name="contact-save")
//     */

//    public function save()
//    {
//        $entityManager = $this->getDoctrine()->getManager();
//
//        $person = new person();
//        $person->setName('Jacob Jan');
//        $person->setEmail('jacobjanwoord@gmail.com');
//        $person->setMobileNumber('0619995065');
//
//        $entityManager->persist($person);
//
//        $entityManager->flush();
//
//        return new Response('saved the person'.$person->getId());
//    }

    /**
     * @Route("/contact", name="new_person")
     * Method({"GET", "POST"})
     */

    public function new(Request $request)
    {
        $person = new Person();

        $form = $this->createFormBuilder($person)
            ->add('title', TextType::class,array('attr' ))

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $person = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('/contact');
        }

        return $this->render('./contact/contact.html.twig', [
            'our_form' => $form->createView()
        ]);
    }
}
