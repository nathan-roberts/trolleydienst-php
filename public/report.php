<?php
$placeholder = require '../includes/init_page.php';
$report_to = new \DateTime('NOW');
$report_from = clone $report_to;
$report_from->sub(new DateInterval('P1M'));
$id_shift_type = (isset($_POST['id_shift_type']))? (int)$_POST['id_shift_type'] : App\Tables\ShiftTypes::select_first_id_shift_type($database_pdo);

$placeholder['report_list'] = array();
$placeholder['shifttype_list'] = array();

if(isset($_GET['id_report'])) {
	$id_report = (int)$_GET['id_report'];
	if(App\Tables\Reports::delete($database_pdo, $id_report))
		$placeholder['message']['success'] = __('Your report has been deleted.');
	else
		$placeholder['message']['error'] = __('Your report could not be deleted!');
}

if($id_shift_type > 0) {
	if(isset($_POST['filter']) && isset($_POST['report_from']) && isset($_POST['report_to'])) {
		$report_from = new \DateTime($_POST['report_from']);
		$report_to = new \DateTime($_POST['report_to']);
	}

	$placeholder['report_from'] = $report_from->format('Y-m-d');
	$placeholder['report_to'] = $report_to->format('Y-m-d');

	$get_reports = include '../services/get_reports.php';
	$name = ($_SESSION['is_admin'])? '' : $_SESSION['name'];
	$placeholder['report_list'] = $get_reports($database_pdo, $report_from, $report_to, $id_shift_type, $name);
	$placeholder['shifttype_list'] = App\Tables\ShiftTypes::select_all($database_pdo);
}

$render_page = include '../includes/render_page.php';
echo $render_page($placeholder);