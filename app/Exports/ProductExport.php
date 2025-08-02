<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProductExport implements FromCollection, WithHeadings, WithDrawings
{
    public function collection()
    {
        return Product::select(
            'category_id',
            'supplier_id',
            'name',
            'sku',
            'description',
            'purchase_price',
            'selling_price',
            'image',
            'minimum_stock'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Category ID',
            'Supplier ID',
            'Name',
            'SKU',
            'Description',
            'Purchase Price',
            'Selling Price',
            'Image',
            'Minimum Stock',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $products = Product::all();

        foreach ($products as $index => $product) {
            if ($product->image && file_exists(public_path('storage/images/' . $product->image))) {
                $drawing = new Drawing();
                $drawing->setName('Product Image');
                $drawing->setDescription('Product Image');
                $drawing->setPath(public_path('storage/images/' . $product->image)); // path gambar
                $drawing->setHeight(60);
                $drawing->setCoordinates('H' . ($index + 2)); // Gambar akan muncul di kolom H (kolom ke-8)
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }
}
