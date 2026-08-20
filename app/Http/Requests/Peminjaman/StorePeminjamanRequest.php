<?php

namespace App\Http\Requests\Peminjaman;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePeminjamanRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tgl_kembali_plan' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.alat_id' => ['required', 'integer', Rule::exists('alat', 'id')],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'tgl_kembali_plan.required' => 'tanggal rencana pengembalian wajib diisi',
            'tgl_kembali_plan.format' => 'format tanggal harus tahun-bulan-hari ()YYY-MM-DD)',
            'tgl_kembali_plan.after_or_equal' => 'tanggal rencana kembali tidak boleh di masa lalu',
            'items.required' => 'anda harus memilih minimal satu alat untuk dipinjam',
            'items.array' => 'format data item yang dikirim harus berupa list/daftar',
            'items.min' => 'anda harus memilih minimal satu alat untuk dipinjam',
        ];
    }

    public function attributes(): array
    {
        return [
            'items.*.alat_id' => 'alat',
            'items.*.jumlah' => 'jumlah_barang',
        ];
    }
}
