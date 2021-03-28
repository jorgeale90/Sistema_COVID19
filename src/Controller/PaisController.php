<?php

namespace App\Controller;

use App\Entity\Pais;
use App\Form\PaisType;
use App\Repository\PaisRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/pais")
 */
class PaisController extends AbstractController
{
    /**
     * @Route("/", name="pais_index", methods={"GET"})
     */
    public function index(PaisRepository $paisRepository): Response
    {
        return $this->render('pais/index.html.twig', [
            'pais' => $paisRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pais_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pais = new Pais();
        $form = $this->createForm(PaisType::class, $pais);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pais);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un País satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('País: %s', $pais->getNombre()));

            return $this->redirectToRoute('pais_index');
        }

        return $this->render('pais/new.html.twig', [
            'pais' => $pais,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pais_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pais $pais): Response
    {
        $form = $this->createForm(PaisType::class, $pais);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un País satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('País: %s', $pais->getNombre()));

            return $this->redirectToRoute('pais_index');
        }

        return $this->render('pais/edit.html.twig', [
            'pais' => $pais,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("pais/remove/{id}", name="removerpais")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Pais::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este País!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un País satisfactoriamente!!!');
        }

        return $this->redirectToRoute('pais_index');
    }

    /**
     * @Route("/exportarpaisexcel", name="exportarpais_excel", methods={"GET"})
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function exportarpaisExcel()
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
                                                pais.nombrepais,
                                                pais.id
                                                FROM
                                                pais
                                                ORDER BY
                                                pais.id ASC");
        $row = 3;
        while ($data = mysqli_fetch_object($query)){
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$row , $data->id)
                ->setCellValue('B'.$row , $data->nombrepais)
            ;
            $row++;
        }

        //filtrador
        $firstRow=2;
        $lastRow=$row-1;
        $spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.":B".$lastRow);

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(35);

        $spreadsheet->getActiveSheet()->getStyle('A2:B2')->getAlignment()->setHorizontal('center');

        $spreadsheet->getActiveSheet()
            ->setCellValue('A1' , 'LISTADO DE PAISES')
            ->setCellValue('A2' , 'ID')
            ->setCellValue('B2' , 'NOMBRE DEL PAIS')
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
        $fileName = 'Listado de Paises.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}