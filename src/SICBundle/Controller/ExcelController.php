<?php

namespace SICBundle\Controller;

use SICBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SICBundle\Entity\Bitacora;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
// use PHPExcel_Writer_OOCalc;

class ExcelController extends BaseController
{
	public function indexAction()
	{

		/**************** SECCION DE ESTILOS ****************/
		$centrar_texto = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
		$relleno_gris = array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'DDDDDD')));
		$border_thin = array('borders' => array('allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN)));
		/**************** SECCION DE ESTILOS ****************/

		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

		$phpExcelObject->getProperties()->setCreator("liuggio")
		   ->setLastModifiedBy("Giulio De Donato")
		   ->setTitle("Office 2005 XLSX Test Document")
		   ->setSubject("Office 2005 XLSX Test Document")
		   ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
		   ->setKeywords("office 2005 openxml php")
		   ->setCategory("Test result file");

		/****************************/
		/* INSTRUCCIONES DE LLENADO */
		/****************************/
		$phpExcelObject->getActiveSheet()->setTitle('Instrucciones');
		$sheet = $phpExcelObject->getActiveSheet();
		$sheet->setCellValue('A1', 'INSTRUCCIONES DE LLENADO')
		   ->setCellValue('A3', '1. Escriba/Selecciones sus respuestas en la celda de color gris. Como la que se muestra a la derecha')
		   ->setCellValue('A4', '2. No hay un orden de llenado, pero hagalo siguiendo la numeración')
		   ->setCellValue('A5', '3. Tomesé su tiempo, nadie lo está apurando')
		   ->setCellValue('A6', '4. Siempre que termine una sección guarde el archivo ;)')
		   ->setCellValue('B3', '');
		
		/*Titulo*/
		$sheet->getStyle('A1')->getFont()->setBold(true);

		// Centrar Texto
		$sheet->getStyle('A1')->applyFromArray($centrar_texto);

		$sheet->getColumnDimension('A')->setWidth(100);
		$sheet->getColumnDimension('B')->setWidth(20);

		$sheet->getStyle('B3')->applyFromArray($relleno_gris);
		$sheet->getStyle('B3')->applyFromArray($border_thin);
		
		/* AGREGAR NUEVA HOJA */
		$phpExcelObject->createSheet();
		$phpExcelObject->setActiveSheetIndex(1);
		$sheet = $phpExcelObject->getActiveSheet();

		$sheet->setTitle('PARTE 1');
		$sheet
		   ->setCellValue('A1', 'I. DATOS PERSONALES DEL JEFE GRUPO FAMILIAR')
		   ->setCellValue('A2', 'Nombres')
		   ->setCellValue('A3', 'Apellidos');

		/** Customizando los elementos **/
		$sheet->getStyle('B2')->applyFromArray($relleno_gris);
		$sheet->getStyle('B2')->applyFromArray($border_thin);
		$sheet->getStyle('B3')->applyFromArray($relleno_gris);
		$sheet->getStyle('B3')->applyFromArray($border_thin);

		// Poner negritas a una celda
		$sheet->getStyle('A1')->getFont()->setBold(true);

		// Cambiar el ancho de una Columna
		$sheet->getColumnDimension('A')->setWidth(50);
		$sheet->getColumnDimension('B')->setWidth(50);

		// $sheet->getRowDimension(1)->setRowHeight(-1);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$phpExcelObject->setActiveSheetIndex(0);

		// create the writer
		$writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
		// $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'OOCalc');
		// create the response
		$response = $this->get('phpexcel')->createStreamedResponse($writer);
		// adding headers
		$dispositionHeader = $response->headers->makeDisposition(
		    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
		    'stream-file.xls'
		);
		$response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
		$response->headers->set('Pragma', 'public');
		$response->headers->set('Cache-Control', 'maxage=1');
		$response->headers->set('Content-Disposition', $dispositionHeader);

		return $response; 
	}
}