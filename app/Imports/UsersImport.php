<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        Log::info('Import row:', $row);

        return new User([
            'name'     => $row['name'],   // kolom di Excel harus sesuai header
            // 'email'    => $row['email'],
            // 'password' => Hash::make($row['password']),
            'token' => $row['token'],
            'is_admin' => $row['is_admin'] ?? 0,
        ]);
    }
    public function rules(): array
    {
        return [
            // '*.email' => ['email', 'unique:users,email'],
            '*.name'  => ['required'],
            // '*.password' => ['required'],
            '*.token' => ['required'],
            '*.is_admin' => ['required'],
        ];
    }
}
