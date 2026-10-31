<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $format,
        WeatherUtil $weatherUtil,
        #[MapQueryParameter] bool $twig = false,
    ): Response
    {
        $forecasts = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            return $this->render("weather_api/index.$format.twig", [
                'city' => $city,
                'country' => $country,
                'forecasts' => $forecasts
            ]);
        }

        if ($format === 'json')
            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => array_map(fn(Forecast $m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'temperatureC' => $m->getTemperature(),
                    'temperatureF' => $m->getFahrenheit(),
                    'humidity' => $m->getHumidity(),
                    'rain' => $m->getRain(),
                    'windSpeed' => $m->getWindSpeed(),
                ], $forecasts),

            ]);

        if ($format === 'csv') {
            $rows = ['city,country,date,temperature C,temperature F,humidity,rain,windSpeed'];
            foreach ($forecasts as $forecast) {
                $rows[] = sprintf(
                    '%s,%s,%s,%s,%s,%s,%s,%s',
                    $city,
                    $country,
                    $forecast->getDate()->format('Y-m-d'),
                    $forecast->getTemperature(),
                    $forecast->getFahrenheit(),
                    $forecast->getHumidity(),
                    $forecast->getRain(),
                    $forecast->getWindSpeed()
                );
            }

            return new Response(
                implode("\n", $rows),
                Response::HTTP_OK,

            );
        }

        return new Response();
    }
}
