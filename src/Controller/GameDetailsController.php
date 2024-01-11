<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameDetailsController extends AbstractController
{
    #[Route('/game/details/{id}', name: 'app_game_details')]
    public function index($id): Response
    {
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
        var_dump($data);
        // $results = $data['results'];
        // var_dump($results);
        // foreach ($results as $data) {
            
        // }
        return $this->render('game_details/index.html.twig', [
            'controller_name' => 'GameDetailsController',
        ]);
    }
}
