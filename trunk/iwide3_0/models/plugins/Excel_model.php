<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Excel_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	function exp_exl($head, $data, $filename) {
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		if (empty ( $filename ))
			$filename = date ( 'YmdHis' );
		if (empty ( $data ))
			$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', iconv ( 'gbk', 'utf-8', '没有更多信息' ) )->setCellValue ( 'B2', '没有更多信息!' )->setCellValue ( 'C1', 'Hello' );
		$i = 0;
		foreach ( $head as $h ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $i, 1, $h );
			$i ++;
		}
		$row = 2;
		foreach ( $data as $d ) {
			$i = 0;
			foreach ( $d as $e ) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $i, $row, $e );
				$i ++;
			}
			$row ++;
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . $filename . ".xls" );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	}
	
	/**
	 * 导出多工作簿excel文件
	 *
	 * @param array $heads
	 *        	各工作簿表头数组,下标为 0,1,2...
	 * @param array $datas
	 *        	各工作簿数据数组,下标与表头对应
	 * @param string $filename
	 *        	excel文件名
	 * @param array $sheet_names
	 *        	工作簿名数组,下标与表头对应
	 */
	function exp_exl_multisheet($heads, $datas, $filename = '', $sheet_names = array()) {
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		if (empty ( $filename ))
			$filename = date ( 'YmdHis' );
		if (empty ( $heads ))
			$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', iconv ( 'gbk', 'utf-8', '没有更多信息' ) )->setCellValue ( 'B2', '没有更多信息!' )->setCellValue ( 'C1', 'Hello' );
		$i = 0;
		foreach ( $heads as $h ) {
			if ($i != 0)
				$objPHPExcel->createSheet ();
			$objPHPExcel->setactivesheetindex ( $i );
			$m = 0;
			if (! empty ( $sheet_names [$i] ))
				$objPHPExcel->getActiveSheet ()->setTitle ( $sheet_names [$i] );
			foreach ( $h as $hd ) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, 1, $hd );
				$m ++;
			}
			$row = 2;
			foreach ( $datas [$i] as $d ) {
				$j = 0;
				foreach ( $d as $e ) {
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $j, $row, $e );
					$j ++;
				}
				$row ++;
			}
			$i ++;
		}
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . $filename . ".xls" );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	}
	function load_exl($file_path) {
		$type = strtolower ( pathinfo ( $file_path, PATHINFO_EXTENSION ) );
		if (! file_exists ( $file_path )) {
			return FALSE;
		}
		// 根据不同类型分别操作
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		if ($type == 'xlsx' || $type == 'xls') {
			$objPHPExcel = IOFactory::load ( $file_path );
		} else if ($type == 'csv') { // 不设置将导致中文列内容返回boolean(false)或乱码
			$objReader = IOFactory::createReader ( 'CSV' )->setDelimiter ( ',' )->setInputEncoding ( 'GBK' )->setEnclosure ( '"' )->setLineEnding ( "\r\n" )->setSheetIndex ( 0 );
			$objPHPExcel = $objReader->load ( $file_path );
		} else {
			return FALSE;
		}
		return $objPHPExcel;
	}

	function re_purchase($head, $data, $filename){
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$tpl = IOFactory::load($head);
	    $target = clone $tpl;
	    $startrow = 0;

		$sheet = $tpl->getActiveSheet();
		$i = 0;
		foreach($sheet->getRowDimensions() as $y=>$row) {
		  foreach($sheet->getColumnDimensions($row) as $x=>$col) {
		    $txt = trim($sheet->getCell($x.$y)->getValue());
		    $h = $y + $startrow;
		    $target->getActiveSheet()->getCell("$x$h")->setValue($txt);
		    $target->getActiveSheet()->duplicateStyle($sheet->getStyle("$x$y"), "$x$h");
		  }
		}
		$row = 4;
		foreach ( $data as $d ) {
			$i = 0;
			foreach ( $d as $e ) {
				$target->getActiveSheet ()->setCellValueByColumnAndRow ( $i, $row, $e );
				$i ++;
			}
			$row ++;
		}

	    $t = IOFactory::createWriter($target, 'Excel5');
	    header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . $filename . ".xls" );
		header ( 'Cache-Control: max-age=0' );
		$t->save ( 'php://output' );
	}
 
}