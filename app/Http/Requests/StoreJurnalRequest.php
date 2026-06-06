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
            'date'                    => ['required', 'date'],
            'desc'                    => ['required', 'string', 'max:255'],
            'entries'                 => ['required', 'array', 'min:2'],
            'entries.*.type'          => ['required', 'in:debet,kredit'],
            'entries.*.akun_kode'     => ['required', 'string', 'exists:akuns,kode_akun'],
            'entries.*.jumlah'        => ['required', 'integer', 'min:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required'               => 'Tanggal transaksi wajib diisi.',
            'date.date'                   => 'Format tanggal tidak valid.',
            'desc.required'               => 'Keterangan transaksi wajib diisi.',
            'desc.max'                    => 'Keterangan maksimal 255 karakter.',
            'entries.required'            => 'Minimal harus ada satu entri akun.',
            'entries.min'                 => 'Minimal harus ada 2 baris entri (debet & kredit).',
            'entries.*.type.required'     => 'Tipe akun (debet/kredit) wajib dipilih.',
            'entries.*.type.in'           => 'Tipe akun hanya boleh debet atau kredit.',
            'entries.*.akun_kode.required'=> 'Akun wajib dipilih.',
            'entries.*.akun_kode.exists'  => 'Kode akun tidak ditemukan dalam database.',
            'entries.*.jumlah.required'   => 'Jumlah wajib diisi.',
            'entries.*.jumlah.integer'    => 'Jumlah harus berupa angka bulat.',
            'entries.*.jumlah.min'        => 'Jumlah minimal Rp 1.000.',
        ];
    }

    /**
     * Validasi tambahan setelah rules standar lulus:
     * total debet HARUS sama dengan total kredit.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $entries = $this->input('entries', []);

            $totalDebet  = 0;
            $totalKredit = 0;

            foreach ($entries as $entry) {
                $jumlah = (int) ($entry['jumlah'] ?? 0);
                if (($entry['type'] ?? '') === 'debet') {
                    $totalDebet += $jumlah;
                } else {
                    $totalKredit += $jumlah;
                }
            }

            if ($totalDebet === 0 && $totalKredit === 0) {
                $validator->errors()->add('entries', 'Entri jurnal tidak boleh kosong.');
                return;
            }

            if ($totalDebet !== $totalKredit) {
                $selisih = abs($totalDebet - $totalKredit);
                $validator->errors()->add(
                    'balance',
                    sprintf(
                        'Jurnal tidak balance! Total Debet (Rp %s) ≠ Total Kredit (Rp %s). Selisih: Rp %s.',
                        number_format($totalDebet, 0, ',', '.'),
                        number_format($totalKredit, 0, ',', '.'),
                        number_format($selisih, 0, ',', '.')
                    )
                );
            }
        });
    }
}