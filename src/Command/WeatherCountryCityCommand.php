<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:country-city',
    description: 'Add a short description for your command',
)]
class WeatherCountryCityCommand{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
        private readonly LocationRepository $locationRepository,
    ) {
    }
    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $country,
        #[Argument('City name')] string $city,
    ): int {
        $location = $this->locationRepository->findByCountryAndCity($country, $city);

        $measurements = $this->weatherUtil->getWeatherForLocation($location);
        $io->writeln(sprintf('Location: %s', $location->getCity()));
        $io->table(['Date', 'Temperature', 'Humidity', 'Rain', "Wind Speed"], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getTemperature().'C',
            $m->getHumidity().'%',
            $m->getRain().'mm',
            $m->getWindSpeed().'km/h',
        ], $measurements));

        return Command::SUCCESS;

    }
}
