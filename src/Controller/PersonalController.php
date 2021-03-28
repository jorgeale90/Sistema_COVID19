<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Filter\PersonalFilterType;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Personal;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use App\Form\PersonalType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/user/personal")
 */
class PersonalController extends Controller
{
    /**
     * @Route("/", name="personal_index", methods={"GET"})
     * @Security("has_role('ROLE_USER') and has_role('ROLE_ADMIN') and is_granted('ROLE_SUPER_ADMIN')")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        // initialize a query builder
        $filterBuilder = $this->get('doctrine.orm.entity_manager')
            ->getRepository('App:Personal')
            ->createQueryBuilder('e');

        $form = $this->get('form.factory')->create(PersonalFilterType::class);

        if ($request->query->has($form->getName())) {
            // manually bind values from the request
            $form->submit($request->query->get($form->getName()));

            // build the query from the given form object
            $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder);
        }

        $query = $filterBuilder->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            9/*limit per page*/
        );

        return $this->render('personal/index.html.twig', array(
            'form' => $form->createView(),
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/new", name="personal_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $personal = new Personal();
        $form = $this->createForm(PersonalType::class, $personal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($personal);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Personal satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Personal: %s', $personal->getNombreCompleto()));

            return $this->redirectToRoute('personal_index');
        }

        return $this->render('personal/new.html.twig', [
            'personal' => $personal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="personal_show", methods={"GET"})
     */
    public function show($id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $personal = $em->getRepository('App:Personal')->find($id);

        $form = $this->createForm(PersonalType::class, $personal);

        return $this->render('personal/show.html.twig', array(
            'personal'      => $personal,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit", name="personal_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Personal $personal): Response
    {
        $form = $this->createForm(PersonalType::class, $personal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Personal satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Personal: %s', $personal->getNombreCompleto()));

            return $this->redirectToRoute('personal_index');
        }

        return $this->render('personal/edit.html.twig', [
            'personal' => $personal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="personal_delete", methods={"DELETE"})
     * @Security("has_role('ROLE_ADMIN') and is_granted('ROLE_SUPER_ADMIN')")
     */
    public function delete(Request $request, Personal $libro): Response
    {
        if ($this->isCsrfTokenValid('delete'.$libro->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($libro);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Personal satisfactoriamente!!!');
        }

        return $this->redirectToRoute('personal_index');
    }

    private $knpSnappy;

    public function __construct(\Knp\Snappy\Pdf $knpSnappy) { $this->knpSnappy = $knpSnappy; }

    /**
     * @Route("/exportarpdf", name="exportar_personal_pdf", methods={"GET"})
     * @param \Knp\Snappy\Pdf $knpSnappy
     */
    public function exportarPDF(\Knp\Snappy\Pdf $snappy)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('App:Personal')->findAll();

        $html = $this->renderView('personal/personal_pdf.html.twig', array(
            'entities' => $entities
        ));

        return new PdfResponse(
            $this->knpSnappy->getOutputFromHtml($html),
            'personalPDF.pdf'
        );
    }

    /**
     * @Route("/getespecialidadxcargo", name="especialidad_x_cargo", methods={"GET","POST"})
     */
    public function getEspecialidadxCargoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cargo_id = $request->get('cargo_id');
        $espe = $em->getRepository('App:Especialidad')->findByCargo($cargo_id);
        return new JsonResponse($espe);
    }
}