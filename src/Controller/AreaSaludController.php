<?php

namespace App\Controller;

use App\Entity\AreaSalud;
use App\Form\AreaSaludType;
use App\Repository\AreaSaludRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/areasalud")
 */
class AreaSaludController extends AbstractController
{
    /**
     * @Route("/", name="areasalud_index", methods={"GET"})
     */
    public function index(AreaSaludRepository $areaSaludRepository): Response
    {
        return $this->render('areasalud/index.html.twig', [
            'areasalud' => $areaSaludRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="areasalud_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $areasalud = new AreaSalud();
        $form = $this->createForm(AreaSaludType::class, $areasalud, array('editar' => false));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($areasalud);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Area de Salud satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Area de Salud: %s', $areasalud->getNombre()));

            return $this->redirectToRoute('areasalud_index');
        }

        return $this->render('areasalud/new.html.twig', [
            'areasalud' => $areasalud,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="areasalud_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AreaSalud $areasalud): Response
    {
        $form = $this->createForm(AreaSaludType::class, $areasalud, array('editar' => true));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Area de Salud satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Area de Salud: %s', $areasalud->getNombre()));

            return $this->redirectToRoute('areasalud_index');
        }

        return $this->render('areasalud/edit.html.twig', [
            'areasalud' => $areasalud,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("areasalud/remove/{id}", name="removerareasalud")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(AreaSalud::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Area de Salud!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Area de Salud satisfactoriamente!!!');
        }

        return $this->redirectToRoute('areasalud_index');
    }

    /**
     * @Route("/getmunicipioaxprovinciaa", name="municipioa_x_provinciaa", methods={"GET","POST"})
     */
    public function getMunicipioaxProvinciaa(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $provincia_id = $request->get('provincia_id');
        $muni = $em->getRepository('App:Municipio')->findByProvinciaa($provincia_id);
        return new JsonResponse($muni);
    }
}