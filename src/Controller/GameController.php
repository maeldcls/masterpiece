<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use App\Entity\Tag;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/', name: 'app_game')]
    public function index(HttpFoundationRequest $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $searchWord = $form->get('searchText')->getData();
            $searchWordUpdated = strtr($searchWord, ' ', '-');
            
            return $this->redirectToRoute('app_search',['searchWord' => $searchWordUpdated]);
        }

        $game = new Game();
        $tag = new Tag();
        $genre = new Genre();

        $developer = new Developer();


        // Remplacez "YOUR_API_KEY" par votre clé API RAWG.io
        $apiKey = "85c1e762dda2428786a58b352a42ade2";
        $gameSlug = "the-witcher-3-wild-hunt"; // Remplacez par le slug du jeu que vous souhaitez rechercher

        $limit = 24; // Nombre de jeux à récupérer
        $keyword = "skyrim";

        $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&genres=action&ordering=-metacritic&page_size=$limit";


        // Initialisation de cURL
        $ch = curl_init($apiUrl);

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

        // Traitement de la réponse
        $data = json_decode($response, true);


        $results = $data['results'];
        $games = [];

        foreach ($results as $data) {
            $game = new Game();
            if (isset($data['background_image']) && !empty($data['background_image'])) {
                // Publishers
                $game->setTitle($data['name']);
                //$game->setSummary($data['description']);
                if (isset($data['metacritic'])) {
                    $game->setMetacritics($data['metacritic']);
                }
                // pour accéder aux différents screenshots 
                // $game->setBackgroundImage($data['short_screenshots'][0]['image']);



                foreach ($data['short_screenshots'] as $screenshot) {
                    $screenshots[] = $screenshot['image'];
                }

                $game->setBackgroundImage($data['background_image']);
                $game->setScreenshots($screenshots);
                unset($screenshots);

                if (isset($data['publishers'])) {
                    foreach ($data['publishers'] as $publisher) {
                        $publi = new Publisher();
                        $publi->setName($publisher['name']);
                        $game->addPublisher($publi);
                    }
                }

                // Developers
                if (isset($data['developers'])) {
                    foreach ($data['developers'] as $developer) {
                        $dev = new Developer();
                        $dev->setName($developer['name']);
                        $game->addDeveloper($dev);
                    }
                }

                // Genres
                if (isset($data['genres'])) {
                    foreach ($data['genres'] as $genreData) {
                        $genre = new Genre();
                        $genre->setName($genreData['name']);
                        $game->addGenre($genre);
                    }
                }

                // Platforms
                if (isset($data['platforms'])) {
                    foreach ($data['platforms'] as $platformData) {
                        $platform = new Platform();
                        $platform->setName($platformData['platform']['name']);
                        if (isset($data['platforms']['image_background'])) {
                            $platform->setImage($platformData['platform']['image_background']);
                        }
                        $game->addPlatform($platform);
                    }
                }
                if (isset($data['parent_platforms'])) {
                    $parentPlatforms = [];
                    $uniquePlatforms = [];
                
                    foreach ($data['parent_platforms'] as $parentPlatformData) {
                        $platformSlug = $parentPlatformData['platform']['slug'];
                
                        if (!in_array($platformSlug, $uniquePlatforms)) {
                            $parentPlatforms[] = $platformSlug;
                            $uniquePlatforms[] = $platformSlug; // Ajouter la plateforme au tableau des éléments uniques
                        }
                    }
                    $game->setParentPlatform($parentPlatforms);
                }
                if (isset($data['id'])) {
                    $game->setGameId($data['id']);
                }
                array_push($games, $game);
            }
        }

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $games,
            'formSearch'=>$form->createView(),
        ]);
    }
}
