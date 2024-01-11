<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatformController extends AbstractController
{
    #[Route('/platform/{platform}', name: 'app_platform')]
    public function index($platform): Response
    {
        $game = new Game();

        $genre = new Genre();

        $developer = new Developer();


        // Remplacez "YOUR_API_KEY" par votre clé API RAWG.io
        $apiKey = "85c1e762dda2428786a58b352a42ade2";
        $limit = 25; // Nombre de jeux à récupérer

        $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&platform=$platform&ordering=-metacritic&page_size=$limit";


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
                        $newPlatform = new Platform();
                        $newPlatform->setName($platformData['platform']['name']);
                        if (isset($data['platforms']['image_background'])) {
                            $newPlatform->setImage($platformData['platform']['image_background']);
                        }
                        $game->addPlatform($newPlatform);
                    }
                }

                array_push($games, $game);
            }
        }

        return $this->render('platform/index.html.twig', [
            'controller_name' => 'PlatformController',
            'games' => $games
        ]);
    }
}
