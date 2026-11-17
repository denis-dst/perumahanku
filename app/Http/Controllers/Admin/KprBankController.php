<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KprBank;
use Illuminate\Http\Request;

class KprBankController extends Controller
{
    public function index()
    {
        $banks = KprBank::orderBy('sort_order')->get();

        return view('admin.banks.index', compact('banks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:150',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'max_tenor' => 'required|integer|min:1|max:50',
            'min_dp_percent' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        KprBank::create($validated);

        return redirect()->route('admin.banks.index')->with('success', 'Bank rekanan berhasil ditambahkan.');
    }

    public function update(Request $request, KprBank $bank)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:150',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'max_tenor' => 'required|integer|min:1|max:50',
            'min_dp_percent' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $bank->update($validated);

        return redirect()->route('admin.banks.index')->with('success', 'Data bank rekanan diperbarui.');
    }

    public function destroy(KprBank $bank)
    {
        $bank->delete();

        return redirect()->route('admin.banks.index')->with('success', 'Bank berhasil dihapus.');
    }
}
