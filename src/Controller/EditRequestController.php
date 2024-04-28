<?php

namespace App\Controller;

use App\Entity\Request;
use App\Entity\Worker;
use App\Entity\TypeRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class EditRequestController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/edit/request/{id}', name: 'edit_request', methods: ['PUT'])]
    public function editRequest(HttpRequest $request, Request $requestEntity): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Обработка данных запроса
        $requestEntity->setStatus($requestData['status'] ?? null);
        $requestEntity->setDecription($requestData['description'] ?? null);
        $requestEntity->setCabinet($requestData['cabinet'] ?? null); // Обновляем номер кабинета
        $requestEntity->setNumberBuilding($requestData['numberBuilding'] ?? null); // Обновляем номер корпуса

        // Обработка типа заявки
        $typeRequestName = $requestData['typeRequest']['name'] ?? null;
        if ($typeRequestName) {
            $typeRequest = $this->entityManager->getRepository(TypeRequest::class)->findOneBy(['name' => $typeRequestName]);
            if (!$typeRequest) {
                $typeRequest = new TypeRequest();
                $typeRequest->setName($typeRequestName);
                $this->entityManager->persist($typeRequest);
            }
            $requestEntity->setTypeRequest($typeRequest);
        }

        // Удаление всех текущих работников из заявки
        foreach ($requestEntity->getWorkers() as $worker) {
            $requestEntity->removeWorker($worker);
        }

        // Обработка сотрудников
        $workersData = $requestData['workers'] ?? [];
        foreach ($workersData as $workerData) {
            $workerId = $workerData['id'] ?? null;
            if ($workerId) {
                $worker = $this->entityManager->getRepository(Worker::class)->find($workerId);
                if ($worker) {
                    $requestEntity->addWorker($worker);
                }
            }
        }

        // Сохранение изменений
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Request updated successfully'], JsonResponse::HTTP_OK);
    }
}
