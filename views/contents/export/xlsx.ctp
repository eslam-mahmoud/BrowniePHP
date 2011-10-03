<?php
include ROOT . DS . 'app' . DS . 'libs' . DS . 'PHPExcel' . DS . 'PHPExcel.php';
include ROOT . DS . 'app' . DS . 'libs' . DS . 'PHPExcel' . DS . 'PHPExcel' . DS . 'Writer' . DS . 'Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

foreach ($brwConfig['fields']['export'] as $col => $field) {
	$fieldName = $field;
	if (!empty($brwConfig['fields']['names'][$field])) {
		$fieldName = $brwConfig['fields']['names'][$field];
	} else {
		$tmp = explode('.', $field); $relModel = $tmp[0]; $relField = $tmp[1];
		if (!empty($relatedBrwConfig[$relModel])) {
			$fieldName = $relatedBrwConfig[$relModel]['names']['singular'] . ' ' .
			$relatedBrwConfig[$relModel]['fields']['names'][$relField];
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $fieldName);
}
foreach ($records as $row => $record) {
	foreach ($brwConfig['fields']['export'] as $col => $field) {
		if (strstr($field, '.')) {
			$tmp = explode('.', $field);
			$value = $record[$tmp[0]][$tmp[1]];
		} else {
			$value = $record[$model][$field];
		}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row + 2, $value);
	}
	reset($brwConfig['fields']['export']);
}

/*$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('A1', 'Hello');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('B2', 'world!');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('C1', 'Hello');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('D2', 'world!');*/


$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');