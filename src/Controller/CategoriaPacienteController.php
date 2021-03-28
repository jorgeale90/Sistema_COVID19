<?php

namespace App\Controller;

use App\Entity\CategoriaPaciente;
use App\Form\CategoriaPacienteType;
use App\Repository\CategoriaPacienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/categoriapaciente")
 */
class CategoriaPacienteController extends AbstractController
{
    /**
     * @Route("/", name="categoriapaciente_index", methods={"GET"})
     */
    public function index(CategoriaPacienteRepository $categoriaPacienteRepository): Response
    {
        return $this->render('categoriapaciente/index.html.twig', [
            'categoriapaciente' => $categoriaPacienteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="categoriapaciente_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categoria = new CategoriaPaciente();
        $form = $this->createForm(CategoriaPacienteType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoria);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado una Categoría de Paciente satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Categoría de Paciente: %s', $categoria->getNombre()));

            return $this->redirectToRoute('categoriapaciente_index');
        }

        return $this->render('categoriapaciente/new.html.twig', [
            'categoria' => $categoria,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categoriapaciente_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CategoriaPaciente $categoriaPaciente): Response
    {
        $form = $this->createForm(CategoriaPacienteType::class, $categoriaPaciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado una Categoría de Paciente satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Categoría de Paciente: %s', $categoriaPaciente->getNombre()));

            return $this->redirectToRoute('categoriapaciente_index');
        }

        return $this->render('categoriapaciente/edit.html.twig', [
            'categoria' => $categoriaPaciente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("categoriapaciente/remove/{id}", name="removercategoriapaciente")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(CategoriaPaciente::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra esta Categoría de Paciente!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado una Categoría de Paciente satisfactoriamente!!!');
        }

        return $this->redirectToRoute('categoriapaciente_index');
    }
}