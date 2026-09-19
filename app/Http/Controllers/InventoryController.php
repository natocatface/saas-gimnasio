<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::latest();

        if ($request->search) {
            $q = $request->search;
            $query->where('name','like',"%$q%")->orWhere('category','like',"%$q%");
        }

        if ($request->status)   $query->where('status', $request->status);
        if ($request->category) $query->where('category', $request->category);

        $inventory  = $query->paginate(15)->withQueryString();
        $categories = Inventory::distinct()->pluck('category')->filter();

        $stats = [
            'total'       => Inventory::count(),
            'low_stock'   => Inventory::whereColumn('quantity','<=','min_quantity')->count(),
            'out_of_stock'=> Inventory::where('status','agotado')->count(),
            'total_value' => Inventory::selectRaw('SUM(quantity * unit_price) as val')->value('val') ?? 0,
        ];

        return view('inventory.index', compact('inventory','categories','stats'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'description'   => 'nullable|string',
            'quantity'      => 'required|integer|min:0',
            'min_quantity'  => 'required|integer|min:0',
            'unit_price'    => 'nullable|numeric|min:0',
            'supplier'      => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'status'        => 'required|in:disponible,agotado,mantenimiento',
            'notes'         => 'nullable|string',
        ]);

        // Auto status based on quantity
        if ($data['quantity'] === 0) $data['status'] = 'agotado';

        Inventory::create($data);
        return redirect()->route('inventory.index')->with('success', 'Ítem de inventario creado.');
    }

    public function show(Inventory $inventory)
    {
        return view('inventory.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'description'   => 'nullable|string',
            'quantity'      => 'required|integer|min:0',
            'min_quantity'  => 'required|integer|min:0',
            'unit_price'    => 'nullable|numeric|min:0',
            'supplier'      => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'status'        => 'required|in:disponible,agotado,mantenimiento',
            'notes'         => 'nullable|string',
        ]);

        $inventory->update($data);
        return redirect()->route('inventory.index')->with('success', 'Inventario actualizado.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Ítem eliminado.');
    }
}
