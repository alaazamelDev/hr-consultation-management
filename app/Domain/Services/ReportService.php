<?php

namespace App\Domain\Services;

use App\Domain\Models\Employee;
use App\Domain\Models\ShiftRequest;
use App\Infrastructure\Persistence\Eloquent\EloquentAbsenceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentAttendanceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEmployeeRepository;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\NoReturn;
use setasign\Fpdi\Tcpdf\Fpdi;

class ReportService
{
    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     */
    #[NoReturn] function create(): void
    {
        $attendance_report = null;
        $absence_report = null;
        $staffing_report = null;

        $info_report = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $info_report = $this->employeeInformationReport($info_report,request()->all());

        if(request()->get('attendance_report') == "true") {
            $attendance_report = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $attendance_report = $this->employeeAttendanceReport($attendance_report,request()->all());
        }

        if(request()->get('absence_report')  == "true") {
            $absence_report = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $absence_report = $this->employeeAbsenceReport($absence_report,request()->all());
        }

        if(request()->get('staffing_report') == "true") {
            $staffing_report = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $staffing_report = $this->employeeStaffingReport($staffing_report,request()->all());
        }

        $info_report->Output(storage_path('app/Reports/info.pdf'),'F');

        $attendance_report?->Output(storage_path('app/Reports/att.pdf'), 'F');

        $absence_report?->Output(storage_path('app/Reports/absence.pdf'), 'F');

        $staffing_report?->Output(storage_path('app/Reports/staffing.pdf'), 'F');

        $mergedPdf = new Fpdi();

        $pageCount = $mergedPdf->setSourceFile(storage_path('app/Reports/info.pdf'));
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $template = $mergedPdf->importPage($pageNo);
            $size = $mergedPdf->getTemplateSize($template);
            $mergedPdf->AddPage($size['orientation'], $size);
            $mergedPdf->useTemplate($template);
        }

        if($staffing_report) {
            $pageCount = $mergedPdf->setSourceFile(storage_path('app/Reports/staffing.pdf'));
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $template = $mergedPdf->importPage($pageNo);
                $size = $mergedPdf->getTemplateSize($template);
                $mergedPdf->AddPage($size['orientation'], $size);
                $mergedPdf->useTemplate($template);
            }
        }

        if($attendance_report) {
            $pageCount = $mergedPdf->setSourceFile(storage_path('app/Reports/att.pdf'));
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $template = $mergedPdf->importPage($pageNo);
                $size = $mergedPdf->getTemplateSize($template);
                $mergedPdf->AddPage($size['orientation'], $size);
                $mergedPdf->useTemplate($template);
            }
        }

        if($absence_report) {
            $pageCount = $mergedPdf->setSourceFile(storage_path('app/Reports/absence.pdf'));
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $template = $mergedPdf->importPage($pageNo);
                $size = $mergedPdf->getTemplateSize($template);
                $mergedPdf->AddPage($size['orientation'], $size);
                $mergedPdf->useTemplate($template);
            }
        }


        $emp_id = request()->get('emp_id');
        $timestamp = date('Y-m-d_H-i-s');
        $fileName = "Employee_Report_{$emp_id}_{$timestamp}.pdf";
        $filePath = storage_path("app/Reports/{$fileName}");
        $mergedPdf->Output($filePath, "F");

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);

        exit;
    }
    function employeeAbsenceReport(\TCPDF $pdf,$data): \TCPDF
    {
        $absenceService = new AbsenceService(new EloquentAbsenceRepository());

        $employee_absences = $absenceService->getEmployeeAbsences($data['emp_id']);

        if(!(!isset($data['absence_start_date']) && !isset($data['absence_end_date']))) {
            $employee_absences = $employee_absences->filter(function ($absence) use ($data) {
                $absenceDate = $absence->absence_date;
                if (isset($data['absence_start_date']) && !isset($data['absence_end_date'])) {
                    return $absenceDate >= $data['absence_start_date'];
                } elseif (!isset($data['absence_start_date']) && isset($data['absence_end_date'])) {
                    return $absenceDate <= $data['absence_end_date'];
                }

                return $absenceDate >= $data['absence_start_date'] && $absenceDate <= $data['absence_end_date'];
            });
        }

        $absenceDates = $employee_absences->pluck('absence_date');
        $absenceStatus = $employee_absences->pluck('absence_status_id');

        $pdf->AddPage();

        $pdf->setRTL(true);
        $pdf->SetFont('aealarabiya', '', 14);

        $pdf->Text(4,20," تاريخ التقرير:");
        $pdf->Text(27,20,now()->format('Y-m-d'));

        $pdf->Text(4,40," اسم الموظف:");
        $pdf->Text(28,40,"انس خالد درة");

        $pdf->Text(4,30," رقم الموظف:");
        $pdf->Text(27,30,$data['emp_id']);

        $pdf->Text(88,57,"تقرير غياب الموظف");

        $now = now()->format('Y-m-d');
        if(isset($data['absence_start_date']) && isset($data['absence_end_date'])) $pdf->Text(56,67,"إبتداءاً من تاريخ {$data['absence_start_date']} حتى تاريخ {$data['absence_end_date']}");
        if(isset($data['absence_start_date']) && !isset($data['absence_end_date'])) $pdf->Text(56,67,"إبتداءاً من تاريخ {$data['absence_start_date']} حتى تاريخ {$now}");
        if(!isset($data['absence_start_date']) && isset($data['absence_end_date'])) $pdf->Text(60,67,"إبتداءاً من تاريخ بداية عمل الموظف حتى تاريخ {$data['absence_end_date']}");
        if(!isset($data['absence_start_date']) && !isset($data['absence_end_date'])) $pdf->Text(60,67,"إبتداءاً من تاريخ عمل الموظف حتى تاريخ {$now}");

        $pdf->setRTL(false);

        $pdf->Image(public_path('qiam.jpg'), 85, 13, 40, 40);

        $header = array('التاريخ', 'الحالة','الرقم');
        $pdf->setCellPaddings(5, 3, 5, 3);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 123, 255);


        $pdf->SetXY(78, 79);
        // Set table header row
        $pdf->MultiCell(40, 10, $header[0], 1, 'C', true);
        // (width, height, text, border (0 or 1), alignment (L, R, C, J), fill (true or false))
        $pdf->SetXY(118, 79); // Set the position for the next cell
        $pdf->MultiCell(40, 10, $header[1], 1, 'C', true);

        $pdf->SetXY(58, 79);
        // Set table header row
        $pdf->MultiCell(20, 10, $header[2], 1, 'C', true);

        // Set table content font color (RGB values)
        $pdf->SetTextColor(0, 0, 0);

        // Set table content background color (RGB values)
        $pdf->SetFillColor(255, 255, 255);

        // Set initial table y-position
        $y = 90;
        $j=1;
        // Loop through absence dates and add table rows
        for($i=0 ; $i<sizeof($absenceDates) ; $i++) {

            if ($absenceStatus[$i] == 1) {
                $status = "مبرر";
                $justified = false;
            } else {
                $status = "غير مبرر";
                $justified = true;
            }

            if ($y + 10 > $pdf->getPageHeight() - $pdf->getMargins()['bottom']) {
                $pdf->AddPage(); // Jump to a new page
                $y = $pdf->getY() + 5; // Update the y-position for the new page
            }

            $pdf->SetXY(78, $y); // Set the position for the next cell

            if ($justified) {
                $pdf->SetTextColor(204, 0, 0);
            }
            else
                $pdf->SetTextColor(0, 102, 0);

            $pdf->MultiCell(40, 10, $absenceDates[$i], 1, 'C', true);


            // Reset color
            $pdf->SetTextColor(0, 0, 0);

            $pdf->SetXY(118, $y);
            $pdf->MultiCell(40, 10, $status, 1, 'C', true);


            $pdf->SetXY(58, $y);
            $pdf->MultiCell(20, 10,$j++, 1, 'C', true);


            // Update the y-position for the next row
            $y += 10;
        }

        if ($y + 20 > $pdf->getPageHeight() - $pdf->getMargins()['bottom']) {
            $pdf->AddPage(); // Jump to a new page
            $y = $pdf->getY() +5; // Update the y-position for the new page
        }

        $justifiedCount = $absenceStatus->filter(function ($status) {
            return $status == 1;
        })->count();

        $unjustifiedCount = $absenceStatus->filter(function ($status) {
            return $status == 2;
        })->count();

        $y = $y +3;
        $pdf->setRTL(true);

        $pdf->Text(80, $y, 'عدد الغيابات المبررة : ' . $justifiedCount);


        $y = $y +9;

        $pdf->Text(80, $y, 'عدد الغيابات الغير مبررة : ' . $unjustifiedCount);

        $pdf->setRTL(false);

        return $pdf;

    }

    function employeeInformationReport(\TCPDF $pdf,$data): \TCPDF
    {
        $employeeService = new EmployeeService(new EloquentEmployeeRepository());
        $employee = $employeeService->getEmployeeById($data["emp_id"]);


        $employee["curr_job_title"] = $employee->getCurrentJobTitleAttribute()->name;
        $employee["StartWorkingDateAttribute"] = $employee->getStartWorkingDateAttribute();
        $employee["CurrentDepartmentAttribute"] = $employee->getCurrentDepartmentAttribute()->name;
        $employee["CurrentEmploymentStatusAttribute"] = $employee->employmentStatuses()->whereNull('end_date')->orderByDesc('start_date')->first()->name;

        $pdf->AddPage();

        $pdf->Image(public_path('qiam.jpg'), 83, 13, 45, 45);

        $pdf->setRTL(true);

        $pdf->SetFont('aealarabiya', '', 30);
        $pdf->Text(5,20," مركز قيم");

        $pdf->SetFont('aealarabiya', '', 23);
        $pdf->Text(15,35," قسم الموارد البشرية");

        $pdf->SetFont('aealarabiya', '', 15);

        $pdf->Text(150,25,"التاريخ: ");
        $pdf->Text(162,25,now()->format('Y/m/d'));

        $user_id = Auth::id();
        $byEmployee = Employee::query()->where('user_id','=',$user_id)->first();
        $pdf->Text(150,35,"الموظف المختص: ");
        $pdf->Text(180,35,$byEmployee->getFullNameAttribute());

        $pdf->Line(10, 60, 200, 60);


        $pdf->SetFont('aealarabiya', '', 18);
        $pdf->Text(83,62,"* تقرير عن موظف *");



        $pdf->Line(20, 80+3, 60, 80+3);
        $pdf->Line(20, 80+3, 20, 120+6);
        $pdf->Line(60, 80+3, 60, 120+6);
        $pdf->Line(20, 120+6, 60, 120+6);
        $photo = null; // TODO Add photo
        if($photo){

        }
        else{
            $pdf->SetFont('aealarabiya', '', 15);
            $pdf->Text(149,100," لايوجد صورة شخصية");
        }

        $pdf->SetFont('aealarabiya', '', 13);


        $employee = array(
            'الرقم:' => $employee['emp_id'],
            'اسم الموظف الثلاثي:' => $employee['empData']['first_name'] ." ". $employee['empData']['father_name'] ." ". $employee['empData']['last_name'],
            'الايميل:' => $employee['user']['email'],
            'اسم المستخدم:' => $employee['user']['username'],
            'العنوان:' => $employee['empData']['address']['city'] ." ". $employee['empData']['address']['street'],
            'تاريخ الميلاد:' => $employee['empData']['birth_date'],
            'مكان الميلاد:' => $employee['empData']['birth_place'],
            'المسمى الوظيفي الحالي:' => $employee['curr_job_title'],
            'القسم الحالي:' => $employee['CurrentDepartmentAttribute'],
            'رقم طلب التوظيف:' => $employee['job_app_id'],
            'تاريخ بداية العمل:' => $employee['StartWorkingDateAttribute'],
            'الوضع الوظيفي:' => $employee['CurrentEmploymentStatusAttribute'],
            'برنامج الدوام:' => $employee['schedule']['name'],
            'معلومات جواز السفر'=>"",
            ' - رقم جواز السفر:'=> $employee['empData']['passport']['passport_number'] ?? '',
            ' - مكان إصدار الجواز:'=> $employee['empData']['passport']['place_of_issue'] ?? '',
            ' - تاريخ إصدار الجواز:'=> $employee['empData']['passport']['date_of_issue'] ?? '',
            'معلومات عن الأقارب'=>"",
            ' - عدد الأقارب:'=> count($employee['empData']['relatives'])>0 ? count($employee['empData']['relatives']) : 'لايوجد',
            'معلومات رخصة القيادة'=>"",
            ' - فئة رخصة القيادة:'=> $employee['empData']['drivingLicence']['category'] ?? '',
            ' - تاريخ إصدار الرخصة:'=> $employee['empData']['drivingLicence']['date_of_issue'] ?? '',
            ' - مكان إصدار الرخصة:'=> $employee['empData']['drivingLicence']['place_of_issue'] ?? '',
            ' - تاريخ انتهاء الرخصة:'=> $employee['empData']['drivingLicence']['expiry_date'] ?? '',
            ' - زمرة الدم:'=> $employee['empData']['drivingLicence']['blood_group'] ?? '',
        );
        $x = 10;
        $y = 73;

        $pdf->Line(10, 160, 200, 160);
        $pdf->Line(10, 205, 200, 205);

        $pdf->SetLineStyle(array('dash' => '4,2,1,2'));
        $pdf->Line(105, 210, 105, 277);

        $space = 40;
        foreach ($employee as $key => $column) {
            if ($key === "تاريخ بداية العمل:") {
                $x += 115;
                $y = 166;
                $space =35 ;
            }
            if ($key === 'معلومات جواز السفر') {
                $x = 10;
                $y = 210;
                $space =35 ;
            }

            if ($key === 'معلومات رخصة القيادة') {
                $x += 115;
                $y = 210;
                $space =40 ;
            }

            if ($key === 'المسمى الوظيفي الحالي:') {
                $y += 9;
            }



            $pdf->SetFont('aealarabiya', '', 14);
            $pdf->Text($x, $y, $key);
            $pdf->SetFont('aealarabiya', '', 12);
            $pdf->Text($x + $space, $y, $column); // Retrieve the corresponding employee information
            $y += 12;
        }

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);


        $pdf->setRTL(false);


        return $pdf;

    }

    function employeeAttendanceReport(\TCPDF $pdf,$data): \TCPDF
    {

        $attendanceService = new AttendanceService(new EloquentAttendanceRepository());

        $employee_attendance = $attendanceService->getAttendanceByEmpId($data['emp_id']);


        $employee_attendance = $employee_attendance['data'];

        if(!(!isset($data['attendance_start_date']) && !isset($data['attendance_end_date']))) {
            $employee_attendance = array_filter($employee_attendance,function ($attendance) use ($data) {
                $attendanceDate = $attendance['attendance_date'];
                if (isset($data['attendance_start_date']) && !isset($data['attendance_end_date'])) {
                    return $attendanceDate >= $data['start_date'];
                } elseif (!isset($data['attendance_start_date']) && isset($data['attendance_end_date'])) {
                    return $attendanceDate <= $data['attendance_end_date'];
                }

                return $attendanceDate >= $data['attendance_start_date'] && $attendanceDate <= $data['attendance_end_date'];
            });
        }

        $employee_attendance = collect($employee_attendance);

        $pdf->AddPage();
        $pdf->setRTL(true);
        $pdf->SetFont('aealarabiya', '', 14);

        $pdf->Text(4,20," تاريخ التقرير:");
        $pdf->Text(27,20,now()->format('Y-m-d'));

        $pdf->Text(4,40," اسم الموظف:");
        $pdf->Text(28,40,"انس خالد درة");

        $pdf->Text(4,30," رقم الموظف:");
        $pdf->Text(27,30,$data['emp_id']);

        $pdf->Text(80,60,"تقرير حضور وإنصراف الموظف");

        $now = now()->format('Y-m-d');
        if(isset($data['attendance_start_date']) && isset($data['attendance_end_date'])) $pdf->Text(56,70,"إبتداءاً من تاريخ {$data['attendance_start_date']} حتى تاريخ {$data['attendance_end_date']}");
        if(isset($data['attendance_start_date']) && !isset($data['attendance_end_date'])) $pdf->Text(56,70,"إبتداءاً من تاريخ {$data['attendance_start_date']} حتى تاريخ {$now}");
        if(!isset($data['attendance_start_date']) && isset($data['attendance_end_date'])) $pdf->Text(55,70,"إبتداءاً من تاريخ بداية عمل الموظف حتى تاريخ {$data['attendance_end_date']}");
        if(!isset($data['attendance_start_date']) && !isset($data['attendance_end_date'])) $pdf->Text(55,70,"إبتداءاً من تاريخ عمل الموظف حتى تاريخ {$now}");

        $pdf->setRTL(false);

        $pdf->Image(public_path('qiam.jpg'), 85, 13, 40, 40);

        $header = array('التاريخ', 'الحالة','الرقم','وقت الدخول','مدة التأخير','وقت الخروج','مدة الإنصراف المبكر');

        $pdf->setCellPaddings(5, 3, 5, 3);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 123, 255);

        $pdf->SetFont('aealarabiya', '', 13);

        $pdf->SetXY(10, 79);
        // Set table header row
        $pdf->MultiCell(16, 10, $header[2], 1, 'C', true);

        $pdf->SetXY(26, 79);
        // Set table header row
        $pdf->MultiCell(40, 10, $header[0], 1, 'C', true);

        $pdf->SetXY(64, 79);
        // Set table header row
        $pdf->MultiCell(31, 10, $header[3], 1, 'C', true);

        $pdf->SetXY(95, 79);
        // Set table header row
        $pdf->MultiCell(36, 10, $header[4], 1, 'C', true);

        $pdf->SetXY(131, 79);
        // Set table header row
        $pdf->MultiCell(31, 10, $header[5], 1, 'C', true);

        $pdf->SetXY(162, 79);
        // Set table header row
        $pdf->MultiCell(40, 10, $header[6], 1, 'C', true);


        $pdf->SetFont('aealarabiya', '', 13);

        // Set table content font color (RGB values)
        $pdf->SetTextColor(0, 0, 0);

        // Set table content background color (RGB values)
        $pdf->SetFillColor(255, 255, 255);

        // Set initial table y-position
        $y = 90;
        $j=1;

        $lateSum = 0;
        $lateCount = 0;

        $leaveBeforeSum = 0;
        $leaveBeforeCount = 0;

        $noCheckOutCount = 0;
        // Loop through absence dates and add table rows
        foreach ($employee_attendance as $attendance) {

            if($attendance['check_in.status'] == 1)
                $status = "غير متأخر";
            else
                $status ="متأخر";

            // Check if the current row will cross the page boundary
            if ($y + 20 > $pdf->getPageHeight() - $pdf->getMargins()['bottom']) {
                $pdf->AddPage();
                $y = $pdf->getY() +5; // Update the y-position for the new page
            }


            // Add table row
            $pdf->SetXY(10, $y); // Set the position for the next cell
            $pdf->MultiCell(16, 17.5, $j++, 1, 'C', true);

            $pdf->SetXY(26, $y);
            $pdf->MultiCell(40, 17.5,  $attendance['attendance_date'], 1, 'C', true);

            $latetime =null;
            if(isset($attendance['latetime.duration'])){
                $latetime = $attendance['latetime.duration'];
                $lateCount +=1;

                $time = new \DateTime($attendance['latetime.duration']);
                $lateSum += $time->getTimestamp();

                $late = true;

            }
            else {
                $latetime = 'لايوجد تأخير';
                $late = false;
            }

            $pdf->SetXY(95, $y);
            $pdf->MultiCell(36, 17.5,$latetime, 1, 'C', true);

            if($late){
                $pdf->SetTextColor(204, 0, 0);
            }
            else
                $pdf->SetTextColor(0, 153, 0);

            $pdf->SetXY(64, $y);
            $pdf->MultiCell(31, 17.5,$attendance['check_in_time'], 1, 'C', true);

            $pdf->SetTextColor(0, 0, 0);

            // Shift request
            $shift_request = ShiftRequest::query()->where('shift_req_id','=',$attendance['shift_req_id'])->first();

            $shift_new_time_in = null;
            $shift_new_time_out = null;
            if($shift_request){
                $shift_new_time_in = $shift_request["new_time_in"];
                $shift_new_time_out = $shift_request["new_time_out"];
            }

            $schedule_time_out = $shift_new_time_out ?? $attendance['schedule_time_out'];

            if(!isset($attendance['check_out_time'])){
                $earlierLeave = false;
                $leaveBefore = "-";
            }
            // Leave before calculation
            else if (!($schedule_time_out <= $attendance["check_out_time"])) {
                $leaveTime = \DateTime::createFromFormat('H:i:s', $attendance["check_out_time"]);
                $scheduleTimeOut = \DateTime::createFromFormat('H:i:s', $schedule_time_out);
                $duration = $scheduleTimeOut->diff($leaveTime);
                $attendance["leaveBefore"] = $duration->format('%H:%I:%S');

                $leaveBefore = $attendance['leaveBefore'];
                $leaveBeforeCount +=1;
                $leaveBeforeTime = new \DateTime($attendance['leaveBefore']);
                $leaveBeforeSum += $leaveBeforeTime->getTimestamp();
                $earlierLeave = true;
            }
            else {
                $leaveBefore = 'خرج بالوقت';
                $earlierLeave = false;
            }


            $pdf->SetXY(162, $y);
            $pdf->MultiCell(40, 17.5,$leaveBefore, 1, 'C', true);

            $check_out_time =null;
            if(isset($attendance['check_out_time'])){
                $check_out_time = $attendance['check_out_time'];
                $no_check_out = false;
            }
            else {
                $no_check_out = true;
                $check_out_time = 'لم يقم بتسجيل الخروج';
                $noCheckOutCount+=1;
            }

            if($no_check_out){
                $pdf->SetFillColor(255, 91, 91);
            }

            else if($earlierLeave){
                $pdf->SetTextColor(204, 0, 0);
            }
            else
                $pdf->SetTextColor(0, 153, 0);

            $pdf->SetXY(131, $y);
            $pdf->MultiCell(31, 17.5,$check_out_time, 1, 'C', true);

            $pdf->SetTextColor(0, 0, 0);

            $pdf->SetFillColor(255, 255, 255);

            // Update the y-position for the next row
            $y += 17.5;
        }


        if ($y + 20 > $pdf->getPageHeight() - $pdf->getMargins()['bottom']) {
            $pdf->AddPage(); // Jump to a new page
            $y = $pdf->getY() +5; // Update the y-position for the new page
        }

        $pdf->SetFont('aealarabiya', 'B', 14);
        $pdf->setRTL(true);
        $pdf->Text(9, $y, 'عدد الأيام التي تأخر فيها : ' . $lateCount);
        $pdf->Text(123, $y, 'عدد الأيام التي خرج فيها مبكراً : ' . $leaveBeforeCount);

        $y +=10;

        $pdf->Text(9, $y, 'مدة التأخر الكلية : ' . gmdate('H:i:s', $lateSum));
        $pdf->Text(123, $y, 'مدة الإنصراف المبكر الكلية : ' . gmdate('H:i:s', $leaveBeforeSum));

        $y = $y +10;

        $pdf->Text(9, $y, 'عدد الأيام التي لم يقم بتسجيل الخروج فيها : ' . $noCheckOutCount);

        $y = $y +9;

        $pdf->setRTL(false);

        return $pdf;

    }

    function employeeStaffingReport(\TCPDF $staffing_report,$data): \TCPDF {
        $employeeService = new EmployeeService(new EloquentEmployeeRepository());
        $employee = $employeeService->getEmployeeById($data['emp_id']);
        $staffings = $employee->staffings()
            ->orderByDesc('start_date')
            ->get()
            ->map(function ($staffing) {
                return [
                    'job_title' => $staffing->jobTitle()->first()->name,
                    'department' => $staffing->department->name,
                    'start_date' => $staffing->start_date,
                    'end_date' => $staffing->end_date,
                ];
            });
        // Create a new TCPDF object
        $staffing_report = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $staffing_report->AddPage();
        $staffing_report->Image(public_path('qiam.jpg'), 83, 13, 45, 45);

        $staffing_report->setRTL(true);

        $staffing_report->SetFont('aealarabiya', '', 30);
        $staffing_report->Text(5,20," مركز قيم");

        $staffing_report->SetFont('aealarabiya', '', 23);
        $staffing_report->Text(15,35," قسم الموارد البشرية");

        $staffing_report->SetFont('aealarabiya', '', 15);

        $staffing_report->Text(150,25-6,"التاريخ: ");
        $staffing_report->Text(162,25-6,now()->format('Y/m/d'));

        $staffing_report->Text(150,33-6,"رقم الموظف:");
        $staffing_report->Text(173,33-6,$data['emp_id']);

        $staffing_report->Text(150,41-6,"اسم الموظف:");
        $staffing_report->Text(174,41-6,$employee->getFullNameAttribute());

        $user_id = Auth::id();
        $byEmployee = Employee::query()->where('user_id','=',$user_id)->first();
        $staffing_report->Text(150,49-6,"الموظف المختص: ");
        $staffing_report->Text(180,49-6,$byEmployee->getFullNameAttribute());


        $staffing_report->SetFont('aealarabiya', '', 16);
        $staffing_report->Text(51,65,"* تقرير عن تنقلات الموظف في الأقسام خلال عمله بالمركز *");

        // Set document information
        $staffing_report->SetCreator('Your Name');
        $staffing_report->SetAuthor('Your Name');
        $staffing_report->SetTitle('Employee Staffing Report');
        $staffing_report->SetSubject('Employee Staffing Report');

        $staffing_report->Line(10, 60, 200, 60);
        // Set font for table headers and data
        $staffing_report->SetFont('aealarabiya', '', 12);

        $y = 80; // Initial y-position of the table

        $staffing_report->SetFillColor(242, 242, 242); // Background color for table header
        $staffing_report->SetXY(7, $y);
        // Add table headers
        $staffing_report->Cell(50, 10, 'القسم', 1, 0, 'C', 1);
        $staffing_report->Cell(45, 10, 'تاريخ بدء العمل', 1, 0, 'C', 1);
        $staffing_report->Cell(45, 10, 'تاريخ انتهاء العمل', 1, 0, 'C', 1);
        $staffing_report->Cell(55, 10, 'المسمى الوظيفي في تلك الفترة', 1, 1, 'C', 1);

        // Reset the fill color, text color, and border color for table data
        $staffing_report->SetFillColor(255); // Reset the fill color to white
        $staffing_report->SetTextColor(0); // Reset the text color to black
        $staffing_report->SetDrawColor(0); // Reset the border color to black
        $staffing_report->SetFont('aealarabiya', '', 10);

        $y = 90;
        foreach ($staffings as $staffing) {
            if ($y > 250) {
                $staffing_report->AddPage();
                $y = 40;
            }

            $staffing_report->SetXY(7,$y);
            $staffing_report->Cell(50, 10, $staffing['department'], 1, 0, 'C');
            $staffing_report->Cell(45, 10, $staffing['start_date'], 1, 0, 'C');
            $staffing_report->Cell(45, 10, ($staffing['end_date'] !== null ? $staffing['end_date'] : 'N/A'), 1, 0, 'C');

            $staffing_report->Cell(55, 10, $staffing['job_title'], 1, 0, 'C');


            $y += 10; // Increment the y-position
        }

        return $staffing_report;
    }
}
