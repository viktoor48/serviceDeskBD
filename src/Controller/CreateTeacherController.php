<?php

namespace App\Controller;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateTeacherController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/create/teacher', name: 'app_create_teacher', methods: ['POST'])]
    public function create(Request $request): Response
    {
        // Получаем данные из запроса
        $data = json_decode($request->getContent(), true);

        // Создаем нового преподавателя
        $teacher = new Teacher();
        $teacher->setPhone($data['phone']);
        $teacher->setName($data['name']);
        $teacher->setLastName($data['lastName']);
        $teacher->setPatronymic($data['patronymic']);
        $teacher->setLogin($data['login']);
        $teacher->setPassword($data['password']);
        $teacher->setDepartment($data['department']);
        $teacher->setFaculty($data['faculty']);

        // Сохраняем преподавателя в базе данных
        $this->entityManager->persist($teacher);
        $this->entityManager->flush();

        // Возвращаем успешный ответ
        return new JsonResponse(['message' => 'Teacher created successfully'], Response::HTTP_CREATED);
    }
}
