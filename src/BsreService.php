<?php

namespace Boyprakasa\BsreESignLaravel;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class BsreService
{
    protected string $apiUrl;
    protected string $username;
    protected string $password;

    public function __construct(array $config)
    {
        $this->apiUrl = $config['host'];
        $this->username = $config['username'];
        $this->password = $config['password'];
    }

    /**
     * Contoh fungsi untuk melakukan tanda tangan elektronik.
     *
     * @param string $pdfFilePath Path ke file PDF yang akan ditandatangani.
     * @param string $nik Nomor Induk Kependudukan.
     * @param string $passphrase Passphrase pengguna.
     * @return array
     */
    public function signDocument(string $pdfFilePath, string $nik, string $passphrase): string|array
    {
        $pdfContent = file_get_contents($pdfFilePath);
        if ($pdfContent === false) {
            return ['error' => true, 'message' => 'Gagal membaca file dari path: ' . $pdfFilePath];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)
        ])
            ->attach('file', $pdfContent, basename($pdfFilePath), ['Content-Type' => 'application/pdf'])
            ->post($this->apiUrl . '/api/sign/pdf', [
                'nik' => $nik,
                'passphrase' => $passphrase,
                'tampilan' => 'invisible'
            ]);

        if ($response->failed()) {
            return [
                'error' => true,
                'message' => $response->json('message') ?? $response->body(),
                'status_code' => $response->status()
            ];
        }

        return $response->body();
    }

    /**
     * --- METODE BARU ---
     * Mengirim dokumen untuk diverifikasi tanda tangannya oleh BSR-e.
     *
     * @param string $pdfFilePath Path ke file PDF yang akan diverifikasi.
     * @return array Hasil verifikasi dari API dalam bentuk array.
     */
    public function verifyDocument(string $pdfFilePath): array
    {
        $pdfContent = file_get_contents($pdfFilePath);
        if ($pdfContent === false) {
            return ['error' => true, 'message' => 'Gagal membaca file dari path: ' . $pdfFilePath];
        }

        // Request ke endpoint verifikasi hanya butuh file dan otentikasi
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)
        ])
            ->attach('file', $pdfContent, basename($pdfFilePath), ['Content-Type' => 'application/pdf'])
            ->post($this->apiUrl . '/api/sign/verify'); // Endpoint verifikasi

        if ($response->failed()) {
            return [
                'error' => true,
                'message' => $response->json('message') ?? $response->body(),
                'status_code' => $response->status()
            ];
        }

        // Jika berhasil, kembalikan body JSON sebagai array
        // Respons verifikasi biasanya selalu JSON
        return $response->json() ?? ['error' => true, 'message' => 'Invalid JSON response from server.'];
    }
}
