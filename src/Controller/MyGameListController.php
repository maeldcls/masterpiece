<?php

namespace App\Controller;

use App\Entity\Game;
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
        $result = $gameUserRepository->showMyGames($this->getUser()->getId());

        dump($result);
            // foreach ($result as $row) {
            //     $game = $row[1]->getGame();
            //     $games[] = $game;
            // }

        return $this->render('my_game_list/index.html.twig', [
            'controller_name' => 'MyGameListController',
            'games' => $result,
        ]);
    }
}
