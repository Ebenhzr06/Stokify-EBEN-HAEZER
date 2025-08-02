<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Services\StockOpnameService;

class StockOpnameController extends Controller
{
    protected $stockOpnameService;
    public function __construct(StockOpnameService $stockOpnameService)
    {
        $this->stockOpnameService = $stockOpnameService;
    }

    public function index()
    {
        $opnamestock = $this->stockOpnameService->getAll();
         if (Auth::user()->role == 'Admin') {
            return view('example.content.admin.opname.index',[
            'stocks' => $opnamestock,
        ]);
        } elseif (Auth::user()->role == 'Manajer gudang') {
          return view('example.content.manajer.opname.index',[
            'stocks' => $opnamestock,
        ]);
        }
    }
}
