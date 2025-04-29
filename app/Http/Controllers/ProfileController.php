<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Tambahkan untuk logging

class ProfileController extends Controller
{
    /**
     * Menampilkan data profil user yang sedang login.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        // Mengembalikan data user yang sedang login dalam format JSON.
        return response()->json(Auth::user());
    }

    /**
     * Mengupdate data profil user yang sedang login.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        
        $user = Auth::user(); // Mendapatkan objek user yang sedang login.

        // Definisi aturan validasi untuk data yang diinputkan.
        $rules = [
            'name' => 'nullable|string|max:255|unique:users,name,' . $user->id, // Nama, opsional, string, maks 255 karakter, unik (kecuali untuk user ini).
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id, // Username, opsional, string, maks 255 karakter, unik (kecuali untuk user ini).
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id, // Email, opsional, email valid, maks 255 karakter, unik (kecuali untuk user ini).
            'avatar' => 'nullable|image|max:2048', // Avatar, opsional, harus gambar, maks 2048 KB (2MB).
        ];

        // Melakukan validasi terhadap data yang diinputkan.
        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembalikan response error.
        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.', // Pesan error standar.
                'errors' => $validator->errors() // Kumpulan error validasi.
            ], 422); // Kode status 422 untuk "Unprocessable Entity".
        }

        // Update data user jika field terkait ada dalam request.
        if ($request->has('username')) {
            $user->username = $request->username;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        // Penanganan upload dan update avatar.
        if ($request->hasFile('avatar')) {
            try {
                // Hapus avatar lama jika ada.
                if ($user->avatar_path) {
                    Storage::disk('public')->delete($user->avatar_path);
                }

                // Simpan avatar baru ke storage.
                $path = $request->file('avatar')->storePublicly('avatars', 'public'); // Menyimpan di direktori 'public/avatars'
                $user->avatar_path = $path; // Simpan path relatif.
                $user->avatar_url = asset('storage/' . $path); // Menghasilkan URL untuk diakses
                 

            } catch (\Exception $e) {
                // Log error jika terjadi masalah saat upload/hapus avatar.
                Log::error('Error handling avatar upload: ' . $e->getMessage());
                return response()->json(['message' => 'Failed to upload avatar.', 'error' => $e->getMessage()], 500); // Kode status 500 untuk Server Error
            }
        }

        // Simpan perubahan data user ke database.
        $user->save();
        $user->avatar_path = asset("storage/$user->avatar_path");

        // Kembalikan response JSON berisi data user yang telah diupdate.
        return response()->json($user);
    }
}

