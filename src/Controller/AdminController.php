<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\RechercheVoiture;
use App\Entity\Voiture;
use App\Form\RechercheVoitureTyoeType;
use App\Form\VoitureType;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(VoitureRepository $repo, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $rechercheVoiture = new RechercheVoiture();
        $form = $this-> createForm(RechercheVoitureTyoeType::class,$rechercheVoiture);
        $form->handleRequest($request);


        $voitures= $paginatorInterface->paginate(
            $repo->findAllWithPagination($rechercheVoiture),
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );
        return $this->render('voiture/voitures.html.twig',[
            "voitures" =>$voitures,
            "form" => $form->createView(),
            "admin" => true
        ]);  
    }

     /**
     * @Route("/admin/creation", name="creationVoiture")
     * @Route("/admin/{id}", name="modifVoiture", methods="GET|POST")
     */
    public function modification(Voiture $voiture = null, Request $request, EntityManagerInterface $em): Response
    {
        if(!$voiture){
            $voiture= new Voiture();
        }
        $form= $this->createForm(VoitureType::class,$voiture);
        $form->handleRequest($request);
        
        if($form->isSubmitted()&& $form->isValid()){
            $em->persist($voiture);
            $em->flush();
            $this->addFlash('success', "L'action a été effectueé");
            return $this->redirectToRoute("admin");
        }
        return $this->render('admin/modification.html.twig',[
            "voiture" =>$voiture,
            "form" => $form->createView(),
        ]); 
    }

     /**
     * @Route("/admin/{id}", name="supVoiture", methods="SUP")
     */
    public function suppression(Voiture $voiture, Request $request, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid("SUP".$voiture->getId(), $request->get("_token"))){
            $em->remove($voiture);
            $em->flush();
            $this->addFlash('success', "L'action a été effectueé");
            return $this->redirectToRoute("admin");
        }
    }

}
