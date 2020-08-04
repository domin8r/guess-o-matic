<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Manager\Game as GameManager;
use App\Form\Guess   as GuessForm;

class GameController extends AbstractController
{
    /**
     * @Route("/game/{id}", name="game")
     *
     * @param Request     $request
     * @param GameManager $gameManager
     *
     * @return Response
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
        return $this->redirectToRoute('app_login');
      }

      $userTurn = $gameManager->isUserTurn($user, $game);

      $form = $this->createForm(GuessForm::class);
      $form->get('game')->setData($game->getId());

      return $this->render('game/index.html.twig', [
          'game'     => $game,
          'user'     => $user,
          'form'     => $form->createView(),
          'userTurn' => $userTurn
      ]);
    }

    /**
     * @Route("/lobby", name="game_lobby")
     *
     * @param GameManager $gameManager
     *
     * @return Response
     */
    public function lobby(GameManager $gameManager)
    {
        if ($user = $this->getUser()) {
            $games = $gameManager->getGamesForUser($user, true);
        } else {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('game/lobby.html.twig', [
            'games' => $games 
        ]);
    }

     /**
      * @Route("/guess", name="guess")
      *
      * @param Request $request
      * @param GameManager $gameManager
      *
      * @return JsonResponse
      */
    public function guess(Request $request, GameManager $gameManager)
    {
        $guessValue = $request->request->get('guess');
        $gameId     = $request->request->get('game');

        if (! is_numeric($guessValue)) {
            return new JsonResponse(['error' => 'guess value missing']);
        }

        $game = $gameManager->getGameById($gameId);

        if (! $game) {
            return new JsonResponse(['error' => 'game missing']);
        }

        if ($user = $this->getUser()) {     
            if ($gameManager->userInGame($user, $game) && $gameManager->isUserTurn($user, $game)) {
                
                $guessResult = $gameManager->submitGuess($user, $game, $guessValue);

                return new JsonResponse(['result' => $guessResult]);
            }
        }

        return new JsonResponse(['error' => 'something went wrong']);
    }

     /**
      * @Route("/poll", name="poll")
      *
      * @param Request $request
      * @param GameManager $gameManager
      *
      * @return JsonResponse
      */
    public function poll(Request $request, GameManager $gameManager)
    {
        $gameId = $request->request->get('game');

        $game = $gameManager->getGameById($gameId);

        if (! $game) {
            return new JsonResponse(['error' => 'game missing']);
        }

        if ($user = $this->getUser()) {     
            if ($gameManager->userInGame($user, $game)) {

                $turn = $gameManager->isUserTurn($user, $game);

                //build data for json output
                $outputData = [];
                $outputData['turn']   = $turn;
                if ($game->getActive()) {
                    $outputData['active'] = 'true';
                } else {
                    $outputData['active'] = 'false';
                    $outputData['winner'] = $game->getWinner()->getId() === $user->getId() ? true : false;
                }
            }
        }
        
        return new JsonResponse($outputData);
    }
}
