<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(FCPATH.'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Reportes extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
		$this->load->model("reportes_model");
		$this->load->model("general_model");
		$this->load->helper('form');
    }

	/**
	 * Gestion de Ingresos
	 */
	public function entrances()
	{
		$from = date('Y-m-d');
		$to = date('Y-m-d');
		$arrParam = array(
			'from' => $from . ' 00:00:00',
			'to' => $to . ' 23:59:59'
		);
		$data['listaIngresos'] = $this->reportes_model->get_ingresos($arrParam);
		$data['noIngresosHOY'] = $data['listaIngresos']?count($data['listaIngresos']):0;
		if (date('D')=='Mon'){
		     $lunes = date('Y-m-d');
		} else {
		     $lunes = date('Y-m-d', strtotime('last Monday', time()));
		}
		$domingo = strtotime('next Sunday', time());
		$domingo = date('Y-m-d', $domingo);
		$domingo = date('Y-m-d',strtotime ('+1 day ', strtotime($domingo)));
		$arrParam = array(
			'from' => $lunes,
			'to' => $domingo
		);
		$data['listaIngresosSEMANA'] = $this->reportes_model->get_ingresos($arrParam);
		$data['noIngresosSEMANA'] = $data['listaIngresosSEMANA']?count($data['listaIngresosSEMANA']):0;
		$month_start = strtotime('first day of this month', time());
		$month_start = date('Y-m-d', $month_start);
		$month_end = strtotime('last day of this month', time());
		$month_end = date('Y-m-d', $month_end);
		$month_end = date('Y-m-d',strtotime ('+1 day ', strtotime($month_end)));
		$arrParam = array(
			'from' => $month_start,
			'to' => $month_end
		);
		$data['listaIngresosMES'] = $this->reportes_model->get_ingresos($arrParam);
		$data['noIngresosMES'] = $data['listaIngresosMES']?count($data['listaIngresosMES']):0;
		$data["view"] = "reporte_ingresos";
		$this->load->view("layout_calendar2", $data);
	}

	/**
     * Modal Buscar Ingresos x Fecha
     */
    public function modalIngresosFecha() 
	{
		header("Content-Type: text/plain; charset=utf-8");
		$this->load->view('modal_ingresos_fecha');
    }
	
	/**
	 * Lista de Ingresos x Fecha
	 */
	public function buscarIngresosFecha()
	{
		$data['from'] = $this->input->post('from');
		$data['to'] = $this->input->post('to');
		$arrParam = array(
			'from' => $data['from'] . ' 00:00:00',
			'to' => $data['to'] . ' 23:59:59'
		);
		$data['listaIngresos'] = $this->reportes_model->get_ingresos($arrParam);
		$data["view"] ='buscar_ingresos_fecha';
		$this->load->view("layout_calendar2", $data);
	}

    /**
	 * Generar Reporte Ingresos XLS
	 */
    public function generarReporteIngresosXLS()
	{
		$arrParam = array(
			"table" => "param_sedes",
			"order" => "id_sede",
			"column" => "id_sede",
			"id" => $this->session->userdata("sede")
		);
		$sedes = $this->general_model->get_basic_search($arrParam);
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$nombreArchivo = 'reporte_asistencias_' . $from . '_' . $to . '.xlsx';
		$arrParam = array(
			'from' => $from . ' 00:00:00',
			'to' => $to . ' 23:59:59'
		);
		$listaIngresos = $this->reportes_model->get_ingresos($arrParam);
		$spreadsheet = new Spreadsheet();
		$spreadsheet->setActiveSheetIndex(0);
		$spreadsheet->getActiveSheet()->setTitle('Reporte Asistencias');
		$img1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$img1->setPath('images/alcaldia_logo.png');
		$img1->setCoordinates('A1');
		$img1->setOffsetX(0);
		$img1->setOffsetY(0);
		$img1->setWorksheet($spreadsheet->getActiveSheet());
		$img1->setWidth(600);
		$img1->setHeight(150);
		$img2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$img2->setPath('images/pvd_logo.jpg');
		$img2->setCoordinates('E1');
		$img2->setOffsetX(0);
		$img2->setOffsetY(0);
		$img2->setWorksheet($spreadsheet->getActiveSheet());
		$img1->setWidth(500);
		$img1->setHeight(150);
		$spreadsheet->getActiveSheet()->mergeCells('A1:D1');
		$spreadsheet->getActiveSheet()->mergeCells('E1:H1');
		$spreadsheet->getActiveSheet()->mergeCells('A2:H2');
		$spreadsheet->getActiveSheet(0)
							->setCellValue('A2', 'CONTROL DE ASISTENCIA PUNTO VIVE DIGITAL ' . strtoupper($sedes[0]['nombre_sede']));
		$spreadsheet->getActiveSheet(0)
							->setCellValue('A3', 'N°')
							->setCellValue('B3', 'NOMBRES Y APELLIDOS')
							->setCellValue('C3', 'N° DOCUMENTO')
							->setCellValue('D3', 'FECHA')
							->setCellValue('E3', 'TELÉFONO')
							->setCellValue('F3', 'OCUPACION')
							->setCellValue('G3', 'HORA')
							->setCellValue('H3', 'EDAD');
		$i = 1;
		$j = 4;
		if($listaIngresos){
			foreach ($listaIngresos as $lista):
				$ingreso = explode(' ', $lista['fecha_ingreso']);
                $fecha = $ingreso[0];
                $hora = $ingreso[1];
                $edad = (new DateTime('today'))->diff(new DateTime($lista['fecha_nacimiento']))->y;
				$spreadsheet->getActiveSheet()->getStyle('A'.$j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$spreadsheet->getActiveSheet()->getStyle('D'.$j.':E'.$j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$spreadsheet->getActiveSheet()->getStyle('G'.$j.':H'.$j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$spreadsheet->getActiveSheet()
							->setCellValue('A'.$j, $i)
							->setCellValue('B'.$j, $lista['nombres'] . ' ' . $lista['apellidos'])
							->setCellValue('C'.$j, $lista['numero_documento'])
							->setCellValue('D'.$j, $fecha)
							->setCellValue('E'.$j, $lista['telefono'])
							->setCellValue('F'.$j, $lista['ocupacion'])
							->setCellValue('G'.$j, $hora)
							->setCellValue('H'.$j, $edad);
				$i++;
				$j++;
			endforeach;
		}
		// Set column widths
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		// Set fonts
		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFont()->setSize(14);
		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFont()->setBold(true);
 		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFill()->setFillType(Fill::FILL_SOLID);
 		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFill()->getStartColor()->setARGB('808080');
 		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);
 		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFill()->setFillType(Fill::FILL_SOLID);
 		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFill()->getStartColor()->setARGB('808080');
 		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(150);
		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);
		$spreadsheet->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
		    [
			    'alignment' => [
			        'wrapText' => TRUE
			    ]
		    ]
		);
		$spreadsheet->getActiveSheet()->setSelectedCell('A2');
		ob_end_clean();
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename=' . $nombreArchivo);
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}

    /**
     * Generar Reporte Ingresos PDF
     */
    public function generarReporteIngresosPDF()
    {
        $arrParam = array(
            "table" => "param_sedes",
            "order" => "id_sede",
            "column" => "id_sede",
            "id" => $this->session->userdata("sede")
        );
        $sedes = $this->general_model->get_basic_search($arrParam);
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $nombreArchivo = 'reporte_asistencias_' . $from . '_' . $to . '.pdf';
        $arrParam = array(
            'from' => $from . ' 00:00:00',
            'to' => $to . ' 23:59:59'
        );
        $listaIngresos = $this->reportes_model->get_ingresos($arrParam);
        $this->load->library('Pdf');
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetMargins(10, 0.01);
        $pdf->SetAutoPageBreak(FALSE, 10);
        $pdf->SetHeaderData(false);
        $pdf->SetFooterData(false);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->AddPage();
        $generarEncabezado = function() use ($sedes) {
            $html = '';
            $responsable = 
            $html .= '<div style="text-align: right; font-size: 10px; font-weight:bold;">RESPONSABLE: ' . strtoupper($this->session->userdata("name")) . '</div>';
            $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse; font-size: 10px; border-color: #000;">';
            $html .= '<thead>';
            $html .= '<tr style="background-color:#808080; color:white; text-align:center; border-bottom: 1px solid #000;">';
            $html .= '<th colspan="8" style="padding: 10px; font-size: 12px; font-weight:bold;">CONTROL DE ASISTENCIA PUNTO VIVE DIGITAL ' . strtoupper($sedes[0]['nombre_sede']) . '</th>';
            $html .= '</tr>';
            $html .= '<tr style="background-color:#808080; color:white; text-align:center; border-bottom: 1px solid #000;">';
            $html .= '<th style="width:5%; border: 1px solid #000;">N°</th>';
            $html .= '<th style="width:25%; border: 1px solid #000;">NOMBRES Y APELLIDOS</th>';
            $html .= '<th style="width:15%; border: 1px solid #000;">N° DOCUMENTO</th>';
            $html .= '<th style="width:10%; border: 1px solid #000;">FECHA</th>';
            $html .= '<th style="width:10%; border: 1px solid #000;">TELÉFONO</th>';
            $html .= '<th style="width:15%; border: 1px solid #000;">OCUPACION</th>';
            $html .= '<th style="width:10%; border: 1px solid #000;">HORA</th>';
            $html .= '<th style="width:10%; border: 1px solid #000;">EDAD</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            return $html;
        };
        $generarFila = function($lista, $i) {
            $ingreso = explode(' ', $lista['fecha_ingreso']);
            $fecha = $ingreso[0];
            $hora = $ingreso[1];
            $edad = (new DateTime('today'))->diff(new DateTime($lista['fecha_nacimiento']))->y;
            $html = '<tr style="text-align:center; border-bottom: 1px solid #000;">';
            $html .= '<td style="width:5%; border: 1px solid #000;">' . $i . '</td>';
            $html .= '<td style="width:25%; text-align:left; border: 1px solid #000;">' . $lista['nombres'] . ' ' . $lista['apellidos'] . '</td>';
            $html .= '<td style="width:15%; text-align:right; border: 1px solid #000;">' . $lista['numero_documento'] . '</td>';
            $html .= '<td style="width:10%; border: 1px solid #000;">' . $fecha . '</td>';
            $html .= '<td style="width:10%; border: 1px solid #000;">' . $lista['telefono'] . '</td>';
            $html .= '<td style="width:15%; text-align:left; border: 1px solid #000;">' . $lista['ocupacion'] . '</td>';
            $html .= '<td style="width:10%; border: 1px solid #000;">' . $hora . '</td>';
            $html .= '<td style="width:10%; border: 1px solid #000;">' . $edad . '</td>';
            $html .= '</tr>';
            return $html;
        };
        $registrosPorPagina = 20;
        $i = 1;
        $totalRegistros = count($listaIngresos);
        $contadorPagina = 0;
        if($listaIngresos){
            foreach ($listaIngresos as $lista):
                if($contadorPagina == 0){
                    $imgPath1 = FCPATH . 'images/alcaldia_logo.png'; 
                    if(file_exists($imgPath1)){
                        $pdf->Image($imgPath1, 10, 5, 100, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    $imgPath2 = FCPATH . 'images/pvd_logo.jpg';
                    if(file_exists($imgPath2)){
                        $pdf->Image($imgPath2, 200, 5, 100, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    $pdf->SetY(25);
                    $html = $generarEncabezado();
                }
                $html .= $generarFila($lista, $i);
                $i++;
                $contadorPagina++;
                if($contadorPagina == $registrosPorPagina){
                    $html .= '</tbody>';
                    $html .= '</table>';
                    $pdf->writeHTML($html, true, false, true, false, '');
                    if($i <= $totalRegistros){
                        $pdf->AddPage();
                    }
                    $contadorPagina = 0;
                }
            endforeach;
            if($contadorPagina > 0){
                $html .= '</tbody>';
                $html .= '</table>';
                $pdf->writeHTML($html, true, false, true, false, '');
            }
        }
        ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        $pdf->Output($nombreArchivo, 'D');
        exit;
    }

    /**
	 * Generar Reporte Inventario XLS
	 */
    public function inventory()
	{
		$nombreArchivo = 'reporte_inventario.xlsx';
		$idUser = $this->session->userdata("id");
		$arrParam = array(
			"idUser" => $idUser
		);
		$listaInventario = $this->reportes_model->get_inventario($arrParam);
		$spreadsheet = new Spreadsheet();
		$spreadsheet->setActiveSheetIndex(0);
		$spreadsheet->getActiveSheet()->setTitle('Reporte Inventario');
		$img1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$img1->setPath('images/alcaldia_logo.png');
		$img1->setCoordinates('A1');
		$img1->setOffsetX(0);
		$img1->setOffsetY(0);
		$img1->setWorksheet($spreadsheet->getActiveSheet());
		$img1->setWidth(600);
		$img1->setHeight(150);
		$img2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$img2->setPath('images/pvd_logo.jpg');
		$img2->setCoordinates('E1');
		$img2->setOffsetX(0);
		$img2->setOffsetY(0);
		$img2->setWorksheet($spreadsheet->getActiveSheet());
		$img1->setWidth(500);
		$img1->setHeight(150);
		$spreadsheet->getActiveSheet()->mergeCells('A1:D1');
		$spreadsheet->getActiveSheet()->mergeCells('E1:H1');
		$spreadsheet->getActiveSheet()->mergeCells('A2:H2');
		$spreadsheet->getActiveSheet(0)
							->setCellValue('A2', 'INVENTARIO: ' . strtoupper($listaInventario[0]['first_name'] . ' ' . $listaInventario[0]['last_name']));
		$spreadsheet->getActiveSheet(0)
							->setCellValue('A3', 'ELEMENTO')
							->setCellValue('B3', 'DESCRIPCIÓN')
							->setCellValue('C3', 'MARCA')
							->setCellValue('D3', 'PLACA')
							->setCellValue('E3', 'FECHA INGRESO')
							->setCellValue('F3', 'FECHA SERVICIO')
							->setCellValue('G3', 'VALOR')
							->setCellValue('H3', 'ESTADO');
		$i = 1;
		$j = 4;
		if($listaInventario){
			foreach ($listaInventario as $lista):
				$spreadsheet->getActiveSheet()->getStyle('H'.$j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$spreadsheet->getActiveSheet()->getStyle('E'.$j.':F'.$j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$spreadsheet->getActiveSheet()
							->setCellValue('A'.$j, $lista['elemento'])
							->setCellValue('B'.$j, $lista['descripcion'])
							->setCellValue('C'.$j, $lista['marca'])
							->setCellValue('D'.$j, $lista['placa'])
							->setCellValue('E'.$j, $lista['fecha_ingreso'])
							->setCellValue('F'.$j, $lista['fecha_servicio'])
							->setCellValue('G'.$j, $lista['valor'])
							->setCellValue('H'.$j, $lista['estado']);
				$i++;
				$j++;
			endforeach;
		}
		$spreadsheet->getActiveSheet()->getStyle('G4:G' . ($j-1))->getNumberFormat()
        ->setFormatCode('$ #,##0');
		// Set column widths
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		// Set fonts
		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFont()->setSize(14);
		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFont()->setBold(true);
 		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFill()->setFillType(Fill::FILL_SOLID);
 		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFill()->getStartColor()->setARGB('808080');
 		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);
 		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFill()->setFillType(Fill::FILL_SOLID);
 		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFill()->getStartColor()->setARGB('808080');
 		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(150);
		$spreadsheet->getActiveSheet()->getStyle('A2:H2')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);
		$spreadsheet->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
		    [
			    'alignment' => [
			        'wrapText' => TRUE
			    ]
		    ]
		);
		$spreadsheet->getActiveSheet()->setSelectedCell('A2');
		ob_end_clean();
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename=' . $nombreArchivo);
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}
}