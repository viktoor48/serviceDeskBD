<?php

namespace App\Controller;

use App\Entity\Worker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteEmployerController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/delete/employer', name: 'app_delete_employer', methods: ['POST'])]
    public function deleteEmployer(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $workerId = $data['worker_id'] ?? null;
            $currentUserId = $data['current_user_id'] ?? null;

            if (!$workerId || !$currentUserId) {
                return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Проверить, что текущий пользователь не удаляет сам себя
            if ($currentUserId === $workerId) {
                return new JsonResponse(['error' => 'You cannot delete yourself'], JsonResponse::HTTP_FORBIDDEN);
            }

            // Найти работника, которого нужно удалить
            $worker = $this->entityManager->getRepository(Worker::class)->find($workerId);
            if (!$worker) {
                return new JsonResponse(['error' => 'Worker not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            // Удалить работника
            $this->entityManager->remove($worker);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Worker deleted successfully'], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
