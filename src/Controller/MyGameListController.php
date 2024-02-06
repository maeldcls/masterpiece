<?php

namespace App\Controller;

use App\Entity\GameUser;
use App\Repository\GameUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyGameListController extends AbstractController
{
    #[Route('/my/game/list', name: 'app_my_game_list')]
    public function index(GameUserRepository $gameUserRepository, EntityManagerInterface $entityManager): Response
    {
        $games = [];
        $gameUser = [];
        /** @var \App\Entity\User $user */
        $result = $gameUserRepository->showMyGames($this->getUser()->getId());

        dump($result);
        

        return $this->render('my_game_list/index.html.twig', [
            'controller_name' => 'MyGameListController',
            'games' => $result,
        ]);
    }

    #[Route('/remove/{gameUserId}', name: 'app_remove')]
    public function deleteGameUserAction(EntityManagerInterface $entityManager, int $gameUserId)
    {
        $repository = $entityManager->getRepository(GameUser::class);
        $gameUser = $repository->find($gameUserId);

        if ($gameUser) {
            $entityManager->remove($gameUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_my_game_list');
        } else {
            // si entiy GameUser pas trouvé
            return $this->redirectToRoute('app_my_game_list');
        }
    }

    #[Route('/fav/{gameUserId}', name: 'app_fav')]
    public function changeFav(EntityManagerInterface $entityManager, int $gameUserId)
    {
        $repository = $entityManager->getRepository(GameUser::class);
        $gameUser = $repository->find($gameUserId);

        if ($gameUser) {
            $gameUser->toggleIsFav();
            $entityManager->flush();

            return $this->redirectToRoute('app_my_game_list');
        } else {
            // si entiy GameUser pas trouvé
            return $this->redirectToRoute('app_my_game_list');
        }
    }
}
