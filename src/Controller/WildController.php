<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{

    /**
     * @Route("/wild/show/{slug}", requirements={"slug"="[a-z0\-9]+"},name="wild_show_slug")
     */
    public function show($slug = 'Aucune-série-sélectionnée-veuillez-choisir-une-série.'): Response
    {
        $slugWithoutDash = str_replace("-", " ", $slug);
        $slugInMag = ucwords($slugWithoutDash);
        return $this->render('/wild/show.html.twig', ['slug' => $slugInMag]);
    }
}
