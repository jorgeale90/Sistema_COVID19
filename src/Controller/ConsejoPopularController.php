<?php

namespace App\Controller;

use App\Entity\Alojamiento;
use App\Form\AlojamientoType;
use App\Repository\AlojamientoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/alojamiento")
 */
class AlojamientoController extends AbstractController
{
    /**
     * @Route("/", name="alojamiento_index", methods={"GET"})
     */
    public function index(AlojamientoRepository $alojamientoRepository): Response
    {
        return $this->render('alojamiento/index.html.twig', [
            'alojamiento' => $alojamientoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="alojamiento_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $alojamiento = new Alojamiento();
        $form = $this->createForm(AlojamientoType::class, $alojamiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($alojamiento);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Alojamiento satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Alojamiento: %s', $alojamiento->getNombre()));

            return $this->redirectToRoute('alojamiento_index');
        }

        return $this->render('alojamiento/new.html.twig', [
            'alojamiento' => $alojamiento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="alojamiento_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Alojamiento $alojamiento): Response
    {
        $form = $this->createForm(AlojamientoType::class, $alojamiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Alojamiento satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Alojamiento: %s', $alojamiento->getNombre()));

            return $this->redirectToRoute('alojamiento_index');
        }

        return $this->render('alojamiento/edit.html.twig', [
            'alojamiento' => $alojamiento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("alojamiento/remove/{id}", name="removeralojamiento")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Alojamiento::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Alojamiento!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Alojamiento satisfactoriamente!!!');
        }

        return $this->redirectToRoute('alojamiento_index');
    }
}