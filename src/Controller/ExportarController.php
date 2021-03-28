<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Filter\PersonalFilterType;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
// Incluir namespaces requeridos de PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Bundle\SnappyBundle\Snappy\Response\SnappyResponse;
use Knp\Bundle\SnappyBundle\Snappy\Response\JpegResponse;
use Knp\Snappy\Pdf;
use Knp\Snappy\Image;

/**
 * @Route("/user")
 */
class ExportarController extends AbstractController
{
    /**
     * @Route("/consultas", name="index_consultas")
     */
    public function consultas()
    {
        return $this->render('consulta/index.html.twig');
    }
}