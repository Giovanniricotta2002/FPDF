<?php

use PHPUnit\Framework\TestCase;

class FPDFTest extends TestCase
{
    public function testGeneratesAMinimalValidPdf()
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'Hello World!');
        $out = $pdf->Output('S');

        $this->assertStringStartsWith('%PDF-1.3', $out);
        $this->assertStringEndsWith("%%EOF\n", $out);
    }

    public function testMultiCellWrapsLongText()
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(0, 5, str_repeat('lorem ipsum dolor sit amet ', 30));
        $out = $pdf->Output('S');

        $this->assertStringStartsWith('%PDF-', $out);
    }

    public function testImageEmbedsAPngFile()
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image(__DIR__.'/../tutorial/logo.png', 10, 10, 30);
        $out = $pdf->Output('S');

        $this->assertStringContainsString('/Subtype /Image', $out);
    }

    public function testInternalLinkIsResolved()
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $link = $pdf->AddLink();
        $pdf->SetLink($link);
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);
        $pdf->Write(5, 'go back', $link);
        $out = $pdf->Output('S');

        $this->assertStringContainsString('/Annots', $out);
    }

    public function testErrorThrowsException()
    {
        $pdf = new FPDF();
        $this->expectException(Exception::class);
        $pdf->SetFont('Arial', '', 0);
        $pdf->Cell(10, 10, 'no page added yet, font undefined behaviour aside');
        $pdf->SetFont('UndefinedFontFamily');
    }

    public function testGetSetXY()
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetXY(20, 30);

        $this->assertEquals(20, $pdf->GetX());
        $this->assertEquals(30, $pdf->GetY());
    }
}
