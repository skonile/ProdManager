<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Request;
use App\Response;

class ErrorController extends BaseController{
    public function getError404(Request $request, Response $response){
        $response->sendToPage404();
        $this->render('errors/viewnotfound');
    }

    public function getInternalErrorPage(Request $request, Response $response){
        
    }
}