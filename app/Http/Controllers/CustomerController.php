<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    // Tampilkan daftar pelanggan
    public function index()
    {
        $customers = Customer::all(); // Ambil semua data pelanggan
        return view('customers.index', compact('customers'));
    }

    // Form tambah pelanggan baru
    public function create()
    {
        return view('customers.create');
    }

    // Simpan data pelanggan baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15|unique:customers,phone_number',
            'address' => 'required|string|max:255',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    // Form edit pelanggan
    public function edit($customer_id)
    {
        $customer = Customer::where('customer_id', $customer_id)->firstOrFail(); // Cari berdasarkan customer_id
        return view('customers.edit', compact('customer'));
    }


    // Update data pelanggan
    public function update(Request $request, $customer_id)
    {
        $customer = Customer::where('customer_id', $customer_id)->firstOrFail(); // Cari berdasarkan customer_id

        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15|unique:customers,phone_number,' . $customer->customer_id . ',customer_id',
            'address' => 'required|string|max:255',
        ]);

        // Update data pelanggan
        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }


    // Hapus data pelanggan
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
