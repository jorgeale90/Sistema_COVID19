<?php

namespace App\Controller;

use App\Entity\ConsejoPopular;
use App\Form\ConsejoPopularType;
use App\Repository\ConsejoPopularRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/consejopopular")
 */
class ConsejoPopularController extends AbstractController
{
    /**
     * @Route("/", name="consejopopular_index", methods={"GET"})
     */
    public function index(ConsejoPopularRepository $consejoPopularRepository): Response
    {
        return $this->render('consejopopular/index.html.twig', [
            'consejopopular' => $consejoPopularRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="consejopopular_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $consejopopular = new ConsejoPopular();
        $form = $this->createForm(ConsejoPopularType::class, $consejopopular, array('editar' => false));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($consejopopular);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Consejo Popular satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Consejo Popular: %s', $consejopopular->getNombre()));

            return $this->redirectToRoute('consejopopular_index');
        }

        return $this->render('consejopopular/new.html.twig', [
            'consejopopular' => $consejopopular,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="consejopopular_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ConsejoPopular $consejopopular): Response
    {
        $form = $this->createForm(ConsejoPopularType::class, $consejopopular, array('editar' => true));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Consejo Popular satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Consejo Popular: %s', $consejopopular->getNombre()));

            return $this->redirectToRoute('consejopopular_index');
        }

        return $this->render('consejopopular/edit.html.twig', [
            'consejopopular' => $consejopopular,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("consejopopular/remove/{id}", name="removerconsejopopular")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(ConsejoPopular::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Consejo Popular!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Consejo Popular satisfactoriamente!!!');
        }

        return $this->redirectToRoute('consejopopular_index');
    }

    /**
     * @Route("/getmunicipioconxprovinciacon", name="municipiocon_x_provinciacon", methods={"GET","POST"})
     */
    public function getMunicipioconxProvinciacon(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $provincia_id = $request->get('provincia_id');
        $muni = $em->getRepository('App:Municipio')->findByProvinciacon($provincia_id);
        return new JsonResponse($muni);
    }
}