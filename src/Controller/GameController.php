<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Publisher;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/', name: 'app_game')]
    public function index(): Response
    {
       $game = new Game();
        $tag = new Tag();
        $genre = new Genre();
        $publisher = new Publisher();
        $developer = new Developer();

        $genre->setName("RPG");
        $publisher->setName("CD PROJEKT");
        $developer->setName("CD PROJEKT");



        $tag->setName("combat");
        $game->setTitle("The Witcher");
        $game->setSummary("C'est l'histoire de géralde le sorceleur");
        $game->addTag($tag);
        $game->addGenre($genre);
        $game->addPublisher($publisher);
        $game->addDeveloper($developer);
        $game->setMetacritics(93);

       



// // Remplacez "YOUR_API_KEY" par votre clé API RAWG.io
// $apiKey = "85c1e762dda2428786a58b352a42ade2";
// $gameSlug = "the-witcher-3-wild-hunt"; // Remplacez par le slug du jeu que vous souhaitez rechercher

// $limit = 5; // Nombre de jeux à récupérer

// $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&ordering=-metacritic&page_size=$limit";


// // Initialisation de cURL
// $ch = curl_init($apiUrl);

// // Configuration des options cURL
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// // Ignorer la vérification SSL (À utiliser avec précaution !)
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// // Exécution de la requête
// $response = curl_exec($ch);

// // Vérification des erreurs cURL
// if (curl_errno($ch)) {
//     die('Erreur cURL : ' . curl_error($ch));
// }

// // Fermeture de la session cURL
// curl_close($ch);

// // Traitement de la réponse
// $data = json_decode($response, true);

// // Affichage des données
// $results = $data['results'];
// foreach ($results as $game) {
//     echo 'Nom du jeu: ' . $game['name'] . '<br>';
//     echo 'Date de sortie: ' . $game['released'] . '<br>';
//     echo 'Score Metacritic: ' . $game['metacritic'] . '<br>';
//     echo '--------------------<br>';
// }
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'game'=>$game
        ]);
    }
}
