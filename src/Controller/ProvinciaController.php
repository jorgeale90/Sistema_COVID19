<?php

namespace App\Controller;

use App\Entity\Provincia;
use App\Form\ProvinciaType;
use App\Repository\ProvinciaRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/provincia")
 */
class ProvinciaController extends AbstractController
{
    /**
     * @Route("/", name="provincia_index", methods={"GET"})
     */
    public function index(ProvinciaRepository $provinciaRepository): Response
    {
        return $this->render('provincia/index.html.twig', [
            'provincias' => $provinciaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="provincia_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $provincium = new Provincia();
        $form = $this->createForm(ProvinciaType::class, $provincium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($provincium);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado una Provincia satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Provincia: %s', $provincium->getNombre()));

            return $this->redirectToRoute('provincia_index');
        }

        return $this->render('provincia/new.html.twig', [
            'provincium' => $provincium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="provincia_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Provincia $provincium): Response
    {
        $form = $this->createForm(ProvinciaType::class, $provincium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado una Provincia satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Provincia: %s', $provincium->getNombre()));

            return $this->redirectToRoute('provincia_index');
        }

        return $this->render('provincia/edit.html.twig', [
            'provincium' => $provincium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("provincia/remove/{id}", name="removerprovincia")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Provincia::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra esta Provincia!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado una Provincia satisfactoriamente!!!');
        }

        return $this->redirectToRoute('provincia_index');
    }

    /**
     * @Route("/exportarprovinciaexcel", name="exportarprovincia_excel", methods={"GET"})
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function exportarprovinciaExcel()
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
                                            provincia.nombre,
                                            pais.nombrepais,
                                            provincia.id
                                            FROM
                                            pais
                                            INNER JOIN provincia ON provincia.pais_id = pais.id
                                            ORDER BY
                                            provincia.id ASC");
        $row = 3;
        while ($data = mysqli_fetch_object($query)){
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$row , $data->id)
                ->setCellValue('B'.$row , $data->nombrepais)
                ->setCellValue('C'.$row , $data->nombre)
            ;
            $row++;
        }

        //filtrador
        $firstRow=2;
        $lastRow=$row-1;
        $spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.":C".$lastRow);

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);

        $spreadsheet->getActiveSheet()->getStyle('A2:C2')->getAlignment()->setHorizontal('center');

        $spreadsheet->getActiveSheet()
            ->setCellValue('A1' , 'LISTADO DE PROVINCIAS')
            ->setCellValue('A2' , 'ID')
            ->setCellValue('B2' , 'NOMBRE DEL PAIS')
            ->setCellValue('C2' , 'NOMBRE DE LA PROVINCIA')
            ->setTitle("Listado 1")
        ;

        $spreadsheet->getActiveSheet()->mergeCells('A1:C1');

        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                "font" => [ "bold" => true, "color" => ["argb" => "FFFFFF"], "size" => 16, ],
                "alignment" => [ "horizontal" => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, ],
                "borders" => [ "top" => [ "borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, ], ],
                "fill" => [ "fillType" => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR, "rotation" => 90,
                    "startColor" => [ "argb" => "D52B1E", ], "endColor" => [ "argb" => "D52B1E", ], ],
            )

        );

        $spreadsheet->getActiveSheet()->getStyle('A2:C2')->applyFromArray(
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
        $fileName = 'Listado de Provincias.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}