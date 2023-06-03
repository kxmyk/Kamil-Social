<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAll(),
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(Request $request, MicroPostRepository $posts): Response
    {
        $form = $this->createForm(MicroPostType::class);

        // Update state of form based on request data
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get data from form
            $post = $form->getData();
            $post->setCreated(new DateTime());
            $posts->save($post, true);
            // Add a flash message
            $this->addFlash('success', 'Post have been added!');
            // Redirect
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render(
            'micro_post/add.html.twig', [
                'form' => $form,
            ]
        );
    }

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    public function edit(MicroPost $post, Request $request, MicroPostRepository $posts): Response
    {
        $form = $this->createForm(MicroPostType::class, $post);

        // Update state of form based on request data
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get data from form
            $post = $form->getData();
            $posts->save($post, true);
            // Add a flash message
            $this->addFlash('success', 'Post have been updated!');
            // Redirect
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render(
            'micro_post/edit.html.twig', [
                'form' => $form,
            ]
        );

    }
}
