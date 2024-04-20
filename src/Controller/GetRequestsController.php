<?php

namespace App\Controller;

use App\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetRequestsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/get/requests', name: 'app_get_requests')]
    public function index(): Response
    {
        // Получаем все заявки
        $requests = $this->entityManager->getRepository(Request::class)->findAll();

        // Преобразуем результат в формат JSON
        $responseData = [];
        foreach ($requests as $request) {
            $responseData[] = [
                'id' => $request->getId(),
                'timeStart' => $request->getTimeStart(),
                'timeClose' => $request->getTimeClose(),
                'description' => $request->getDecription(),
                'status' => $request->getStatus(),
                'cabinet' => $request->getCabinet(),
                'numberBuilding' => $request->getNumberBuilding(),
                'teacher' => [
                    'id' => $request->getTeacher()->getId(),
                    'name' => $request->getTeacher()->getName(),
                    'lastName' => $request->getTeacher()->getLastName(),
                    'patronymic' => $request->getTeacher()->getPatronymic(),
                    'login' => $request->getTeacher()->getLogin(),
                ],
                'typeRequest' => [
                    'id' => $request->getTypeRequest()->getId(),
                    'name' => $request->getTypeRequest()->getName(),
                    'description' => $request->getTypeRequest()->getDescription(),
                ],
                'devices' => $this->getDevices($request),
                'workers' => $this->getWorkers($request),
            ];
        }

        return new JsonResponse($responseData);
    }

    private function getDevices(Request $request): array
    {
        $devices = [];
        foreach ($request->getDevice() as $device) {
            $devices[] = [
                'id' => $device->getId(),
                // добавьте другие поля, если необходимо
            ];
        }
        return $devices;
    }

    private function getWorkers(Request $request): array
    {
        $workers = [];
        foreach ($request->getWorkers() as $worker) {
            $workers[] = [
                'id' => $worker->getId(),
                // добавьте другие поля, если необходимо
            ];
        }
        return $workers;
    }
}
