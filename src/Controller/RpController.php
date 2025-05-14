<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RpController extends AbstractController
{
    #[Route('/rp', name: 'app_rp')]
    public function index(): Response
    {
        return $this->render('rp/index.html.twig', [
            'controller_name' => 'RpController',
        ]);
    }
}
