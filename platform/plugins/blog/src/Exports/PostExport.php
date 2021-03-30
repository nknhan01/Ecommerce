<?php

namespace Botble\Blog\Exports;

use Botble\Table\Supports\TableExportHandler;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class PostExport extends TableExportHandler
{
    /**
     * {@inheritDoc}
     */
    protected function afterSheet(AfterSheet $event)
    {
        parent::afterSheet($event);

        $totalRows = $this->collection->count() + 1;

        $event->sheet->getDelegate()->getStyle('F1:F' . $totalRows)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        for ($index = 2; $index <= $totalRows; $index++) {
            $image = $event->sheet->getDelegate()->getCell('B' . $index)->getValue();

            $drawing = new MemoryDrawing;
            $drawing->setName('Image')
                ->setWorksheet($event->sheet->getDelegate());

            $drawing
                ->setRenderingFunction(MemoryDrawing::RENDERING_PNG)
                ->setMimeType(MemoryDrawing::MIMETYPE_PNG)
                ->setImageResource($this->getImageResourceFromURL($image))
                ->setCoordinates('B' . $index)
                ->setWidth(70)
                ->setHeight(70)
                ->setOffsetX(10)
                ->setOffsetY(10);

            $event->sheet->getDelegate()->getCell('B' . $index)->setValue(null);
            $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(11);
            $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(40);
            $event->sheet
                ->getDelegate()
                ->getStyle('C1:C' . $totalRows)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getRowDimension($index)->setRowHeight(65);

            $status = $event->sheet->getDelegate()->getStyle('F' . $index)->getFont()->getColor();

            if ($event->sheet->getDelegate()->getCell('F' . $index)->getValue()) {
                $status->setARGB('1d9977');
                $event->sheet
                    ->getDelegate()
                    ->getCell('F' . $index)
                    ->setValue(ucfirst(trans('core/base::tables.activated')));
            } else {
                $status->setARGB('dc3545');
                $event->sheet
                    ->getDelegate()
                    ->getCell('F' . $index)
                    ->setValue(ucfirst(trans('core/base::tables.deactivated')));
            }
        }

    }

    /**
     * @param string $imageUrl
     * @return null|resource
     */
    protected function getImageResourceFromURL($imageUrl)
    {
        return imagecreatefromstring(file_get_contents($imageUrl));
    }
}
