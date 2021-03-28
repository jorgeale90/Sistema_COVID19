<?php

namespace App\Controller;

use App\Entity\EstadoIngreso;
use App\Form\EstadoIngresoType;
use App\Repository\EstadoIngresoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/estadoingreso")
 */
class EstadoIngresoController extends AbstractController
{
    /**
     * @Route("/", name="estadoingreso_index", methods={"GET"})
     */
    public function index(EstadoIngresoRepository $estadoIngresoRepository): Response
    {
        return $this->render('estadoingreso/index.html.twig', [
            'estadoingreso' => $estadoIngresoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="estadoingreso_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $estadoingreso = new EstadoIngreso();
        $form = $this->createForm(EstadoIngresoType::class, $estadoingreso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($estadoingreso);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Estado de Ingreso satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Estado de Ingreso: %s', $estadoingreso->getNombre()));

            return $this->redirectToRoute('estadoingreso_index');
        }

        return $this->render('estadoingreso/new.html.twig', [
            'estadoingreso' => $estadoingreso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="estadoingreso_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EstadoIngreso $estadoIngreso): Response
    {
        $form = $this->createForm(EstadoIngresoType::class, $estadoIngreso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Estado de Ingreso satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Estado de Ingreso: %s', $estadoIngreso->getNombre()));

            return $this->redirectToRoute('estadoingreso_index');
        }

        return $this->render('estadoingreso/edit.html.twig', [
            'estadoingreso' => $estadoIngreso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("estadoingreso/remove/{id}", name="removerestadoingreso")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(EstadoIngreso::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Estado de Ingreso!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Estado de Ingreso satisfactoriamente!!!');
        }

        return $this->redirectToRoute('estadoingreso_index');
    }
}