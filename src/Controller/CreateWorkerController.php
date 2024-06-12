<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Worker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateWorkerController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/create/worker', name: 'app_create_worker', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Проверяем, существует ли работник с таким логином
        $existingWorker = $this->entityManager->getRepository(Worker::class)->findOneBy(['login' => $data['login']]);
        if ($existingWorker) {
            return new JsonResponse(['error' => 'Worker with this login already exists'], Response::HTTP_CONFLICT);
        }

        $worker = new Worker();
        $worker->setLogin($data['login'] ?? null);
        $worker->setPassword($data['password'] ?? null);
        $worker->setName($data['name'] ?? null);
        $worker->setLastName($data['lastName'] ?? null);
        $worker->setPatronymic($data['patronymic'] ?? null);
        $worker->setPost($data['post'] ?? null);
        
        // Проверяем, передан ли параметр isAdminRole
        if (isset($data['isAdminRole']) && $data['isAdminRole'] === true) {
            $roleRepository = $this->entityManager->getRepository(Role::class);
            $role = $roleRepository->findOneBy(['name' => 'Админ']);
            if (!$role) {
                return new JsonResponse(['error' => 'Admin role not found'], Response::HTTP_NOT_FOUND);
            }
            $worker->addRole($role);
        }

        $this->entityManager->persist($worker);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Worker created successfully'], Response::HTTP_CREATED);
    }
}
