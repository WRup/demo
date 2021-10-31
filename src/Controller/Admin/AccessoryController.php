<?php

namespace App\Controller\Admin;

use App\Entity\Accessory;
use App\Form\AccessoryType;
use App\Repository\AccessoryRepository;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage blog contents in the backend.
 *
 * Please note that the application backend is developed manually for learning
 * purposes. However, in your real Symfony application you should use any of the
 * existing bundles that let you generate ready-to-use backends without effort.
 *
 * See http://knpbundles.com/keyword/admin
 *
 * @Route("/lab/admin/post")
 * @IsGranted("ROLE_ADMIN")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class AccessoryController extends AbstractController
{

    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, methods="GET", name="lab_admin_index")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"_format"="html"}, methods="GET", name="lab_admin_index_paginated")
     * @Cache(smaxage="10")
     *
     * NOTE: For standard formats, Symfony will also automatically choose the best
     * Content-Type header for the response.
     * See https://symfony.com/doc/current/routing.html#special-parameters
     */
    public function index(int $page, AccessoryRepository $accessories): Response
    {
        $latestAccessories = $accessories->findAll($page);

        return $this->render('admin/lab/index.html.twig', ['paginator' => $latestAccessories]);
    }

//    /**
//     * Creates a new Post entity.
//     *
//     * @Route("/new", methods="GET|POST", name="admin_post_new")
//     *
//     * NOTE: the Method annotation is optional, but it's a recommended practice
//     * to constraint the HTTP methods each controller responds to (by default
//     * it responds to all methods).
//     */
//    public function new(Request $request): Response
//    {
//        $post = new Post();
//        $post->setAuthor($this->getUser());
//
//        // See https://symfony.com/doc/current/form/multiple_buttons.html
//        $form = $this->createForm(PostType::class, $post)
//            ->add('saveAndCreateNew', SubmitType::class);
//
//        $form->handleRequest($request);
//
//        // the isSubmitted() method is completely optional because the other
//        // isValid() method already checks whether the form is submitted.
//        // However, we explicitly add it to improve code readability.
//        // See https://symfony.com/doc/current/forms.html#processing-forms
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($post);
//            $em->flush();
//
//            // Flash messages are used to notify the user about the result of the
//            // actions. They are deleted automatically from the session as soon
//            // as they are accessed.
//            // See https://symfony.com/doc/current/controller.html#flash-messages
//            $this->addFlash('success', 'post.created_successfully');
//
//            if ($form->get('saveAndCreateNew')->isClicked()) {
//                return $this->redirectToRoute('admin_post_new');
//            }
//
//            return $this->redirectToRoute('admin_post_index');
//        }
//
//        return $this->render('admin/blog/new.html.twig', [
//            'post' => $post,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * Finds and displays a Post entity.
//     *
//     * @Route("/{id<\d+>}", methods="GET", name="admin_post_show")
//     */
//    public function show(Accessory $accessory): Response
//    {
//        // This security check can also be performed
//        // using an annotation: @IsGranted("show", subject="post", message="Posts can only be shown to their authors.")
//        $this->denyAccessUnlessGranted(PostVoter::SHOW, $accessory, 'Posts can only be shown to their authors.');
//
//        return $this->render('admin/blog/show.html.twig', [
//            'post' => $accessory,
//        ]);
//    }
//
    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/{id<\d+>}/edit", methods="GET|POST", name="lab_admin_accessory_edit")
     */
    public function edit(Request $request, Accessory $accessory, AccessoryRepository $repository, LoggerInterface $logger): Response
    {
        $amountOfLoans = $repository->findLoanedAccessoriesById($accessory->getId());
//        $logger->info($amountOfLoans);
        $form = $this->createForm(AccessoryType::class, $accessory, array('amountOfLoans' => $amountOfLoans));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('lab_admin_accessory_edit', ['id' => $accessory->getId()]);
        }

        return $this->render('admin/lab/edit.html.twig', [
            'post' => $accessory,
            'form' => $form->createView(),
        ]);
    }
//
//    /**
//     * Deletes a Post entity.
//     *
//     * @Route("/{id}/delete", methods="POST", name="admin_post_delete")
//     * @IsGranted("delete", subject="post")
//     */
//    public function delete(Request $request, Post $post): Response
//    {
//        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
//            return $this->redirectToRoute('admin_post_index');
//        }
//
//        // Delete the tags associated with this blog post. This is done automatically
//        // by Doctrine, except for SQLite (the database used in this application)
//        // because foreign key support is not enabled by default in SQLite
//        $post->getTags()->clear();
//
//        $em = $this->getDoctrine()->getManager();
//        $em->remove($post);
//        $em->flush();
//
//        $this->addFlash('success', 'post.deleted_successfully');
//
//        return $this->redirectToRoute('admin_post_index');
//    }
}