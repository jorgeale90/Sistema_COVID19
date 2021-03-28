<?php

namespace App\Controller;

use App\Entity\Municipio;
use App\Form\MunicipioType;
use App\Repository\MunicipioRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/municipio")
 */
class MunicipioController extends AbstractController
{
    /**
     * @Route("/", name="municipio_index", methods={"GET"})
     */
    public function index(MunicipioRepository $municipioRepository): Response
    {
        return $this->render('municipio/index.html.twig', [
            'municipio' => $municipioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="municipio_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $municipio = new Municipio();
        $form = $this->createForm(MunicipioType::class, $municipio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($municipio);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Municipio satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Municipio: %s', $municipio->getNombre()));

            return $this->redirectToRoute('municipio_index');
        }

        return $this->render('municipio/new.html.twig', [
            'municipio' => $municipio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="municipio_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Municipio $municipio): Response
    {
        $form = $this->createForm(MunicipioType::class, $municipio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Municipio satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Municipio: %s', $municipio->getNombre()));

            return $this->redirectToRoute('municipio_index');
        }

        return $this->render('municipio/edit.html.twig', [
            'municipio' => $municipio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("municipio/remove/{id}", name="removermunicipio")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Municipio::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Municipio!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Municipio satisfactoriamente!!!');
        }

        return $this->redirectToRoute('municipio_index');
    }

    /**
     * @Route("/exportarmunicipioexcel", name="exportarmunicipio_excel", methods={"GET"})
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function exportarmunicipioExcel()
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
                                            provincia.nombre,
                                            municipio.nombremunicipio,
                                            municipio.id
                                            FROM
                                            municipio
                                            INNER JOIN provincia ON municipio.provincia_id = provincia.id
                                            INNER JOIN pais ON provincia.pais_id = pais.id
                                            ORDER BY
                                            municipio.id ASC");
        $row = 3;
        while ($data = mysqli_fetch_object($query)){
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$row , $data->id)
                ->setCellValue('B'.$row , $data->nombrepais)
                ->setCellValue('C'.$row , $data->nombre)
                ->setCellValue('C'.$row , $data->nombremunicipio)
            ;
            $row++;
        }

        //filtrador
        $firstRow=2;
        $lastRow=$row-1;
        $spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.":D".$lastRow);

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);

        $spreadsheet->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setHorizontal('center');

        $spreadsheet->getActiveSheet()
            ->setCellValue('A1' , 'LISTADO DE PROVINCIAS')
            ->setCellValue('A2' , 'ID')
            ->setCellValue('B2' , 'NOMBRE DEL PAIS')
            ->setCellValue('C2' , 'NOMBRE DE LA PROVINCIA')
            ->setCellValue('D2' , 'NOMBRE DEL MUNICIPIO')
            ->setTitle("Listado 1")
        ;

        $spreadsheet->getActiveSheet()->mergeCells('A1:D1');

        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                "font" => [ "bold" => true, "color" => ["argb" => "FFFFFF"], "size" => 16, ],
                "alignment" => [ "horizontal" => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, ],
                "borders" => [ "top" => [ "borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, ], ],
                "fill" => [ "fillType" => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR, "rotation" => 90,
                    "startColor" => [ "argb" => "D52B1E", ], "endColor" => [ "argb" => "D52B1E", ], ],
            )

        );

        $spreadsheet->getActiveSheet()->getStyle('A2:D2')->applyFromArray(
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
        $fileName = 'Listado de Municipios.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}