<?php

namespace App\Controller;

use App\Entity\Nacionalidad;
use App\Form\NacionalidadType;
use App\Repository\NacionalidadRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/nacionalidad")
 */
class NacionalidadController extends Controller
{
    /**
     * @Route("/", name="nacionalidad_index", methods={"GET"})
     */
    public function index(NacionalidadRepository $nacionalidadRepository): Response
    {
        return $this->render('nacionalidad/index.html.twig', [
            'nacionalidad' => $nacionalidadRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="nacionalidad_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $nacionalidad = new Nacionalidad();
        $form = $this->createForm(NacionalidadType::class, $nacionalidad);
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errors = $validator->validate($form);
        if ($request->isXmlHttpRequest() == true) {
            $msg = array();
            foreach ($errors as $error) {
                $msg[] = $error->getMessage();
            }
            return new JsonResponse($msg);
        } elseif ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($nacionalidad);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado una Nacionalidad satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Nacionalidad: %s', $nacionalidad->getNombre()));

            return $this->redirectToRoute('nacionalidad_index');
        }

        return $this->render('nacionalidad/new.html.twig', [
            'nacionalidad' => $nacionalidad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="nacionalidad_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Nacionalidad $nacionalidad): Response
    {
        $form = $this->createForm(NacionalidadType::class, $nacionalidad);
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errors = $validator->validate($form);
        if ($request->isXmlHttpRequest() == true) {
            $msg = array();
            foreach ($errors as $error) {
                $msg[] = $error->getMessage();
            }
            return new JsonResponse($msg);
        } elseif ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado una Nacionalidad satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Nacionalidad: %s', $nacionalidad->getNombre()));

            return $this->redirectToRoute('nacionalidad_index');
        }

        return $this->render('nacionalidad/edit.html.twig', [
            'nacionalidad' => $nacionalidad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("nacionalidad/remove/{id}", name="removernacionalidad")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Nacionalidad::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra esta Nacionalidad!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado una Nacionalidad satisfactoriamente!!!');
        }

        return $this->redirectToRoute('nacionalidad_index');
    }

    /**
     * @Route("/exportarnacionalidadexcel", name="exportarnacionalidad_excel", methods={"GET"})
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function exportarnacionalidadExcel()
    {
        $conn = mysqli_connect("localhost", "root", "", "covid_bd");
        if (!$conn){
            echo mysqli_error($conn);
            exit;
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $query = mysqli_query($conn, "
                                            SELECT
                                            nacionalidad.nombrenacional,
                                            nacionalidad.id
                                            FROM
                                            nacionalidad
                                            ORDER BY
                                            nacionalidad.id ASC");
        $row = 3;
        while ($data = mysqli_fetch_object($query)){
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$row , $data->id)
                ->setCellValue('B'.$row , $data->nombrenacional)
            ;
            $row++;
        }

        //filtrador
        $firstRow=2;
        $lastRow=$row-1;
        $spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.":B".$lastRow);

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);

        $spreadsheet->getActiveSheet()->getStyle('A2:B2')->getAlignment()->setHorizontal('center');

        $spreadsheet->getActiveSheet()
            ->setCellValue('A1' , 'LISTADO DE NACIONALIDADES')
            ->setCellValue('A2' , 'ID')
            ->setCellValue('B2' , 'NACIONALIDAD')
            ->setTitle("Listado 1")
        ;

        $spreadsheet->getActiveSheet()->mergeCells('A1:B1');

        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                "font" => [ "bold" => true, "color" => ["argb" => "FFFFFF"], "size" => 16, ],
                "alignment" => [ "horizontal" => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, ],
                "borders" => [ "top" => [ "borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, ], ],
                "fill" => [ "fillType" => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR, "rotation" => 90,
                    "startColor" => [ "argb" => "D52B1E", ], "endColor" => [ "argb" => "D52B1E", ], ],
            )

        );

        $spreadsheet->getActiveSheet()->getStyle('A2:B2')->applyFromArray(
            array(
                "borders" => [ "allBorders" => [ "borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    "color" => ["argb" => "000000"], ], ],
                "fill" => [ "fillType" => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    "rotation" => 90, "startColor" => [ "argb" => "ADAFAF", ],
                    "endColor" => [ "argb" => "ADAFAF", ], ],
            )

        );

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'Listado de Nacionalidades.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}