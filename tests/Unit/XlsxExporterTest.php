<?php

namespace Tests\Unit;

use App\Support\XlsxExporter;
use PHPUnit\Framework\TestCase;
use ZipArchive;

class XlsxExporterTest extends TestCase
{
    public function test_build_produces_qb_style_xlsm_with_spacers_and_gridlines(): void
    {
        $binary = (new XlsxExporter)->build(
            headers: ['', 'Type', 'Date', 'Amount'],
            rows: [
                ['4 EVER', '', '', ''],
                ['', 'Invoice', '09/10/26', '20.00'],
                ['Total 4 EVER', '', '', '20.00'],
                ['TOTAL', '', '', '20.00'],
            ],
            companyName: 'Bargain Enterprise Inc.',
            title: 'Sales by Customer Detail',
            subtitle: 'Sep 1 - 15, 26',
            qbLayout: true,
        );

        $this->assertSame('PK', substr($binary, 0, 2));

        $tmp = tempnam(sys_get_temp_dir(), 'xlsx-test');
        $this->assertNotFalse($tmp);
        file_put_contents($tmp, $binary);

        $zip = new ZipArchive;
        $this->assertTrue($zip->open($tmp) === true);
        $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
        $styles = $zip->getFromName('xl/styles.xml');
        $zip->close();
        @unlink($tmp);

        $this->assertIsString($sheet);
        $this->assertStringContainsString('showGridLines="1"', $sheet);
        $this->assertStringContainsString('Type', $sheet);
        $this->assertStringContainsString('4 EVER', $sheet);
        $this->assertStringContainsString('Invoice', $sheet);
        $this->assertStringContainsString('TOTAL', $sheet);
        $this->assertIsString($styles);
        $this->assertStringContainsString('Arial', $styles);
    }

    public function test_filename_extension_normalized_to_xlsm(): void
    {
        $exporter = new XlsxExporter;

        $this->assertSame('sales-by-item.xlsm', $exporter->ensureXlsmExtension('sales-by-item.csv'));
        $this->assertSame('report.xlsm', $exporter->ensureXlsmExtension('report.xlsx'));
        $this->assertSame('report.xlsm', $exporter->ensureXlsmExtension('report.xlsm'));
    }
}
