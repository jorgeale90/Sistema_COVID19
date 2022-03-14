<?php

namespace App\Controller;

use App\Entity\TipoMuestra;
use App\Form\TipoMuestraType;
use App\Repository\TipoMuestraRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/tipomuestra")
 */
class TipoMuestraController extends AbstractController
{
    /**
     * @Route("/", name="tipomuestra_index", methods={"GET"})
     */
    public function index(TipoMuestraRepository $tipoRepository): Response
    {
        return $this->render('tipomuestra/index.html.twig', [
            'tipo' => $tipoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tipomuestra_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tipo = new TipoMuestra();
        $form = $this->createForm(TipoMuestraType::class, $tipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tipo);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Tipo de Muestra satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Tipo Muestra: %s', $tipo->getNombre()));

            return $this->redirectToRoute('tipomuestra_index');
        }

        return $this->render('tipomuestra/new.html.twig', [
            'tipo' => $tipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipomuestra_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TipoMuestra $tipo): Response
    {
        $form = $this->createForm(TipoMuestraType::class, $tipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Tipo de Muestra satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Tipo Muestra: %s', $tipo->getNombre()));

            return $this->redirectToRoute('tipomuestra_index');
        }

        return $this->render('tipomuestra/edit.html.twig', [
            'tipo' => $tipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("tipomuestra/remove/{id}", name="removertipomuestra")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(TipoMuestra::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Tipo de Muestra!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Tipo de Muestra satisfactoriamente!!!');
        }

        return $this->redirectToRoute('tipomuestra_index');
    }
}