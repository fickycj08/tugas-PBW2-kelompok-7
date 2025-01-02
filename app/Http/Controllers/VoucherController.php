<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all(); // Mengambil semua data voucher
        return view('vouchers.index', compact('vouchers'));
    }
    

    public function create()
    {
        return view('vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code',
            'discount' => 'required|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
        ]);

        Voucher::create($request->all());

        return redirect()->route('vouchers.create')->with('success', 'Voucher berhasil dibuat.');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id); // Mengambil voucher berdasarkan ID atau melempar 404
        return view('vouchers.edit', compact('voucher')); // Mengarahkan ke view edit
    }

    public function update(Request $request, $voucher_id)
    {
        $voucher = Voucher::findOrFail($voucher_id);
    
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher_id . ',voucher_id', // Tambahkan voucher_id
            'discount' => 'required|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
        ]);
    
        $voucher->update($request->all());
    
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus');
    }
}
