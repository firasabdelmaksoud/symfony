<?php

namespace TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TestBundle\Entity\car;
use TestBundle\Form\carType;
/* for ajax */
use Symfony\Component\HttpFoundation\Response;

class CarController extends Controller
{
    public function showAction(){


        $em= $this->getDoctrine()->getManager();
        $car =$em->getRepository('TestBundle:car')->findAll();
        return $this->render('@Test/Default/showcar.html.twig',array(
            'car'=> $car));
    }




    public function showbyIdAction($id){
        $em= $this->getDoctrine()->getManager();
        $car =$em->getRepository('TestBundle:car')->find($id);
        return $this->render('@Test/Default/showcarbyid.html.twig',array(
            'car'=> $car));
    }


    public function addAction(Request $request)
    {
        $car = new car();
        $form = $this->createForm(carType::class, $car);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();
            $this->addFlash('info', 'Created Successfully !');
            return $this->redirectToRoute('test_car_show');
        }


        return $this->render('@Test/Default/add.html.twig',array(
            'Form'=> $form->createView()));

    }


    public function editAction(Request $request,$id)
    {

        $em=$this->getDoctrine()->getManager();
        $car= $em->getRepository('TestBundle:car')->find($id);
        $form=$this->createForm(carType::class,$car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();
            $this->addFlash('info', 'Created Successfully !');
            return $this->redirectToRoute('test_car_show');
        }


        return $this->render('@Test/Default/edit.html.twig',array(
            'Form'=> $form->createView()));




    }

    public function deleteAction($qdt)
    {

        $em= $this->getDoctrine()->getManager();
        $car =$em->getRepository('TestBundle:car')->find($qdt);
        $em->remove($car);
        $em->flush();
        return $this->redirectToRoute('test_car_show');


    }



    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $car =  $em->getRepository('TestBundle:car')->find($requestString);
        if(!$car) {
            $result['car']['error'] = "Post Not found :( ";
        } else {
            $result['id'] = $car->getId();
            $result['nom'] = $car->getnom();
            $result['marque'] = $car->getmarque();
            $result['age'] = $car->getage();

        }
        return new Response(json_encode($result));
    }

}
