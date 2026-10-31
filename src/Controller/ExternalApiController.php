<?php

namespace App\Controller;

use App\Form\ExternalApiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ExternalApiController extends AbstractController
{
    #[Route('/externalapi', name: 'app_external_api')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ExternalApiType::class);
        $weatherData = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $longitude = $data['longitude'];
            $latitude = $data['latitude'];

            $apiUrl = sprintf(
                'https://api.open-meteo.com/v1/forecast?latitude=%s&longitude=%s&daily=temperature_2m_max,temperature_2m_min,precipitation_sum&timezone=UTC',
                $latitude,
                $longitude
            );

            $response = file_get_contents($apiUrl);
            $weatherData = json_decode($response, true);
        }

        return $this->render('external_api/index.html.twig', [
            'form' => $form->createView(),
            'weatherData' => $weatherData,
        ]);
    }
}
