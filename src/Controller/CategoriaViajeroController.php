<?php

namespace App\Controller;

use App\Entity\CategoriaViajero;
use App\Form\CategoriaViajeroType;
use App\Repository\CategoriaViajeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/categoriaviajero")
 */
class CategoriaViajeroController extends AbstractController
{
    /**
     * @Route("/", name="categoriaviajero_index", methods={"GET"})
     */
    public function index(CategoriaViajeroRepository $categoriaViajeroRepository): Response
    {
        return $this->render('categoriaviajero/index.html.twig', [
            'categoriaviajero' => $categoriaViajeroRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="categoriaviajero_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categoriaviajero = new CategoriaViajero();
        $form = $this->createForm(CategoriaViajeroType::class, $categoriaviajero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoriaviajero);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado una Categoría de Viajero satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Categoría de Viajero: %s', $categoriaviajero->getNombre()));

            return $this->redirectToRoute('categoriaviajero_index');
        }

        return $this->render('categoriaviajero/new.html.twig', [
            'categoriaviajero' => $categoriaviajero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categoriaviajero_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CategoriaViajero $categoriaViajero): Response
    {
        $form = $this->createForm(CategoriaViajeroType::class, $categoriaViajero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado una Categoría de Viajero satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Categoría de Viajero: %s', $categoriaViajero->getNombre()));

            return $this->redirectToRoute('categoriaviajero_index');
        }

        return $this->render('categoriaviajero/edit.html.twig', [
            'categoriaviajero' => $categoriaViajero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("categoriaviajero/remove/{id}", name="removercategoriaviajero")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(CategoriaViajero::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra esta Categoría de Viajero!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado una Categoría de Viajero satisfactoriamente!!!');
        }

        return $this->redirectToRoute('categoriaviajero_index');
    }
}