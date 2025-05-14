<?php
namespace App\Controller;

use App\Entity\Produit;
use App\Service\QrCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QrCodeController extends AbstractController
{
    private $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    #[Route('/produit/{id}/qrcode', name: 'produit_qrcode')]
    public function generateQrCode(Produit $produit): Response
    {
        $location = $produit->getLocation();

        return $this->qrCodeService->generateQrCode($location);
    }
}