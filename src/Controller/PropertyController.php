<?php 

namespace App\Controller;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController{

    /** 
    * @var Environment
    */
    private $twig;

    public function __construct(Environment $twig){
        $this->twig = $twig;
    }

    /** 
    * @Route("/biens", name="property_index")
    * @return Response
    */
    public function index(): Response {
        return new Response($this->twig->render('pages/property.html.twig'));
    }
}