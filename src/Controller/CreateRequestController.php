<?php

namespace App\Controller;

use App\Entity\Request;
use App\Entity\Teacher;
use App\Entity\TypeRequest;
use App\Repository\DeviceRepository;
use App\Repository\TypeRequestRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CreateRequestController extends AbstractController
{
    private TypeRequestRepository $typeRequestRepository;
    private DeviceRepository $deviceRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        TypeRequestRepository $typeRequestRepository,
        DeviceRepository $deviceRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->typeRequestRepository = $typeRequestRepository;
        $this->deviceRepository = $deviceRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/create/request', name: 'app_create_request')]
    public function createRequest(HttpRequest $request): Response
    {
        $requestData = json_decode($request->getContent(), true);

        if (!$requestData) {
            return new JsonResponse(['error' => 'Некорректные данные'], Response::HTTP_BAD_REQUEST);
        }

        $requestEntity = new Request();
        $requestEntity->setTimeStart((new DateTime())->format('Y-m-d H:i:s'));

        $typeRequestId = $requestData['type_request_id'];
        $typeRequest = $this->typeRequestRepository->find($typeRequestId);

        if (!$typeRequest) {
            return new JsonResponse(['error' => 'Тип заявки не найден'], Response::HTTP_NOT_FOUND);
        }

        $requestEntity->setTypeRequest($typeRequest);

        $teacherId = $requestData['teacher_id'];
        $teacher = $this->entityManager->getRepository(Teacher::class)->find($teacherId);

        if (!$teacher) {
            return new JsonResponse(['error' => 'Преподаватель не найден'], Response::HTTP_NOT_FOUND);
        }

        $requestEntity->setTeacher($teacher);

        $requestEntity->setStatus($requestData['status']);
        $requestEntity->setDecription($requestData['description']);
        $requestEntity->setCabinet($requestData['cabinet']);
        $requestEntity->setNumberBuilding($requestData['number_building']);

        if (isset($requestData['device_id'])) {
            $deviceId = $requestData['device_id'];
            $device = $this->deviceRepository->find($deviceId);
            if (!$device) {
                return new JsonResponse(['error' => 'Устройство не найдено'], Response::HTTP_NOT_FOUND);
            }
            $requestEntity->addDevice($device);
        }

        $this->entityManager->persist($requestEntity);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Заявка успешно создана'], Response::HTTP_CREATED);
    }
}
