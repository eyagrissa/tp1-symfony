<?php

namespace App\Controller;

use App\Entity\Tache;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TacheRepository;

class TacheController extends AbstractController
{
    #[Route('/taches', name: 'app_taches')]
    public function index(TacheRepository $tacheRepository): Response
    {
        // Get all tasks, sorted by non-finished first
        $taches = $tacheRepository->findBy([], ['terminee' => 'ASC', 'dateCreation' => 'DESC']);

        return $this->render('tache/index.html.twig', [
            'taches' => $taches,
        ]);
    }

    #[Route('/taches/ajouter', name: 'app_tache_ajouter')]
    public function ajouter(EntityManagerInterface $em): Response
    {
        $tache = new Tache();
        $tache->setTitre('Nouvelle tâche');
        $tache->setDescription('Description de la tâche en dur');
        $tache->setDateCreation(new \DateTime());
        $tache->setTerminee(false);

        $em->persist($tache);
        $em->flush();

        return new Response('Tâche créée avec ID : '.$tache->getId());
    }

    #[Route('/taches/{id}', name: 'app_tache_detail', requirements: ['id' => '\d+'])]
    public function detail(Tache $tache): Response
    {
        return $this->render('tache/detail.html.twig', [
            'tache' => $tache,
        ]);
    }

    // ✅ Bonus route to mark task as done
    #[Route('/taches/{id}/terminer', name: 'app_tache_terminer', requirements: ['id' => '\d+'])]
    public function terminer(Tache $tache, EntityManagerInterface $em): Response
    {
        $tache->setTerminee(true);
        $em->flush();

        return $this->redirectToRoute('app_taches');
    }
}