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

    // Ce route gère l'affichage des Offres et le système de pagination
    #[Route('/', name: 'app_job',requirements: ['id' => '\d+'])]
    // En paramètre on lui passent l'objet OffreRepository pour avoir accès à la méthode getOffrePaginator, et l'object Request pour récupérer des données dans l'URL
    public function index(OffreRepository $offreRepository,Request $request): Response
    {

        // Récupération du numéro de la page depuis l'URL que l'on stocke dans la variable $page.
        $page = $request->query->getInt('page',1);
        // Instanciation du Repository pour récuperer la méthode getOffresPaginator que l'on stocke dans la variable $offre. On page en paramètre les 2 arguments demandés.
        $offre = $offreRepository->getOffresPaginator($page,4);
        // A l'aide de la méthode render mis à disposition via l'AbstractController on renvoie à la vue (Twig) les offres, et la pagination. On lui passe, la route du fichier twig en 1er arguments, et en second arguments la variable $offre qui contient l'intégralités des offres présente en BDD.
        return $this->render('job/index.html.twig', [
            'offres' => $offre
        ]);
    }
    // Cette route gère l'affichage d'une seul offre. De ce faite on lui passe l'argument {id} de l'offre ciblée. 
    #[Route('/{id}', name: 'app_job_show',requirements: ['id' => '\d+'])]
    // En paramètre on lui passent l'entité Offre pour accéder aux offres
    public function show(Offre $offre): Response
    {
        // On envoie à la vue le template et l'offre en argument de la méthode render
        return $this->render('job/show.html.twig', [
            'offre' => $offre
        ]);
    }

    // Cette route gère l'ajout d'une offre
    #[Route('/new', name: 'app_job_new')]
    // En paramètre, on à besoin de l'object Request pour récupérer les données d'un formulaire et EntityManagerInterface pour envoyées les données en BDD
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        // On instancie l'object offre comme on souhaite en créeer une nouvelle
        $offre = new Offre();
        // On fait appel à la méthode createForm pour récuperer le formulaire créer en amont (-> App\Form\OffreType.php). 
        // On lui passe l'entités Offre et la variable de l'instance de l'entité Offre
        $form = $this->createForm(OffreType::class, $offre);
        // On récupére les données du formulaire
        $form->handleRequest($request);
        // Condition, si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid())
        {
        // Dans ce cas l'object EntityManagerInterface se charge d'envoyé les données de l'ajout d'une offre en base de données
            $em->persist($offre);
            $em->flush();

        // Suite à la création depuis le formulaire, on effectue une redirection vers la route précédent pour afficher une seul offre.
        // On envoie également l'id de la nouvelle offre car la route précédent demande en argument un id
        // Route de redirection : "#[Route('/{id}', name: 'app_job_show',requirements: ['id' => '\d+'])]"
            return $this->redirectToRoute('app_job_show', [
                'id' => $offre->getId(),
            ]);
    }
    // On envoie à la vue le formulaire et la variable $offre qui contient la gestion du formaulaire vu au dessus.
    return $this->render('job/new.html.twig', [
        'form' => $form
        ]);
    }
    // Cette route affiche un modification d'une offre
    #[Route('/{id}/modifier', name: 'app_job_edit',requirements: ['id' => '\d+'])]
    // En paramètre, les différents objects nécessaire à la méthode.
    public function editJob(Request $request, Offre $offre, EntityManagerInterface $em): Response
    {
    //Pour la modification c'est le même principe que la création
    // Sauf que l'on à pas besoin d'instancier l'entité Offre puisqu'on ne créer pas, mais on modifie uniquement les données déjà présente.
    $form = $this->createForm(OffreType::class, $offre);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
    
        //$em->persist($offre);
        $em->flush();
        // Ajout d'une petit message avec la méthode addFlash de l'abstractController.
        // On lui passe en argument la clé et le message que l'on souhaite affiché
        $this->addFlash(
            'sucess',
            'Vous avez modifier le job'
        );
        // Redirection après sauvegarde des modifications
        return $this->redirectToRoute('app_job_show', [
            'id' => $offre->getId(),
        ]);

        }
        // On donne à la vue le formulaire avec ses données
    return $this->render('job/edit.html.twig', [
        'form' => $form
        ]);
    
    }
    // Cette route supprime une offre
    #[Route('/{id}/supprimer', name: 'app_job_delete', requirements: ['id' => '\d+'])]
    // En paramètre, les différents objects nécessaire à la méthode.
    public function deleteJob(Offre $offre, EntityManagerInterface $em): RedirectResponse
    {
    
        //On utilise la méthode remove fourni par l'EntityManagerInterface. 
        // Puis la méthode flush pour procéder à la suppression de l'offre en BDD
        $em->remove($offre);
        $em->flush();

        // Ajout d'une petit message avec la méthode addFlash de l'abstractController.
        // On lui passe en argument la clé et le message que l'on souhaite affiché
        $this->addFlash('success', 'L\'offre a été supprimée avec succès.');
        // Une fois la suppression effectuée, on redirige vers la route qui affiche toutes les offres
        return $this->redirectToRoute('app_job');
    }
}
