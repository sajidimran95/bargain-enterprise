<?php

namespace App\Support;

use App\Models\Setting;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

/**
 * QuickBooks Desktop–style .xlsm exporter (spacer columns, header lines, gridlines).
 */
class XlsxExporter
{
    /**
     * @param  array<int, string>  $headers
     * @param  iterable<int, array<int, string|int|float|null>>  $rows
     */
    public function download(
        string $filename,
        array $headers,
        iterable $rows,
        ?string $title = null,
        ?string $subtitle = null,
        ?string $companyName = null,
        bool $qbLayout = true,
    ): StreamedResponse {
        $filename = $this->ensureXlsmExtension($filename);
        $companyName ??= $this->resolveCompanyName();
        $title ??= pathinfo($filename, PATHINFO_FILENAME);

        $binary = $this->build(
            headers: $headers,
            rows: $rows,
            companyName: $companyName,
            title: $title,
            subtitle: $subtitle,
            qbLayout: $qbLayout,
        );

        return response()->streamDownload(function () use ($binary) {
            echo $binary;
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
        ]);
    }

    /**
     * @param  array<int, string>  $headers
     * @param  iterable<int, array<int, string|int|float|null>>  $rows
     */
    public function build(
        array $headers,
        iterable $rows,
        string $companyName,
        string $title,
        ?string $subtitle = null,
        bool $qbLayout = true,
    ): string {
        if ($qbLayout) {
            $headers = $this->withSpacers($headers);
            $expanded = [];
            foreach ($rows as $row) {
                $expanded[] = $this->withSpacers(array_values((array) $row));
            }
            $rows = $expanded;
        }

        $sheetRows = $this->buildSheetRows($headers, $rows, $companyName, $title, $subtitle, $qbLayout);
        $sheetXml = $this->worksheetXml($sheetRows, count($headers), $qbLayout);
        $stylesXml = $this->stylesXml();

        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        if ($tmp === false) {
            throw new \RuntimeException('Unable to create temporary XLSX file.');
        }

        $zip = new ZipArchive;
        if ($zip->open($tmp, ZipArchive::OVERWRITE) !== true) {
            @unlink($tmp);
            throw new \RuntimeException('Unable to open XLSX archive.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $zip->addFromString('_rels/.rels', $this->rootRelsXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelsXml());
        $zip->addFromString('xl/styles.xml', $stylesXml);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->close();

        $binary = file_get_contents($tmp);
        @unlink($tmp);

        if ($binary === false) {
            throw new \RuntimeException('Unable to read generated XLSX file.');
        }

        return $binary;
    }

    public function ensureXlsmExtension(string $filename): string
    {
        $filename = preg_replace('/\.(csv|xls|xlsx)$/i', '.xlsm', $filename) ?? $filename;

        if (! str_ends_with(strtolower($filename), '.xlsm')) {
            $filename .= '.xlsm';
        }

        return $filename;
    }

    /**
     * Insert narrow spacer columns between logical columns (QB Desktop export look).
     *
     * @param  array<int, mixed>  $cells
     * @return list<mixed>
     */
    public function withSpacers(array $cells): array
    {
        $out = [];
        foreach (array_values($cells) as $index => $cell) {
            if ($index > 0) {
                $out[] = '';
            }
            $out[] = $cell;
        }

        return $out;
    }

    protected function resolveCompanyName(): string
    {
        return (string) (
            Setting::getValue('company.name', config('bargain.company_name', config('app.name')))
            ?: config('bargain.company_name', config('app.name'))
        );
    }

    /**
     * @param  array<int, string>  $headers
     * @param  iterable<int, array<int, string|int|float|null>>  $rows
     * @return list<array{cells: list<array{value: mixed, type: string, style: int}>, kind: string}>
     */
    protected function buildSheetRows(
        array $headers,
        iterable $rows,
        string $companyName,
        string $title,
        ?string $subtitle,
        bool $qbLayout,
    ): array {
        $sheet = [];

        if (! $qbLayout) {
            $sheet[] = $this->titleRow($companyName, count($headers), 1);
            $sheet[] = $this->titleRow($title, count($headers), 2);
            if (filled($subtitle)) {
                $sheet[] = $this->titleRow((string) $subtitle, count($headers), 3);
            }
            $sheet[] = $this->blankRow(count($headers));
        }

        $sheet[] = $this->headerRow($headers);

        foreach ($rows as $row) {
            $values = array_values((array) $row);
            while (count($values) < count($headers)) {
                $values[] = '';
            }
            $values = array_slice($values, 0, count($headers));

            $label = trim((string) ($values[0] ?? ''));
            $kind = $this->detectRowKind($label, $values);
            $sheet[] = $this->dataRow($values, $kind, $qbLayout);
        }

        return $sheet;
    }

    /**
     * @return array{cells: list<array{value: mixed, type: string, style: int}>, kind: string}
     */
    protected function titleRow(string $text, int $columnCount, int $level): array
    {
        $style = match ($level) {
            1 => 4,
            2 => 5,
            default => 6,
        };

        $cells = [[
            'value' => $text,
            'type' => 's',
            'style' => $style,
        ]];

        for ($i = 1; $i < $columnCount; $i++) {
            $cells[] = ['value' => '', 'type' => 's', 'style' => $style];
        }

        return ['cells' => $cells, 'kind' => 'title'];
    }

    /**
     * @return array{cells: list<array{value: mixed, type: string, style: int}>, kind: string}
     */
    protected function blankRow(int $columnCount): array
    {
        $cells = [];
        for ($i = 0; $i < $columnCount; $i++) {
            $cells[] = ['value' => '', 'type' => 's', 'style' => 0];
        }

        return ['cells' => $cells, 'kind' => 'blank'];
    }

    /**
     * @param  array<int, string>  $headers
     * @return array{cells: list<array{value: mixed, type: string, style: int}>, kind: string}
     */
    protected function headerRow(array $headers): array
    {
        $cells = [];
        foreach ($headers as $header) {
            $isSpacer = trim((string) $header) === '';
            $cells[] = [
                'value' => $header,
                'type' => 's',
                // 11 = header with top+bottom line; 0 = spacer
                'style' => $isSpacer ? 0 : 11,
            ];
        }

        return ['cells' => $cells, 'kind' => 'header'];
    }

    /**
     * @param  array<int, mixed>  $values
     * @return array{cells: list<array{value: mixed, type: string, style: int}>, kind: string}
     */
    protected function dataRow(array $values, string $kind, bool $qbLayout = true): array
    {
        $cells = [];
        foreach ($values as $index => $value) {
            $isSpacerCol = $qbLayout && $index % 2 === 1;
            if ($isSpacerCol && trim((string) $value) === '') {
                $cells[] = ['value' => '', 'type' => 's', 'style' => 0];

                continue;
            }

            [$normalized, $type] = $this->normalizeCell($value);
            $isNumeric = $type === 'n';
            $style = match (true) {
                $kind === 'grand' && $isNumeric => 10,
                $kind === 'total' && $isNumeric => 9,
                $kind === 'group' => 7,
                $kind === 'grand' => 8,
                $kind === 'total' => 8,
                $isNumeric => 3,
                default => 0,
            };

            $cells[] = [
                'value' => $normalized,
                'type' => $type,
                'style' => $style,
            ];
        }

        return ['cells' => $cells, 'kind' => $kind];
    }

    /**
     * @param  array<int, mixed>  $values
     */
    protected function detectRowKind(string $label, array $values = []): string
    {
        $lower = strtolower($label);

        if ($lower === 'total') {
            return 'grand';
        }

        if (str_starts_with($lower, 'total ') || str_starts_with($lower, 'total')) {
            return 'total';
        }

        $othersEmpty = true;
        for ($i = 1, $count = count($values); $i < $count; $i++) {
            if (trim((string) $values[$i]) !== '') {
                $othersEmpty = false;
                break;
            }
        }

        if ($label !== '' && $othersEmpty) {
            return 'group';
        }

        return 'data';
    }

    /**
     * @return array{0: mixed, 1: string}
     */
    protected function normalizeCell(mixed $value): array
    {
        if ($value === null || $value === '') {
            return ['', 's'];
        }

        if (is_int($value) || is_float($value)) {
            return [$value, 'n'];
        }

        $string = trim((string) $value);
        if ($string === '') {
            return ['', 's'];
        }

        if (preg_match('/^0\d+$/', $string) === 1) {
            return [$string, 's'];
        }

        if (preg_match('/^-?\d+(\.\d+)?$/', $string) === 1) {
            return [(float) $string, 'n'];
        }

        return [$string, 's'];
    }

    /**
     * @param  list<array{cells: list<array{value: mixed, type: string, style: int}>, kind: string}>  $sheetRows
     */
    protected function worksheetXml(array $sheetRows, int $columnCount, bool $qbLayout): string
    {
        $lastCol = $this->columnLetter($columnCount);
        $colsXml = '<cols>';
        for ($i = 1; $i <= $columnCount; $i++) {
            if ($qbLayout) {
                // Odd Excel cols (2,4,6…) are spacers after withSpacers expansion.
                $width = ($i % 2 === 0) ? 2.5 : ($i === 1 ? 28 : 14);
            } else {
                $width = $i === 1 ? 36 : 14;
            }
            $colsXml .= '<col min="'.$i.'" max="'.$i.'" width="'.$width.'" customWidth="1"/>';
        }
        $colsXml .= '</cols>';

        $sheetData = '<sheetData>';
        foreach ($sheetRows as $rowIndex => $row) {
            $r = $rowIndex + 1;
            $sheetData .= '<row r="'.$r.'">';
            foreach ($row['cells'] as $colIndex => $cell) {
                $ref = $this->columnLetter($colIndex + 1).$r;
                $style = (int) $cell['style'];
                if ($cell['type'] === 'n') {
                    $sheetData .= '<c r="'.$ref.'" s="'.$style.'"><v>'.$this->xmlNumber($cell['value']).'</v></c>';
                } else {
                    $text = (string) $cell['value'];
                    if ($text === '') {
                        $sheetData .= '<c r="'.$ref.'" s="'.$style.'"/>';
                    } else {
                        $sheetData .= '<c r="'.$ref.'" s="'.$style.'" t="inlineStr"><is><t>'.$this->xmlText($text).'</t></is></c>';
                    }
                }
            }
            $sheetData .= '</row>';
        }
        $sheetData .= '</sheetData>';

        $grid = $qbLayout ? '1' : '0';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            .' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<dimension ref="A1:'.$lastCol.count($sheetRows).'"/>'
            .'<sheetViews><sheetView showGridLines="'.$grid.'" workbookViewId="0"/></sheetViews>'
            .'<sheetFormatPr defaultRowHeight="14.25"/>'
            .$colsXml
            .$sheetData
            .'<pageMargins left="0.5" right="0.5" top="0.5" bottom="0.5" header="0.3" footer="0.3"/>'
            .'</worksheet>';
    }

    protected function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<numFmts count="2">'
            .'<numFmt numFmtId="164" formatCode="#,##0.00;-#,##0.00"/>'
            .'<numFmt numFmtId="165" formatCode="#,##0.####;-#,##0.####"/>'
            .'</numFmts>'
            .'<fonts count="5">'
            .'<font><sz val="8"/><color rgb="FF323232"/><name val="Arial"/><family val="2"/></font>'
            .'<font><b/><sz val="8"/><color rgb="FF323232"/><name val="Arial"/><family val="2"/></font>'
            .'<font><b/><sz val="12"/><color rgb="FF323232"/><name val="Arial"/><family val="2"/></font>'
            .'<font><b/><sz val="11"/><color rgb="FF323232"/><name val="Arial"/><family val="2"/></font>'
            .'<font><sz val="8"/><color rgb="FF666666"/><name val="Arial"/><family val="2"/></font>'
            .'</fonts>'
            .'<fills count="2">'
            .'<fill><patternFill patternType="none"/></fill>'
            .'<fill><patternFill patternType="gray125"/></fill>'
            .'</fills>'
            .'<borders count="4">'
            .'<border><left/><right/><top/><bottom/><diagonal/></border>'
            .'<border><left/><right/><top/><bottom style="medium"><color rgb="FF323232"/></bottom><diagonal/></border>'
            .'<border><left/><right/><top style="medium"><color rgb="FF323232"/></top><bottom/><diagonal/></border>'
            .'<border><left/><right/><top style="medium"><color rgb="FF323232"/></top><bottom style="double"><color rgb="FF323232"/></bottom><diagonal/></border>'
            .'</borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs count="12">'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="1" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1"/>'
            .'<xf numFmtId="0" fontId="1" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center"/></xf>'
            .'<xf numFmtId="164" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            .'<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1"/>'
            .'<xf numFmtId="0" fontId="3" fillId="0" borderId="0" xfId="0" applyFont="1"/>'
            .'<xf numFmtId="0" fontId="4" fillId="0" borderId="0" xfId="0" applyFont="1"/>'
            .'<xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>'
            .'<xf numFmtId="0" fontId="1" fillId="0" borderId="2" xfId="0" applyFont="1" applyBorder="1"/>'
            .'<xf numFmtId="164" fontId="1" fillId="0" borderId="2" xfId="0" applyNumberFormat="1" applyFont="1" applyBorder="1"/>'
            .'<xf numFmtId="164" fontId="1" fillId="0" borderId="3" xfId="0" applyNumberFormat="1" applyFont="1" applyBorder="1"/>'
            // 11: QB header — line above and below (border id 3 uses top medium + bottom double; use border 1 medium bottom + add top via border 3-like)
            .'<xf numFmtId="0" fontId="1" fillId="0" borderId="3" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" wrapText="1"/></xf>'
            .'</cellXfs>'
            .'<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            .'</styleSheet>';
    }

    protected function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.ms-excel.sheet.macroEnabled.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'</Types>';
    }

    protected function rootRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
    }

    protected function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            .' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Report" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>';
    }

    protected function workbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            .'</Relationships>';
    }

    protected function columnLetter(int $index): string
    {
        $letter = '';
        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)).$letter;
            $index = intdiv($index, 26);
        }

        return $letter;
    }

    protected function xmlText(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    protected function xmlNumber(mixed $value): string
    {
        if (is_int($value)) {
            return (string) $value;
        }

        $float = (float) $value;

        return rtrim(rtrim(number_format($float, 8, '.', ''), '0'), '.') ?: '0';
    }
}
