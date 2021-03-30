<?php

namespace Botble\Table\Supports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Yajra\DataTables\Services\DataTablesExportHandler;

class TableExportHandler extends DataTablesExportHandler implements WithEvents
{
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->beforeSheet($event);
            },
            AfterSheet::class  => function (AfterSheet $event) {
                $this->afterSheet($event);
            },
        ];
    }

    /**
     * @param BeforeSheet $event
     * @throws Exception
     */
    protected function beforeSheet(BeforeSheet $event)
    {
        $event->sheet
            ->getDelegate()
            ->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0)
            ->setHorizontalCentered(true)
            ->setVerticalCentered(false);

        $event->sheet
            ->getDelegate()
            ->getPageMargins()
            ->setTop(0.4)
            ->setLeft(0.4)
            ->setBottom(0.4)
            ->setRight(0.4)
            ->setHeader(0.0)
            ->setFooter(0.0);
    }

    /**
     * @param AfterSheet $event
     * @throws Exception
     */
    protected function afterSheet(AfterSheet $event)
    {
        $totalColumns = count(array_filter($this->headings()));
        $lastColumnName = $this->getNameFromNumber($totalColumns);
        $dimensions = 'A1:' . $lastColumnName . '1';
        $event->sheet->getDelegate()->getStyle($dimensions)->applyFromArray(
            [
                'font'      => [
                    'bold'  => true,
                    'color' => [
                        'argb' => 'ffffff',
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill'      => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => '1d9977',
                    ],
                ],
            ]
        );

        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(10);
        $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);

        for ($index = 2; $index <= $totalColumns; $index++) {
            $event->sheet->getDelegate()->getColumnDimension($this->getNameFromNumber($index))->setWidth(25);
        }

        $event->sheet->getDelegate()
            ->getStyle('A1:Z' . ($this->collection->count() + 1))
            ->getAlignment()
            ->setWrapText(true)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $event->sheet->getDelegate()->setSelectedCell('A1');
        $event->sheet->getDelegate()->freezePane('A2');
    }

    /**
     * @param int $number
     * @return string
     */
    protected function getNameFromNumber($number)
    {
        $numeric = ($number - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($number - 1) / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2) . $letter;
        }

        return $letter;
    }
}
