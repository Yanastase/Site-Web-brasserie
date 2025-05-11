<?php

namespace App\Controller;

use App\Repository\BoissonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class BoissonController extends AbstractController
{
    #[Route('/boissonList', name: 'app_boisson')]
    public function index(BoissonRepository $boissonRepository): JsonResponse
    {
        $boissons = $boissonRepository->findAll();

        // Transform the entities into an array
        $data = array_map(fn($boisson) => [
            'id' => $boisson->getId(),
            'nom' => $boisson->getNom(),
            'logo' => $boisson->getLogo(),
            'prix' => $boisson->getPrix(),
            'dateProduction' => $boisson->getDateProduction(),
                   ], $boissons);

        return $this->json($data);
    }
    #[Route('/boissonList/{id}', name: 'delete_boisson', methods: ['DELETE'])]
    public function delete(int $id, BoissonRepository $boissonRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $boisson = $boissonRepository->find($id);

        if (!$boisson) {
            return $this->json(['error' => 'Boisson not found'], 404);
        }

        $entityManager->remove($boisson);
        $entityManager->flush();

        return $this->json(['message' => 'Boisson deleted successfully']);
    }
}

