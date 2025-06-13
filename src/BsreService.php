<?php

namespace Boyprakasa\BsreESignLaravel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BsreService
{
    protected Client $client;
    protected string $apiUrl;
    protected string $username;
    protected string $password;

    public function __construct(array $config)
    {
        $this->apiUrl = $config['host'];
        $this->username = $config['username'];
        $this->password = $config['password'];

        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout'  => $config['timeout'] ?? 30,
        ]);
    }

    /**
     * Contoh fungsi untuk melakukan tanda tangan elektronik.
     *
     * @param string $pdfFilePath Path ke file PDF yang akan ditandatangani.
     * @param string $nik Nomor Induk Kependudukan.
     * @param string $passphrase Passphrase pengguna.
     * @return array
     */
    public function signDocument(string $pdfFilePath, string $nik, string $passphrase): array
    {
        // Logika untuk mengirim request ke endpoint TTE BSR-e
        // Ini adalah contoh, sesuaikan dengan dokumentasi API BSR-e yang sebenarnya.
        try {
            $response = $this->client->post('/api/sign/pdf', [
                'auth' => [$this->username, $this->password],
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($pdfFilePath, 'r'),
                    ],
                    [
                        'name'     => 'nik',
                        'contents' => $nik,
                    ],
                    [
                        'name'     => 'passphrase',
                        'contents' => $passphrase,
                    ],
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            // Lakukan error handling yang baik
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Contoh fungsi lain, misalnya untuk verifikasi tanda tangan.
     *
     * @param string $signedPdfPath Path ke file PDF yang sudah ditandatangani.
     * @return array
     */
    public function verifyDocument(string $signedPdfPath): array
    {
        // Logika untuk mengirim request verifikasi ke API BSR-e
        try {
            $response = $this->client->post('/api/verify/pdf', [
                'auth' => [$this->username, $this->password],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($signedPdfPath, 'r')
                    ]
                ]
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}
