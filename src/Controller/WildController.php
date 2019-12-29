<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Route("/wild")
 */
class WildController extends AbstractController
{

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
     * @return Response
     */
    public function show(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
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
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/category/{categoryName}", name="show_category")
     * @return Response A response instance
     */
    public function showByCategory(string $categoryName)
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category'=> $category], ['id' => 'DESC'], 3);

        if (!$category) {
            throw $this->createNotFoundException(
                'No program with '.$categoryName.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/category.html.twig', [
            'program' => $program,
            'categoryName' => $categoryName,

        ]);
    }
    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/show/program/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show_program")
     * @return Response
     */
    public function showByProgram(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show_program.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }
    /**
     * @param int $id
     * @Route("/show/program/season/{id}", name="show_season")
     * @return Response
     */
    public function showBySeason(int $id) :response {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['program' =>$id]);

            $program = $season->getProgram();
            $episode = $season->getEpisodes();

        return $this->render('wild/show_season.html.twig', [
            'season' => $season,
            'program' => $program,
            'episode' => $episode,
        ]);

    }
    /**
     * @Route("/show/program/episode/{id}", name="show_episode_season")
     */
    public function showEpisode(Episode $episode, Request $request, $id): Response
    {
        $article = $this->getDoctrine()->getRepository(Episode::class)->findOneBy(['id' => $id]);
        $test = $article;
        $comment= new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $entityManager = $this->getDoctrine()->getManager();
        $season = $episode->getSeason();
        $program = $season->getProgram();
        $comment = $entityManager->getRepository("App\Entity\Comment")->findAll();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $content = $form["comment"]->getData();
            $rate = $form["rate"]->getData();
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $user = $this->getUser();
            //$userID = $user->getId();
            $comment= new Comment();
            $comment->setAuthor($user);
            $comment->setEpisode($test);
            $comment->setComment($content);
            $comment->setRate($rate);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('show_episode_season', array('id' => $id));
        }
        return $this->render('wild/show_episode.html.twig', [
        'season' => $season,
        'program' => $program,
        'episode' => $episode,
        'comments' => $comment,
        'form' => $form->createView(),
    ]);
    }

    /**
     * @Route("/show/categoryName/")
     */
    public function showCategory() :Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render('wild/show_category.html.twig', [
            'category' => $categories,
        ]);
    }
    /**
     * @Route("/actor/{id}")
     */
    public function showActor(int $id) :Response
    {
        $actors = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findOneBy(['id' =>$id]);
        $films = $actors->getPrograms();
        return $this->render('wild/show_actor.html.twig', [
            'actor' => $actors,
            'program' => $films,
        ]);
    }
}
