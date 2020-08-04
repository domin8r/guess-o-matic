<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Manager\Game as GameManager;

class GameController extends AbstractController
{
    /**
     * @Route("/game/{id}", name="game")
     */
    public function game(Request $request, GameManager $gameManager)
    {
      if (empty($request->get('id')) || ! is_numeric($request->get('id'))) {
        return $this->redirectToRoute('game_lobby');
      }

      $game = $gameManager->getGameById($request->get('id'));

      if (! $game) {
        return $this->redirectToRoute('game_lobby');
      }

      if ($user = $this->getUser()) {     
        if (! $gameManager->userInGame($user, $game)) {
          return $this->redirectToRoute('game_lobby');
        }
      } else {
        return $this->redirectToRoute('login');
      }

      return $this->render('game/index.html.twig', [
          'game' => $game,
      ]);
    }

    /**
     * @Route("/lobby", name="game_lobby")
     */
     public function lobby(GameManager $gameManager)
     {
       if ($user = $this->getUser()) {
          $games = $gameManager->getGamesForUser($user, true);
       } else {
          return $this->redirectToRoute('login');
       }

       return $this->render('game/lobby.html.twig', [
          'games' => $games 
       ]);
     }

     /**
      * @Route("/guess", name="guess")

      * @param Request $request
      */
     public function guess($request) {

     }
}
