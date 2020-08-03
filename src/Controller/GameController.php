<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/game/{id}", name="game")
     */
    public function game()
    {
        //get user
        //check if user is in game
        //get status



        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    /**
     * @Route("/lobby", name="game_lobby")
     */
     public function lobby()
     {
       //get user
     }

     /**
      * @Route("/guess", name="guess")

      * @param Request $request
      */
     public function guess($request) {
       
     }
}
