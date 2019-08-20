<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class AdminPropertyController extends AbstractController {
    
    /**
     * @var PropertyRepository
     */
    public function __construct(PropertyRepository $repository, ObjectManager $em){
        $this->repository = $repository;
        $this->em = $em;
    }
    
    /** 
    * @Route("/admin", name="admin.property.index")
    * @return Response
    */
    public function index(): Response {
        // Gérer les biens
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', [
            'properties' => $properties
        ]);
    }

    /** 
    * @Route("/admin/property/create", name="admin.property.new")
    * @return Response
    */
    public function new(Request $request): Response {
        // Créer un bien
        $property = new Property();
        $form = $this->createForm(PropertyType::class,$property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success','Le bien créé avec succès.');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }


    /** 
    * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
    * @param Property $property
    * @return Response
    */
    public function edit(Property $property, Request $request): Response {
        // Editer le bien
        $form = $this->createForm(PropertyType::class,$property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success','Le bien a été modifié avec succès.');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /** 
    * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
    * @param Property $property
    * @return Response
    */
    public function delete(Property $property, Request $request): Response{
        
        if ($this->isCsrfTokenValid('delete'. $property->getId(), $request->get('_token'))) {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success','Le bien a bien été supprimé.');
            return $this->redirectToRoute('admin.property.index');
        }
    }
}