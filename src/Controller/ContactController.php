<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Person;
use App\Form\ContactType;
use Symfony\Component\Form\Extension\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LengthValidator;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactController extends AbstractController
{

    /**
     * @Route("/contact", name="person_list", methods={"GET"})
     */

    public function index()
    {
        $persons = $this->getDoctrine()->getRepository(Person::class)->findAll();

        return $this->render('contact/contact.html.twig', array('persons' => $persons));
    }

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
     * @Route("/contact/new", name="new_person", methods={"GET", "POST"})
     */

    public function new(Request $request)
    {
        $person = new Person();

        $form = $this->createFormBuilder($person)
            ->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
            ->add('mobileNumber', TelType::class, array('attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $person = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('person_list');
        }

        return $this->render('contact/new.html.twig', array('our_form' => $form->createView()));
    }
}
