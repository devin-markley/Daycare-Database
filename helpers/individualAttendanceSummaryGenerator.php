<?php
require_once 'libraries/TCPDF-main/tcpdf.php';

class PdfGenerator
{
    public static function generateIndividualAttendanceSummaryPDF($summaryData, $providerName, $reportStartDate)
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

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Meal Table', 0, 1);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Date Served', 1, 0);
        $pdf->Cell(50, 10, 'Fruit', 1, 0);
        $pdf->Cell(50, 10, 'Vegetables', 1, 1);

        $pdf->SetFont('helvetica', '', 12);
        foreach ($summaryData as $row) {
            $pdf->Cell(50, 10, $row['date_served'], 1, 0);
            $pdf->Cell(50, 10, ($row['fruit'] ? 'yes' : 'no'), 1, 0);
            $pdf->Cell(50, 10, ($row['vegetables'] ? 'yes' : 'no'), 1, 1);
        }

        $pdf->Output('individual_attendance_summary.pdf', 'I');
    }
}