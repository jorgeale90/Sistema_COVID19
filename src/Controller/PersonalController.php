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
use Doctrine\ORM\EntityManagerInterface;
// Incluir namespaces requeridos de PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Helper\Sample;

/**
 * @Route("/user/personal")
 */
class PersonalController extends Controller
{
    private $knpSnappy;

    public function __construct(\Knp\Snappy\Pdf $knpSnappy)
    {
        $this->knpSnappy = $knpSnappy;
    }

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
        $form = $this->createForm(PersonalType::class, $personal, array('editar' => false));
        $form->handleRequest($request);
        $temp = $personal->getFechaalta();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($temp ==  null){
                $entityManager = $this->getDoctrine()->getManager();
                $personal->setActivo(true);
                $entityManager->persist($personal);
                $entityManager->flush();

                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->add('app_success','Se ha creado un Paciente satisfactoriamente!!!');
                $flashBag->add('app_success', sprintf('Paciente: %s', $personal->getNombreCompleto()));

                return $this->redirectToRoute('personal_index');
            }
            else
            {
                $entityManager = $this->getDoctrine()->getManager();
                $personal->setActivo(false);
                $entityManager->persist($personal);
                $entityManager->flush();

                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->add('app_success','Se ha creado un Paciente satisfactoriamente!!!');
                $flashBag->add('app_success', sprintf('Paciente: %s', $personal->getNombreCompleto()));

                return $this->redirectToRoute('personal_index');
            }
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
        $form = $this->createForm(PersonalType::class, $personal, array('editar' => true));
        $form->handleRequest($request);
        $temp = $personal->getFechaalta();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($temp ==  null){
                $this->getDoctrine()->getManager()->flush();

                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->add('app_warning','Se ha actualizado un Paciente satisfactoriamente!!!');
                $flashBag->add('app_warning', sprintf('Paciente: %s', $personal->getNombreCompleto()));

                return $this->redirectToRoute('personal_index');
            }
            else
            {
                $personal->setActivo(false);
                $this->getDoctrine()->getManager()->flush();

                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->add('app_warning','Se ha actualizado un Paciente satisfactoriamente!!!');
                $flashBag->add('app_warning', sprintf('Paciente: %s', $personal->getNombreCompleto()));

                return $this->redirectToRoute('personal_index');
            }
        }

        return $this->render('personal/edit.html.twig', [
            'personal' => $personal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/deletepersonal/{id}", name="personal_delete")
     * @Security("has_role('ROLE_ADMIN') and is_granted('ROLE_SUPER_ADMIN')")
     */
    public function delete(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Personal::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Paciente!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Paciente satisfactoriamente!!!');
        }

        return $this->redirectToRoute('personal_index');
    }

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
     * @Route("/getmunicipioxprovincia", name="municipio_x_provincia", methods={"GET","POST"})
     */
    public function getMunicipioxProvincia(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $provincia_id = $request->get('provincia_id');
        $muni = $em->getRepository('App:Municipio')->findByProvincia($provincia_id);
        return new JsonResponse($muni);
    }

    /**
     * @Route("/getareasaludxmunicipio", name="areasalud_x_municipio", methods={"GET","POST"})
     */
    public function getAreaSaludxMunicipio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $municipio_id = $request->get('municipio_id');
        $areas = $em->getRepository('App:AreaSalud')->findByMunicipio($municipio_id);
        return new JsonResponse($areas);
    }

    /**
     * @Route("/getconsejopopularxmunicipio", name="consejopopular_x_municipio", methods={"GET","POST"})
     */
    public function getConsejoPopularxMunicipio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $municipio_id = $request->get('municipio_id');
        $consejo = $em->getRepository('App:ConsejoPopular')->findByMunicipioConsejoPopular($municipio_id);
        return new JsonResponse($consejo);
    }

    /**
     * @Route("/getdesactivarpaciente", name="desactivarpaciente", methods={"GET","POST"})
     */
    public function desactivarPaciente(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/getactivarpaciente", name="activarXpaciente", methods={"GET","POST"})
     */
    public function activarPaciente(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $valor = 0;
        $personal_id = $request->request->get('personal_id');
        $personal = $em->getRepository('App:Personal')->find($personal_id);
        if ($personal->getActivo() == false) {
            $personal->setActivo(true);
            $valor = 1;
        } else {
            $personal->setActivo(false);
        }
        $em->persist($personal);
        $em->flush();
        return new Response($valor);
    }

    /**
     * @Route("/exportarexcel", name="exportar_excel", methods={"GET"})
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function exportarExcel()
    {
        $con = mysqli_connect("localhost", "root", "", "covid_bd");
        if (!$con){
            echo mysqli_error($con);
            exit;
        }

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0);
        $query = mysqli_query($con, "
                                SELECT
                                    p.ci,
                                    p.nombre,
                                    p.apellidos,
                                    p.edad,
                                    sexo.nombresexo,
                                    p.direccioncarnet,
                                    municipio.nombremunicipio,
                                    area_salud.nombrearea,
                                    hospital_ingreso.nombreingreso,
                                    nacionalidad.nombrenacional,
                                    pais.nombrepais,
                                    p.fechaentrada,
                                    categoria_viajero.nombrecategoriavi,
                                    estado_ingreso.nombreestadoin,
                                    p.fis,
                                    p.fechaconsulta,
                                    p.fechaingreso,
                                    GROUP_CONCAT(si.nombresintomain) AS nombresintomain,
                                    p.fechatomamuestra,
                                    p.fechaenviomuestra,
                                    resultado.nombreresultado,
                                    p.fecharesultado,
                                    p.fechaalta,
                                    p.observaciones
                                FROM
                                    personal p
                                LEFT JOIN sexo ON p.sexo_id = sexo.id
                                LEFT JOIN municipio ON p.municipio_id = municipio.id
                                LEFT JOIN area_salud ON p.areasalud_id = area_salud.id
                                LEFT JOIN hospital_ingreso ON p.hospitalingreso_id = hospital_ingreso.id
                                LEFT JOIN nacionalidad ON p.nacionalidad_id = nacionalidad.id
                                LEFT JOIN pais ON p.paisprocedencia_id = pais.id
                                LEFT JOIN categoria_viajero ON p.categoriaviajero_id = categoria_viajero.id
                                LEFT JOIN estado_ingreso ON p.estadoingreso_id = estado_ingreso.id
                                INNER JOIN sintomasingreso_personal sp ON p.id = sp.personal_id
                                INNER JOIN sintomas_ingreso si ON si.id = sp.sintomasingreso_id
                                LEFT JOIN resultado ON p.resultado_id = resultado.id
                                GROUP BY
                                    p.id                      
");
        $row = 4;
        while ($data = mysqli_fetch_object($query)){
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$row , $data->ci)
                ->setCellValue('B'.$row , $data->nombre)
                ->setCellValue('C'.$row , $data->apellidos)
                ->setCellValue('D'.$row , $data->edad)
                ->setCellValue('E'.$row , $data->nombresexo)
                ->setCellValue('F'.$row , $data->direccioncarnet)
                ->setCellValue('G'.$row , $data->nombremunicipio)
                ->setCellValue('H'.$row , $data->nombrearea)
                ->setCellValue('I'.$row , $data->nombreingreso)
                ->setCellValue('J'.$row , $data->nombrenacional)
                ->setCellValue('K'.$row , $data->nombrepais)
                ->setCellValue('L'.$row , $data->fechaentrada)
                ->setCellValue('M'.$row , $data->nombrecategoriavi)
                ->setCellValue('N'.$row , $data->nombreestadoin)
                ->setCellValue('O'.$row , $data->fis)
                ->setCellValue('P'.$row , $data->fechaconsulta)
                ->setCellValue('Q'.$row , $data->fechaingreso)
                ->setCellValue('R'.$row , $data->nombresintomain)
                ->setCellValue('S'.$row , $data->fechatomamuestra)
                ->setCellValue('T'.$row , $data->fechaenviomuestra)
                ->setCellValue('U'.$row , $data->nombreresultado)
                ->setCellValue('V'.$row , $data->fecharesultado)
                ->setCellValue('W'.$row , $data->fechaalta)
                ->setCellValue('X'.$row , $data->observaciones)
            ;
            $row++;
        }

        //filtrador
        $firstRow=3;
        $lastRow=$row-1;
        $spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.":X".$lastRow);

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(38);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(28);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(29);
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(40);

        $spreadsheet->getActiveSheet()->getStyle('A3:X3')->getAlignment()->setHorizontal('center');
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1' , 'LISTADO DE INGRESADOS COVID-19')
            ->setCellValue('A3' , 'CARNET DE IDENTIDAD')
            ->setCellValue('B3' , 'NOMBRE')
            ->setCellValue('C3' , 'APELLIDOS')
            ->setCellValue('D3' , 'EDAD')
            ->setCellValue('E3' , 'SEXO')
            ->setCellValue('F3' , 'DIRECCION')
            ->setCellValue('G3' , 'MUNICIPIO')
            ->setCellValue('H3' , 'AREA DE SALUD')
            ->setCellValue('I3' , 'LUGAR DE INGRESO')
            ->setCellValue('J3' , 'NACIONALIDAD')
            ->setCellValue('K3' , 'PAIS DE PROCEDENCIA')
            ->setCellValue('L3' , 'FECHA DE ARRIBO')
            ->setCellValue('M3' , 'CONDICION')
            ->setCellValue('N3' , 'CATEGORIA')
            ->setCellValue('O3' , 'FPS')
            ->setCellValue('P3' , 'FECHA CONSULTA')
            ->setCellValue('Q3' , 'FECHA INGRESO')
            ->setCellValue('R3' , 'DIAGNOSTICO')
            ->setCellValue('S3' , 'FTM')
            ->setCellValue('T3' , 'FECHA DE ENVIO')
            ->setCellValue('U3' , 'RESULTADO')
            ->setCellValue('V3' , 'FECHA RESULTADO')
            ->setCellValue('W3' , 'FECHA ALTA')
            ->setCellValue('X3' , 'OBSERVACIONES')
            ->setTitle("Registro 1")
        ;

        $spreadsheet->getActiveSheet()->mergeCells('A1:X1');

        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                "font" => [ "bold" => true, "color" => ["argb" => "FFFFFF"], "size" => 16, ],
                "alignment" => [ "horizontal" => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, ],
                "borders" => [ "top" => [ "borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, ], ],
                "fill" => [ "fillType" => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR, "rotation" => 90,
                    "startColor" => [ "argb" => "D52B1E", ], "endColor" => [ "argb" => "D52B1E", ], ],
            )

        );

        $spreadsheet->getActiveSheet()->getStyle('A3:X3')->applyFromArray(
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
        $fileName = 'Ingreso coronavirus.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/exportartoximedexcel", name="exportartoximed_excel", methods={"GET"})
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function exportarExcelToximed()
    {
        $con = mysqli_connect("localhost", "root", "", "covid_19");
        if (!$con){
            echo mysqli_error($con);
            exit;
        }
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $query = mysqli_query($con, "
            SELECT
            personal.numero,
            personal.fechaconsulta,
            personal.nombre,
            personal.apellidos,
            personal.edad,
            personal.color_piel,
            sexo.nombresexo,
            personal.ci,
            personal.direccioncarnet,
            area_salud.nombrearea,
            municipio.nombremunicipio,
            provincia.nombreprovincia,
            estado_ingreso.nombreestadoin,
            pais.nombrepais,
            personal.fis,
            personal.fechatomamuestra,
            personal.tiempo,
            tipo_muestra.nombretipomuestra
            FROM
            personal
            INNER JOIN sexo ON personal.sexo_id = sexo.id
            INNER JOIN area_salud ON personal.areasalud_id = area_salud.id AND personal.centroprocemuestra_id = area_salud.id
            LEFT JOIN municipio ON personal.municipio_id = municipio.id AND area_salud.municipio_id = municipio.id
            LEFT JOIN provincia ON personal.provincia_id = provincia.id
            INNER JOIN estado_ingreso ON personal.estadoingreso_id = estado_ingreso.id
            LEFT JOIN pais ON personal.paisprocedencia_id = pais.id AND provincia.pais_id = pais.id
            INNER JOIN tipo_muestra ON personal.tipomuestra_id = tipo_muestra.id    
        ");
        $row = 2;
        while ($data = mysqli_fetch_object($query)){
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$row , $data->numero)
                ->setCellValue('B'.$row , $data->fechaconsulta)
                ->setCellValue('C'.$row , $data->nombre. " " . $data->apellidos)
                ->setCellValue('D'.$row , $data->edad)
                ->setCellValue('E'.$row , $data->tiempo)
                ->setCellValue('f'.$row , $data->nombresexo)
                ->setCellValue('G'.$row , $data->color_piel)
                ->setCellValue('H'.$row , $data->ci)
                ->setCellValue('I'.$row , $data->direccioncarnet)
                ->setCellValue('J'.$row , $data->nombrearea)
                ->setCellValue('K'.$row , $data->nombremunicipio)
                ->setCellValue('L'.$row , $data->nombreprovincia)
                ->setCellValue('M'.$row , $data->nombreestadoin)
                ->setCellValue('N'.$row , $data->nombrepais)
                ->setCellValue('O'.$row , $data->fis)
                ->setCellValue('P'.$row , $data->fechatomamuestra)
                ->setCellValue('X'.$row , $data->nombretipomuestra)
            ;
            $row++;
        }

        //filtrador
        $firstRow=1;
        $lastRow=$row-1;
        $spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.":Z".$lastRow);

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(38);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(28);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(29);
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('Z')->setWidth(40);

        $spreadsheet->getActiveSheet()->getStyle('A1:Z1')->getAlignment()->setHorizontal('center');

        $spreadsheet->getActiveSheet()
            ->setCellValue('A1' , 'CÓDIGO DE LA MUESTRA')
            ->setCellValue('B1' , 'FECHA DE ENTRADA')
            ->setCellValue('C1' , 'NOMBRE y APELLIDOS')
            ->setCellValue('D1' , 'EDAD')
            ->setCellValue('E1' , 'día(s),mes(es) o año(s)')
            ->setCellValue('F1' , 'SEXO')
            ->setCellValue('G1' , 'COLOR DE PIEL')
            ->setCellValue('H1' , 'C. INDENTIDAD / PASAPORTE')
            ->setCellValue('I1' , 'DIRECCION')
            ->setCellValue('J1' , 'AREA DE SALUD')
            ->setCellValue('K1' , 'MUNICIPIO')
            ->setCellValue('L1' , 'PROVINCIA')
            ->setCellValue('M1' , 'CONDICIÓN')
            ->setCellValue('N1' , 'PAIS DE PROCEDENCIA')
            ->setCellValue('O1' , 'FECHA DE INICIO DE SINTOMAS')
            ->setCellValue('P1' , 'FECHA DE TOMA DE MUESTRA')
            ->setCellValue('Q1' , 'Tos')
            ->setCellValue('R1' , 'Dolor de Garganta')
            ->setCellValue('S1' , 'Fiebre')
            ->setCellValue('T1' , 'Rinorrea')
            ->setCellValue('U1' , 'Malestar General')
            ->setCellValue('V1' , 'Diarreas')
            ->setCellValue('W1' , 'Otros')
            ->setCellValue('X1' , 'TIPO DE MUESTRA')
            ->setCellValue('Y1' , 'CENTRO DE PROCEDENCIA DE LA MUESTRA')
            ->setCellValue('Z1' , 'PROVINCIA DE PROCEDENCIA DE LA MUESTRA')
            ->setTitle("Registro 1")
        ;

        $spreadsheet->getActiveSheet()->getStyle('A1:Z1')->applyFromArray(
            array(
                "font" => [ "bold" => true, "color" => ["argb" => "FFFFFF"], "size" => 13, ],
                "borders" => [ "allBorders" => [ "borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    "color" => ["argb" => "897292"], ], ],
                "fill" => [ "fillType" => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    "rotation" => 90, "startColor" => [ "argb" => "ADAFAF", ],
                    "endColor" => [ "argb" => "ADAFAF", ], ],
            )

        );

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'Policlínico Jose Martí.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

}