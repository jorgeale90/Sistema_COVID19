<?php

namespace App\Controller;

use App\Entity\Resultado;
use App\Form\ResultadoType;
use App\Repository\ResultadoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/resultado")
 */
class ResultadoController extends AbstractController
{
    /**
     * @Route("/", name="resultado_index", methods={"GET"})
     */
    public function index(ResultadoRepository $resultadoRepository): Response
    {
        return $this->render('resultado/index.html.twig', [
            'resultado' => $resultadoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="resultado_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $resultado = new Resultado();
        $form = $this->createForm(ResultadoType::class, $resultado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($resultado);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Resultado satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Resultado: %s', $resultado->getNombre()));

            return $this->redirectToRoute('resultado_index');
        }

        return $this->render('resultado/new.html.twig', [
            'resultado' => $resultado,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="resultado_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Resultado $resultado): Response
    {
        $form = $this->createForm(ResultadoType::class, $resultado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Resultado satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Resultado: %s', $resultado->getNombre()));

            return $this->redirectToRoute('resultado_index');
        }

        return $this->render('resultado/edit.html.twig', [
            'resultado' => $resultado,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("resultado/remove/{id}", name="removerresultado")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Resultado::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Resultado!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Resultado satisfactoriamente!!!');
        }

        return $this->redirectToRoute('resultado_index');
    }
}