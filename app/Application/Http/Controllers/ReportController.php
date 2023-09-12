<?php

namespace App\Application\Http\Controllers;
use App\Domain\Services\EmployeeService;
use App\Domain\Services\ReportService;
use App\Infrastructure\Persistence\Eloquent\EloquentEmployeeRepository;
use Illuminate\Support\Facades\Validator;

class ReportController
{
    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     */
    function create(){
        $ReportService = new ReportService();
        $validator = Validator::make(request()->query(), [
            'emp_id' => 'required|exists:employees,emp_id',
            'staffing_report' => ['sometimes', function ($attribute, $value, $fail) {
                if (!is_bool($value) && !in_array($value, ['true', 'false', '1', '0'], true)) {
                    $fail('The '.$attribute.' field must be true or false.');
                }
            }],
            'attendance_report' => ['sometimes', function ($attribute, $value, $fail) {
                if (!is_bool($value) && !in_array($value, ['true', 'false', '1', '0'], true)) {
                    $fail('The '.$attribute.' field must be true or false.');
                }
            }],
            'attendance_start_date' => 'sometimes|date',
            'attendance_end_date' => 'sometimes|date',
            'absence_report' => ['nullable', function ($attribute, $value, $fail) {
                if (!is_bool($value) && !in_array($value, ['true', 'false', '1', '0'], true)) {
                    $fail('The '.$attribute.' field must be true or false.');
                }
            }],
            'absence_start_date' => 'nullable|date',
            'absence_end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $ReportService->create();
    }

}
