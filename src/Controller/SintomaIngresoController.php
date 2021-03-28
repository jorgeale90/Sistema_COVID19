<?php

namespace App\Controller;

use App\Entity\SintomasIngreso;
use App\Form\SintomasIngresoType;
use App\Repository\SintomasIngresoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/sintomaingreso")
 */
class SintomaIngresoController extends AbstractController
{
    /**
     * @Route("/", name="sintomaingreso_index", methods={"GET"})
     */
    public function index(SintomasIngresoRepository $sintomasIngresoRepository): Response
    {
        return $this->render('sintomaingreso/index.html.twig', [
            'sintomasingreso' => $sintomasIngresoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sintomaingreso_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sintomaingreso = new SintomasIngreso();
        $form = $this->createForm(SintomasIngresoType::class, $sintomaingreso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sintomaingreso);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Síntoma de Ingreso satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Síntoma de Ingreso: %s', $sintomaingreso->getNombre()));

            return $this->redirectToRoute('sintomaingreso_index');
        }

        return $this->render('sintomaingreso/new.html.twig', [
            'sintomaingreso' => $sintomaingreso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sintomaingreso_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SintomasIngreso $sintomasIngreso): Response
    {
        $form = $this->createForm(SintomasIngresoType::class, $sintomasIngreso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Síntoma de Ingreso satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Síntoma de Ingreso: %s', $sintomasIngreso->getNombre()));

            return $this->redirectToRoute('sintomaingreso_index');
        }

        return $this->render('sintomaingreso/edit.html.twig', [
            'sintomaingreso' => $sintomasIngreso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("sintomaingreso/remove/{id}", name="removersintomaingreso")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(SintomasIngreso::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Síntoma de Ingreso!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Síntoma de Ingreso satisfactoriamente!!!');
        }

        return $this->redirectToRoute('sintomaingreso_index');
    }
}