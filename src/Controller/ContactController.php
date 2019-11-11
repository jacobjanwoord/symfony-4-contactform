<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;
use App\Form\ContactType;
use Symfony\Component\Form\Extension\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    //function to show all persons from the database onto the contact page
    /**
     * @Route("/contact", name="person_list", methods={"GET"})
     */
    public function index()
    {
        $persons = $this->getDoctrine()->getRepository(Person::class)->findAll();

        return $this->render('contact/contact.html.twig', array('persons' => $persons));
    }

    //function to add a new person into the database
    /**
     * @Route("/contact/new", name="new_person", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        $person = new Person();

        //form builder
        $form = $this->createFormBuilder($person)
            ->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
            ->add('mobileNumber', TelType::class, array('attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();


        $form->handleRequest($request);

            // adding input to database
            if($form->isSubmitted() && $form->isValid()) {
                    $person = $form->getData();

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($person);
                    $entityManager->flush();

                    return $this->redirectToRoute('person_list');
            }else {
                return $this->render('contact/new.html.twig', array('our_form' => $form->createView()));
            }
    }

}
