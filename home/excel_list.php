<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

//need_manager();
//error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '128M');
date_default_timezone_set('Asia/Shanghai');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once(dirname(dirname(__FILE__)) . '/system/excel/PHPExcel.php');

$calltime = time();
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("easyscm.com")
        ->setLastModifiedBy("easyscm.com");

$col_num = count($titles['th']);
$b = chr(ord('A') + $col_num);
//Set Style
$objPHPExcel->setActiveSheetIndex(0)->getDefaultStyle()->getFont()->setName('宋体');
$objPHPExcel->setActiveSheetIndex(0)->getDefaultStyle()->getFont()->setSize(12);
$objPHPExcel->setActiveSheetIndex(0)->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)->getDefaultRowDimension()->setRowHeight(20);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('1:2')->getFont()->setBold(true);
//$objPHPExcel->setActiveSheetIndex(0)->getStyle('1:2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->setActiveSheetIndex(0)->getStyle('A:' . $b)->getFont()->setName('宋体');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A:' . $b)->getNumberFormat()->setFormatCode('@');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('1')->getFont()->setSize(16);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight(20);


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $titles['head'] . '_' . date("Ymd", time()) . '（共' . count($data) . '条）');

$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN, //设置border样式
            //'style' => PHPExcel_Style_Border::BORDER_THICK,  另一种样式
            'color' => array('argb' => 'FF000000'), //设置border颜色
        ),
    ),
);


$a = "A";
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($a)->setWidth(6);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($a . '2', '序号');
$objPHPExcel->setActiveSheetIndex(0)->getStyle($a . '2')->applyFromArray($styleThinBlackBorderOutline);
$a++;
foreach ($titles['th'] as $title) {
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($a)->setWidth($title['width']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($a . '2', $title['name']);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($a . '2')->applyFromArray($styleThinBlackBorderOutline);
    $a++;
}


foreach ($data as $key => $one) {

    $objPHPExcel->setActiveSheetIndex(0)->getRowDimension($key + 3)->setRowHeight(20);
    $a = "A";
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($a . ($key + 3), $key + 1);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($a . ($key + 3))->applyFromArray($styleThinBlackBorderOutline);
    $a++;

    foreach ($titles['th'] as $title) {

        if (isset($title['style'])) {
            if (array_key_exists('backagecolor', $title['style'])) {
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($a . ($key + 3))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($a . ($key + 3))->getFill()->getStartColor()->setARGB($one[$title['style']['backagecolor']]);
            }
            if (array_key_exists('image', $title['style']) && file_exists($one[$title['style']['image']]) && is_file($one[$title['style']['image']])) {
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($one[$title['style']['image']]); //图片引入位置
                $objDrawing->setCoordinates($a . ($key + 3)); //图片添加位置
                $objDrawing->setHeight($title['style']['height']); //照片高度
                $objDrawing->setWidth($title['style']['width']); //照片宽度
                $objDrawing->setOffsetX(5);
                $objDrawing->setOffsetY(1);
                $objDrawing->setRotation(0);
//                $objDrawing->getShadow()->setVisible(true);
//                $objDrawing->getShadow()->setDirection(50);
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
            }
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($a . ($key + 3), $one[$title['value']]);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($a . ($key + 3))->applyFromArray($styleThinBlackBorderOutline);
        $a++;
    }
    if (time() - $calltime > 25) {
        showmessage('error', '', '数据量过大，网络超时，请分多次导出');
        Utility::Redirect();
    }
    if (memory_get_usage(true) / 1024 / 1024 > 127) {
        showmessage('error', '', '内存占用过大，请分多次导出');
        Utility::Redirect();
    }
}

// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:' . $b . '1');



// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
//header('Content-Type: application/vnd.ms-excel');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');  //excel2007
header('Content-Disposition: attachment;filename=' . iconv('utf-8', 'gbk', $titles['head'] . '_' . date("Ymd", time()) . ".xlsx"));
//header('Content-Disposition: attachment;filename=' . ($titles['head'] . '_' . date("Ymd", time()) . ".xls"));
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); //excel2007
$objWriter->save('php://output');
exit;
?>