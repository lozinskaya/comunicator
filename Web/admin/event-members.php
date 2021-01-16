<?php
require_once '../init.php';
$event_id = intval($_REQUEST["event_id"]);
if(!$event_id)
    die("Choose event");
require_once INCLUDE_PATH . 'PHPExcel.php';
require_once INCLUDE_PATH . 'PHPExcel/IOFactory.php';

$regs = $mdb->get_results("SELECT * FROM `{$mdb->events_regs}` JOIN `{$mdb->users}` ON  `{$mdb->events_regs}`.`user_id` = `{$mdb->users}`.`id`  WHERE `event_id` = '{$event_id}'");

$cdate = date('d-m-Y H.i', time());
$fileName = 'Регистрации на мероприятие  от ' . $cdate . '.xls';

$document = new PHPExcel();

$sheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе

$data = [];

$data[] = array(
    'Имя, фамилия',
    'E-mail',
    'Кол-во человек',
    'Дата регистрации',
);

foreach($regs as $reg) { 
    $data[] = array(
        $reg->firstname . ' ' . $reg->surname,
        $reg->email,
        $reg->count,
        date('d.m.Y G:i', $reg->create_date),
    );
}

$sheet->fromArray($data, NULL, 'A1');
$sheet->getColumnDimension('A')->setWidth("45");
$sheet->getColumnDimension('B')->setWidth("45");
$sheet->getColumnDimension('C')->setWidth("20");
$sheet->getColumnDimension('D')->setWidth("45");
$sheet->getColumnDimension('E')->setWidth("20");
$sheet->getColumnDimension('F')->setWidth("45");
$sheet->getColumnDimension('G')->setWidth("20");

$objWriter = PHPExcel_IOFactory::createWriter($document, 'Excel5');

header('Content-type: application/vnd.ms-excel');

header('Content-Disposition: attachment; filename="'.$fileName.'"');

$objWriter->save('php://output');
?>