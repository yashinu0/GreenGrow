<?php

namespace App\Service;

use App\Entity\Commande;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class FactureService
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generateFacturePDF(Commande $commande): Response
    {
        // Configure Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        // Instantiate Dompdf
        $dompdf = new Dompdf($options);

        // Generate HTML content
        $html = $this->twig->render('facture/facture_pdf.html.twig', [
            'commande' => $commande,
            'date' => new \DateTime(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Generate response
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="facture-' . $commande->getIdCommande() . '.pdf"');

        return $response;
    }
}
