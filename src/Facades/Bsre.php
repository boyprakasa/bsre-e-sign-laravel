<?php

namespace Boyprakasa\BsreESignLaravel\Facades;

use Boyprakasa\BsreESignLaravel\BsreService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array signDocument(string $pdfFilePath, string $nik, string $passphrase)
 * @method static array verifyDocument(string $signedPdfPath)
 *
 * @see \Pengembang\Bsre\BsreService
 */
class Bsre extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return BsreService::class;
    }
}
