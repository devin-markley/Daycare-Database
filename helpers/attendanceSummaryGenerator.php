<?php
require_once 'libraries/TCPDF-main/tcpdf.php';

class PdfGenerator
{
    public static function generateAttendanceSummaryPDF($summaryData, $providerName, $reportStartDate, $mealTableData)
    {
        // create new PDF document
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(is_array($providerName) ? join(' ', $providerName) : $providerName);
        $pdf->SetTitle('Attendance Summary Report');
        $pdf->SetSubject('Attendance Summary Report');

        // set default font
        $pdf->SetFont('helvetica', '', 12);

        // add a page
        $pdf->AddPage();

        // output the provider name and report start date
        $pdf->Cell(0, 10, "Attendance Summary Report for " . (is_array($providerName) ? join(' ', $providerName) : $providerName) . " - " . (is_array($reportStartDate) ? implode('-', $reportStartDate) : $reportStartDate), 0, 1, 'C');

        // output the summary data
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Total Children Fed:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(50, 10, $summaryData[0]['total_children_fed'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Total Meals:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(50, 10, $summaryData[0]['total_distinct_meals'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Fruit Percentage:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(50, 10, $summaryData[0]['fruit_percentage'] . '%', 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Vegetable Percentage:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(50, 10, $summaryData[0]['vegetable_percentage'] . '%', 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Meal Table', 0, 1);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Date Served', 1, 0);
        $pdf->Cell(50, 10, 'Fruit', 1, 0);
        $pdf->Cell(50, 10, 'Vegetables', 1, 0);
        $pdf->Cell(50, 10, 'Num Children', 1, 1);

        $pdf->SetFont('helvetica', '', 12);
        foreach ($mealTableData as $row) {
            $pdf->Cell(50, 10, $row['date_served'], 1, 0);
            $pdf->Cell(50, 10, $row['fruit'], 1, 0);
            $pdf->Cell(50, 10, $row['vegetables'], 1, 0);
            $pdf->Cell(50, 10, $row['num_children'], 1, 1);
        }

        $pdf->Output('attendance_summary.pdf', 'I');
    }
}