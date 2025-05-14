<?php

namespace App\Service;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\HttpFoundation\Response;

class QrCodeService
{
    public function generateQrCode(string $location): Response
    {
        // Générer le lien Google Maps avec les coordonnées de localisation
        $googleMapsUrl = sprintf('https://www.google.com/maps?q=%s', urlencode($location));

        $qrCode = new QrCode($googleMapsUrl);
        $qrCode->setSize(300); // Utilisez la méthode correcte pour définir la taille
        $qrCode->setMargin(10); // Utilisez la méthode correcte pour définir la marge

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return new Response($result->getString(), Response::HTTP_OK, ['Content-Type' => $result->getMimeType()]);
    }
}