<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Teacher;
use App\Entity\Worker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorizationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/authorization', name: 'app_authorization', methods: ['POST'])]
    public function authorize(Request $request): Response
    {
        // Получаем данные из запроса
        $data = json_decode($request->getContent(), true);

        // Ищем пользователя по логину и паролю
        $userRepository = $this->entityManager->getRepository(Worker::class);
        $teacherRepository = $this->entityManager->getRepository(Teacher::class);

        // Проверяем сначала среди преподавателей
        $teacher = $teacherRepository->findOneBy(['login' => $data['login'], 'password' => $data['password']]);
        if ($teacher instanceof Teacher) {
            // Если найден преподаватель, формируем ответ
            return new JsonResponse([
                'user' => [
                    'id' => $teacher->getId(),
                    'login' => $teacher->getLogin(),
                    'name' => $teacher->getName(),
                    'lastName' => $teacher->getLastName(),
                    'patronymic' => $teacher->getPatronymic(),
                    'isTeacher' => true,
                ]
            ]);
        }

        // Если преподаватель не найден, ищем среди работников
        $worker = $userRepository->findOneBy(['login' => $data['login'], 'password' => $data['password']]);
        if ($worker instanceof Worker) {
            // Если найден работник, формируем ответ
            $roles = $worker->getRole()->map(function ($role) {
                return $role->getName();
            })->toArray();

            return new JsonResponse([
                'user' => [
                    'id' => $worker->getId(),
                    'login' => $worker->getLogin(),
                    'name' => $worker->getName(),
                    'lastName' => $worker->getLastName(),
                    'patronymic' => $worker->getPatronymic(),
                    'roles' => $roles,
                ]
            ]);
        }

        // Если пользователь не найден, возвращаем ошибку
        return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
}
