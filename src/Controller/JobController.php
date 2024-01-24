<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    #[Route('/', name: 'app_job',requirements: ['id' => '\d+'])]
    public function index(OffreRepository $offreRepository,Request $request): Response
    {

        // On va chercher le numéro de page dans l'URL
        $page = $request->query->getInt('page',1);

        $offre = $offreRepository->getOffresPaginator($page,4);

        
        
        
        return $this->render('job/index.html.twig', [
            'offres' => $offre
        ]);
    }

    #[Route('/{id}', name: 'app_job_show',requirements: ['id' => '\d+'])]
    public function show(Offre $offre): Response
    {
        
        return $this->render('job/show.html.twig', [
            'offre' => $offre
        ]);
    }

    #[Route('/new', name: 'app_job_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {

      $offre = new Offre();

      $form = $this->createForm(OffreType::class, $offre);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid())
      {
        
        $em->persist($offre);
        $em->flush();

        return $this->redirectToRoute('app_job_show', [
            'id' => $offre->getId(),
        ]);
      }

    return $this->render('job/new.html.twig', [
        'form' => $form
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_job_edit',requirements: ['id' => '\d+'])]
    public function editJob(Request $request, Offre $offre, EntityManagerInterface $em): Response
    {

      $form = $this->createForm(OffreType::class, $offre);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid())
      {
      
        //$em->persist($offre);
        $em->flush();

        $this->addFlash(
            'sucess',
            'Vous avez modifier le job'
        );

        return $this->redirectToRoute('app_job_show', [
            'id' => $offre->getId(),
        ]);

        }

    return $this->render('job/edit.html.twig', [
        'form' => $form
        ]);
    
    }

    #[Route('/{id}/supprimer', name: 'app_job_delete', requirements: ['id' => '\d+'])]
    public function deleteJob(Offre $offre, EntityManagerInterface $em): RedirectResponse
    {
        // Vous pouvez ajouter des vérifications supplémentaires ici, par exemple, si l'utilisateur a les autorisations nécessaires.

        $em->remove($offre);
        $em->flush();

        $this->addFlash('success', 'L\'offre a été supprimée avec succès.');

        return $this->redirectToRoute('app_job');
    }
}
