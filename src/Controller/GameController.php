<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use App\Entity\Tag;
use App\Form\SearchType;
use App\Service\ApiDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/', name: 'app_game')]
    public function index(HttpFoundationRequest $request,ApiDataService $apiDataService): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $searchWord = $form->get('searchText')->getData();
            $searchWordUpdated = strtr($searchWord, ' ', '-');
            
            return $this->redirectToRoute('app_search',['searchWord' => $searchWordUpdated]);
        }

        // Remplacez "YOUR_API_KEY" par votre clé API RAWG.io
        $apiKey = "85c1e762dda2428786a58b352a42ade2";
        $gameSlug = "the-witcher-3-wild-hunt"; // Remplacez par le slug du jeu que vous souhaitez rechercher

        $limit = 24; // Nombre de jeux à récupérer

        $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&ordering=-metacritic&page_size=$limit";
        $apiData = $apiDataService->fetchDataFromApi($apiUrl);
   
        
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $apiData,
            'formSearch'=>$form->createView(),
        ]);
    }
}
