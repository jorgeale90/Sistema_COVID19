<?php

namespace App\Controller;

use App\Entity\HospitalIngreso;
use App\Form\HospitalType;
use App\Repository\HospitalIngresoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/hospital")
 */
class HospitalController extends AbstractController
{
    /**
     * @Route("/", name="hospital_index", methods={"GET"})
     */
    public function index(HospitalIngresoRepository $hospitalIngresoRepository): Response
    {
        return $this->render('hospital/index.html.twig', [
            'hospital' => $hospitalIngresoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="hospital_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $hospital = new HospitalIngreso();
        $form = $this->createForm(HospitalType::class, $hospital, array('editar' => false));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hospital);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Hospital satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Hospital: %s', $hospital->getNombre()));

            return $this->redirectToRoute('hospital_index');
        }

        return $this->render('hospital/new.html.twig', [
            'hospital' => $hospital,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hospital_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, HospitalIngreso $hospital): Response
    {
        $form = $this->createForm(HospitalType::class, $hospital, array('editar' => true));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Hospital satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Hospital: %s', $hospital->getNombre()));

            return $this->redirectToRoute('hospital_index');
        }

        return $this->render('hospital/edit.html.twig', [
            'hospital' => $hospital,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("hospital/remove/{id}", name="removerhospital")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(HospitalIngreso::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Hospital!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Hospital satisfactoriamente!!!');
        }

        return $this->redirectToRoute('hospital_index');
    }

    /**
     * @Route("/getmunicipioixprovinciai", name="municipioi_x_provinciai", methods={"GET","POST"})
     */
    public function getMunicipioixProvinciai(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $provincia_id = $request->get('provincia_id');
        $muni = $em->getRepository('App:Municipio')->findByProvinciai($provincia_id);
        return new JsonResponse($muni);
    }
}