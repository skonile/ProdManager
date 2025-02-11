<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Request;
use App\Response;

class PageController extends BaseController{
    public function __construct(){
        parent::__construct();
    }

    public function getHome(Request $request, Response $response){
        $this->render('home');
    }

    public function getSettings(Request $request, Response $response){
        $this->render(
            'settings', 
            [
                'route' => $request->getFirstUriPart(),
                'name' => 'well that worked'
            ]
        );
    }

    public function getAbout(Request $request, Response $response){
        $this->render('about', ['route' => $request->getFirstUriPart()]);
    }
}