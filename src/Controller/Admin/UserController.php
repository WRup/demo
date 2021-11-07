<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage operations on users.
 *
 *
 * @Route("/lab/admin/users")
 * @IsGranted("ROLE_ADMIN")
 *
 */
class UserController extends AbstractController
{

    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, methods="GET", name="lab_admin_users")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"_format"="html"}, methods="GET", name="lab_admin_users_paginated")
     * @Cache(smaxage="10")
     *
     */
    public function index(int $page, UserRepository $userRepository): Response
    {
        $latestUsers = $userRepository->findAllStudentUsersPaginator($page);

        return $this->render('admin/user/index.html.twig', ['paginator' => $latestUsers]);
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id<\d+>}/edit", methods="GET|POST", name="lab_admin_user_edit")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('lab_admin_user_edit', ['id' => $user->getId()]);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Deletes an User entity.
     *
     * @Route("/user/{id}/delete", methods="POST", name="lab_admin_user_delete")
     */
    public function deleteUser(Request $request, User $user): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('lab_admin_home');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'user.deleted_successfully');

        return $this->redirectToRoute('lab_admin_users');
    }


    /**
     * Creates a new User entity.
     *
     * @Route("/new", methods="GET|POST", name="lab_admin_user_new")
     *.
     */
    public function newStudentUser(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_USER"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'user.created_successfully');

            return $this->redirectToRoute('lab_admin_users');
        }

        return $this->render('admin/user/new.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}