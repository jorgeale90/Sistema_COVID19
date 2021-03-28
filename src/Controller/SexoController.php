<?php

namespace App\Controller;

use App\Entity\Sexo;
use App\Form\SexoType;
use App\Repository\SexoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/sexo")
 */
class SexoController extends AbstractController
{
    /**
     * @Route("/", name="sexo_index", methods={"GET"})
     */
    public function index(SexoRepository $sexoRepository): Response
    {
        return $this->render('sexo/index.html.twig', [
            'sexos' => $sexoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sexo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sexo = new Sexo();
        $form = $this->createForm(SexoType::class, $sexo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sexo);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Sexo satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Sexo: %s', $sexo->getNombre()));

            return $this->redirectToRoute('sexo_index');
        }

        return $this->render('sexo/new.html.twig', [
            'sexo' => $sexo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sexo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sexo $sexo): Response
    {
        $form = $this->createForm(SexoType::class, $sexo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Sexo satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Sexo: %s', $sexo->getNombre()));

            return $this->redirectToRoute('sexo_index');
        }

        return $this->render('sexo/edit.html.twig', [
            'sexo' => $sexo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("sexo/remove/{id}", name="removersexo")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Sexo::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Sexo!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Sexo satisfactoriamente!!!');
        }

        return $this->redirectToRoute('sexo_index');
    }
}