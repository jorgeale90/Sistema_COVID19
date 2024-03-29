<?php

namespace App\Controller;

use App\Entity\Alojamiento;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/usuario")
 */
class UserController extends AbstractController
{
//    /**
//     * @Route("/", name="user_index", methods={"GET"})
//     */
//    public function index(): Response
//    {
//        $users = $this->getDoctrine()
//            ->getRepository(User::class)
//            ->findAll();
//        $entity = $this->getDoctrine()->getRepository(Alojamiento::class)->findAll();
//
//        return $this->render('user/index.html.twig', [
//            'users' => $users,
//            '$entity' => $entity,
//        ]);
//    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Usuario satisfactoriamente!!!');
        }

        return $this->redirectToRoute('user_index');
    }
}
