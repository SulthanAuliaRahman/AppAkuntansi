<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJurnalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date'       => ['required', 'string'],
            'amount'     => ['required', 'integer', 'min:1000'],
            'desc'       => ['required', 'string', 'max:255'],
            'debit_acc'  => ['required', 'string', 'exists:akun,kode'],
            'credit_acc' => ['required', 'string', 'exists:akun,kode', 'different:debit_acc'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min'          => 'Nilai transaksi minimal Rp 1.000.',
            'credit_acc.different'=> 'Akun kredit tidak boleh sama dengan akun debet.',
            'debit_acc.exists'    => 'Kode akun debet tidak ditemukan.',
            'credit_acc.exists'   => 'Kode akun kredit tidak ditemukan.',
        ];
    }
}
