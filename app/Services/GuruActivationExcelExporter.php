<?php

namespace App\Services;

use RuntimeException;
use ZipArchive;

class GuruActivationExcelExporter
{
    /**
     * Create a small Excel Open XML workbook without persisting it in storage.
     *
     * @param  array<int, array{nip:string,nama_lengkap:string,kode_aktivasi:string}>  $rows
     */
    public function make(array $rows): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('PHP ZipArchive extension is required for Excel export.');
        }

        $temporaryPath = tempnam(sys_get_temp_dir(), 'siguru-activation-');

        if ($temporaryPath === false) {
            throw new RuntimeException('Unable to create a temporary Excel workbook.');
        }

        try {
            $zip = new ZipArchive;
            $result = $zip->open($temporaryPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            if ($result !== true) {
                throw new RuntimeException('Unable to open the temporary Excel workbook.');
            }

            $zip->addFromString('[Content_Types].xml', $this->contentTypes());
            $zip->addFromString('_rels/.rels', $this->packageRelationships());
            $zip->addFromString('xl/workbook.xml', $this->workbook());
            $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelationships());
            $zip->addFromString('xl/worksheets/sheet1.xml', $this->worksheet($rows));

            if (! $zip->close()) {
                throw new RuntimeException('Unable to finish the Excel workbook.');
            }

            $contents = file_get_contents($temporaryPath);

            if ($contents === false) {
                throw new RuntimeException('Unable to read the completed Excel workbook.');
            }

            return $contents;
        } finally {
            if (is_file($temporaryPath)) {
                unlink($temporaryPath);
            }
        }
    }

    private function contentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'</Types>';
    }

    private function packageRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
    }

    private function workbook(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Kode Aktivasi" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>';
    }

    private function workbookRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'</Relationships>';
    }

    /**
     * @param  array<int, array{nip:string,nama_lengkap:string,kode_aktivasi:string}>  $rows
     */
    private function worksheet(array $rows): string
    {
        $allRows = array_merge([
            ['NIP', 'Nama Lengkap', 'Kode Aktivasi'],
        ], array_map(fn (array $row) => [
            $row['nip'],
            $row['nama_lengkap'],
            $row['kode_aktivasi'],
        ], $rows));

        $xmlRows = '';

        foreach ($allRows as $rowIndex => $row) {
            $excelRow = $rowIndex + 1;
            $cells = '';

            foreach ($row as $columnIndex => $value) {
                $column = ['A', 'B', 'C'][$columnIndex];
                $cells .= '<c r="'.$column.$excelRow.'" t="inlineStr"><is><t xml:space="preserve">'
                    .$this->escapeXml((string) $value)
                    .'</t></is></c>';
            }

            $xmlRows .= '<row r="'.$excelRow.'">'.$cells.'</row>';
        }

        $lastRow = count($allRows);

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<sheetViews><sheetView showGridLines="1" workbookViewId="0"/></sheetViews>'
            .'<cols><col min="1" max="1" width="24" customWidth="1"/>'
            .'<col min="2" max="2" width="42" customWidth="1"/>'
            .'<col min="3" max="3" width="24" customWidth="1"/></cols>'
            .'<sheetData>'.$xmlRows.'</sheetData>'
            .'<autoFilter ref="A1:C'.$lastRow.'"/>'
            .'</worksheet>';
    }

    private function escapeXml(string $value): string
    {
        $validXml = preg_replace(
            '/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u',
            '',
            $value
        ) ?? '';

        return htmlspecialchars($validXml, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
