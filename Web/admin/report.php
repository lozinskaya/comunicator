<?php
require_once '../init.php';
require_once INCLUDE_PATH . 'PHPExcel.php';
require_once INCLUDE_PATH . 'PHPExcel/IOFactory.php';

$sessions = $mdb->get_results("SELECT * FROM `{$mdb->sessions}` WHERE `exit_time` IS NOT NULL");

$cdate = date('d-m-Y H.i', time());
$fileName = 'Сенсы «Коммуникатор»  от ' . $cdate . '.xls';

$document = new PHPExcel();

$sheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе

$data = [];

$data[] = array(
    'ID',
    'Тариф, ₽ / мин',
    'Кол-во человек',
    'Дата и время',
    'Длительность, мин',
    'Имя клиента',
    'Сумма, ₽'
);

foreach($sessions as $session) { 
    $tariff = get_tariff($session->tariff_id);
    $user = get_user(array("id" => $session->user_id));
    $minutes = ceil(($session->exit_time - $session->enter_time) / 60);
    $data[] = array(
        $session->id,
        $tariff->price,
        $session->count,
        date('d.m.Y G:i', $session->enter_time),
        $minutes,
        $user->firstname . ' ' . $user->surname,
        $session->sum
    );
}

$sheet->fromArray($data, NULL, 'A1');
$sheet->getColumnDimension('A')->setWidth("20");
$sheet->getColumnDimension('B')->setWidth("20");
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