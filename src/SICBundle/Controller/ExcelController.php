<?php

namespace SICBundle\Controller;

use SICBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class ExcelController extends BaseController
{
    public function indexAction()
    {
        // PHPExcel fue abandonado. Pendiente migrar a PhpSpreadsheet (phpoffice/phpspreadsheet).
        return new Response('La exportación a Excel no está disponible en esta versión.', Response::HTTP_NOT_IMPLEMENTED);
    }
}
