<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Session;
use Twig\TwigFilter;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class BaseController{
    /** @property Environment $twig A twig instance. */
    protected Environment $twig;

    public function __construct(){
        $loader = new FilesystemLoader(VIEWS_PATH);
        $this->twig = new Environment($loader);

        $this->addTwigFilter('getPaginationRoute', function($route){
            $routeWithNoQuery = explode('?', $route)[0];
            return '/' . explode('/', $routeWithNoQuery)[1];
        });

        $this->addTwigFilter('getPaginationRouteQuery', function($route){
            return explode('?', $route)[1];
        });
    }

    /**
     * Load and render a given view.
     *
     * @param string $view The view to render
     * @param array $args The arguments to pass to the view
     * @return string Rendered content
     */
    protected function render(string $view, array $args = []): string{
        $session = Session::getInstance();
        $args['userlevel'] = @$session->get('userlevel');
        $args['isLoggedIn'] = @$session->isLoggedIn();

        $message = $session->getMessage();       
        if($message !== null){
            $args['message'] = $message;
        }

        $template = $this->twig->load($view . '.html.twig');
        $renderedView = $template->render($args);

        $session->removeMessage();
        echo $renderedView;
    }

    /**
     * Converts a given page number or limit string to int or
     * returns false if the given string can not be converted to int or is less than 1.
     * 
     * @param string $val The given page or limit number
     * @return int|bool Returns an int if the conversion went through 
     *                  and the the number is not less than 1, false otherwise
     */
    protected function getPageOrLimitNum(string $val): int|bool{
        try{
            $num = (int) $val;
            if($num <= 0)
                return false;
            return $num;
        } catch(\Throwable){
            return false;
        }
    }

    public function addTwigFilter(string $name, callable $callable){
        $this->twig->addFilter(new TwigFilter($name, $callable));
    }
}