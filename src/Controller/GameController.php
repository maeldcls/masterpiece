<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\GameUser;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use App\Form\GameType;
use App\Form\SearchType;
use App\Repository\GameRepository;
use App\Service\ApiDataService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GameController extends AbstractController
{
    #[Route('/', name: 'app_game')]
    public function index(Request $request, ApiDataService $apiDataService): Response
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

        $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&ordering=-relevance&page_size=$limit";
        $apiData = $apiDataService->fetchDataFromApi($apiUrl);
   
        
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $apiData,
            'formSearch'=>$form->createView(),
   
        ]);
    }

    #[Route('/new/{id}', name: 'app_add_game', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $manager =$entityManager->getRepository(Game::class);

        $foundGame = $manager->findby(['gameId' => $id]);
        if(!$foundGame){
            $apiKey = "85c1e762dda2428786a58b352a42ade2";

            $apiUrl = "https://api.rawg.io/api/games/$id?key=$apiKey";
    
            $ch = curl_init($apiUrl);
            $response = curl_exec($ch);
             // Configuration des options cURL
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             // Ignorer la vérification SSL (À utiliser avec précaution !)
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
             // Exécution de la requête
             $response = curl_exec($ch);
     
             // Vérification des erreurs cURL
             if (curl_errno($ch)) {
                 die('Erreur cURL : ' . curl_error($ch));
             }
     
             // Fermeture de la session cURL
             curl_close($ch);
            $data = json_decode($response, true);

            $game = new Game();
            $game->setTitle($data['name']);
            $game->setBackgroundImage($data['background_image']);
            $game->setSummary($data['description_raw']);
            $game->setReleaseDate(new \DateTimeImmutable($data['released']));
            $game->setWebsite($data['website']);
            $game->setGameId($id);
            $game->setMetacritics($data['metacritic']);
            //$dateActuelle = new DateTimeImmutable();
    
        }
        
        if($this->getUser()){
            
            $entityManager->persist($game);
            $entityManager->flush();
            $gameUser = new GameUser();
            if($foundGame){
                $gameUser->setGame($foundGame);
            }else{
                $gameUser->setGame($game);
            }

           $gameUser->setUser($this->getUser());
           $gameUser->setAddedAt(new DateTimeImmutable());
           $gameUser->setIsFav(false);
           $gameUser->setStatus("played");

           $entityManager->persist($gameUser);
           $entityManager->flush();
        }
         return $this->redirectToRoute('app_game');
        
    }

    #[Route('/{id}', name: 'app_game_show', methods: ['GET'])]
    public function show(Game $game): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_game_delete', methods: ['POST'])]
    public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    }

  
}


