<?php

namespace Semeformation\Mvc\Cinema_crud\Controllers;
use  Semeformation\Mvc\Cinema_crud\Controllers\CinemaController;
use  Semeformation\Mvc\Cinema_crud\Controllers\FavoriteController;
use  Semeformation\Mvc\Cinema_crud\Controllers\HomeController;
use  Semeformation\Mvc\Cinema_crud\Controllers\MovieController;
use  Semeformation\Mvc\Cinema_crud\Controllers\ShowtimesController;
use  \Psr\Log\LoggerInterface;

/**
*
*/
class Router{

    private $homeCtrl;
    private $favoriteCtrl;
    private $cinemaCtrl;
    private $movieCtrl;
    private $showtimeCtrl;

    public function routeRequest(){

    }

    function __construct(LoggerInterface $logger){
        $this -> logger = $logger;
        $this -> homeCtrl = $homeCtrl;
        $this -> favoriteCtrl = $favoriteCtrl;
        $this -> cinemaCtrl = $cinemaCtrl;
        $this -> movieCtrl = $movieCtrl;
        $this -> showtimeCtrl = $showtimeCtrl;
    }



}
