<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Route("/wild")
 */
class WildController extends AbstractController
{

    /**
     * @param string $slug The slugger
     * @Route("/show/{slug}", requirements={"slug"="[a-z0-9-]+$"}, defaults={"slug" = null}, name="wild_show_slug")
     */
    public function show(?string $slug): Response
    {
        $slugWithoutDash = str_replace("-", " ", $slug);
        $slugInMag = ucwords($slugWithoutDash);
        return $this->render('/wild/show.html.twig', ['slug' => $slugInMag]);

    }
    /**
    * Show all rows from Program’s entity
    *
    * @Route("/", name="wild_index")
    * @return Response A response instance
    */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs]
        );
    }
}
