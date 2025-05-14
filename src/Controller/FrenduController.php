<?php
// src/Controller/FrenduController.php
namespace App\Controller;

use App\Entity\Rendu;
use App\Entity\Alerte;
use App\Repository\AlerteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class FrenduController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/frendu', name: 'app_frendu')]
    public function index(): Response
    {
        // Fetch weather data from OpenWeatherMap API
        $weatherData = $this->fetchWeatherData();

        return $this->render('frendu/index.html.twig', [
            'controller_name' => 'FrenduController',
            'weather' => $weatherData,
        ]);
    }

    #[Route('/save-plant', name: 'app_save_plant', methods: ['POST'])]
    public function savePlant(Request $request, EntityManagerInterface $entityManager, AlerteRepository $alerteRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        // Determine plant status
        $plantStatus = $this->determinePlantStatus(
            $data['light'],
            $data['humidity'],
            $data['temperature'],
            $data['water']
        );

        // Save sensor data to the `rendu` table
        $rendu = new Rendu();
        $rendu->setMessageRendu("Sensor data for plant: " . $data['name']);
        $rendu->setTypeRendu($plantStatus['type']);
        $rendu->setDateEnvoiRendu(new \DateTime());

        $entityManager->persist($rendu);
        $entityManager->flush();

        // If the plant is in critical condition, save an alert (but check for duplicates first)
        if ($plantStatus['isCritical']) {
            // Check if an alert for this plant already exists
            $existingAlert = $alerteRepository->findOneBy([
                'Niveau_urgence_alerte' => $plantStatus['message'],
                'temps_limite_alerte' => new \DateTime(), // Use the current date to avoid duplicates
            ]);

            if (!$existingAlert) {
                $alerte = new Alerte();
                $alerte->setNiveauUrgenceAlerte($plantStatus['message']);
                $alerte->setTempsLimiteAlerte(new \DateTime());

                $entityManager->persist($alerte);
                $entityManager->flush();
            }
        }

        return $this->json(['status' => 'success', 'message' => 'Plant and sensor data saved successfully.']);
    }

    private function fetchWeatherData(): array
    {
        $apiKey = '157bca46bd79bed9a7cbccd94a725212';
        $lat = '36.898861';
        $lon = '10.184440';
        $url = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric";

        try {
            $response = $this->httpClient->request('GET', $url);
            $data = $response->toArray();

            // Extract current weather
            $currentWeather = [
                'temperature' => $data['list'][0]['main']['temp'],
                'weather' => $data['list'][0]['weather'][0]['main'],
                'description' => $data['list'][0]['weather'][0]['description'],
            ];

            // Extract 3-day forecast
            $forecast = [];
            $currentDate = date('Y-m-d');
            $daysAdded = 0;

            foreach ($data['list'] as $entry) {
                $date = date('Y-m-d', $entry['dt']);
                if ($date !== $currentDate && $daysAdded < 3) {
                    $forecast[] = [
                        'date' => $date,
                        'temperature' => $entry['main']['temp'],
                        'weather' => $entry['weather'][0]['main'],
                        'description' => $entry['weather'][0]['description'],
                    ];
                    $currentDate = $date;
                    $daysAdded++;
                }
            }

            return [
                'current' => $currentWeather,
                'forecast' => $forecast,
            ];
        } catch (\Exception $e) {
            return [
                'current' => [
                    'temperature' => 'N/A',
                    'weather' => 'N/A',
                    'description' => 'N/A',
                ],
                'forecast' => [],
            ];
        }
    }

    private function determinePlantStatus($light, $humidity, $temperature, $water): array
    {
        // Remove percentage signs and units for comparison
        $lightValue = (float) $light;
        $humidityValue = (float) $humidity;
        $temperatureValue = (float) $temperature;
        $waterValue = (float) $water;

        // Define optimal values
        $optimalValues = [
            'light' => ['min' => 30, 'max' => 70],
            'humidity' => ['min' => 40, 'max' => 60],
            'temperature' => ['min' => 14, 'max' => 33],
            'water' => ['min' => 100, 'max' => 300],
        ];

        // Check if values are outside optimal ranges
        $isLightCritical = $lightValue < $optimalValues['light']['min'] || $lightValue > $optimalValues['light']['max'];
        $isHumidityCritical = $humidityValue < $optimalValues['humidity']['min'] || $humidityValue > $optimalValues['humidity']['max'];
        $isTemperatureCritical = $temperatureValue < $optimalValues['temperature']['min'] || $temperatureValue > $optimalValues['temperature']['max'];
        $isWaterCritical = $waterValue < $optimalValues['water']['min'] || $waterValue > $optimalValues['water']['max'];

        // If any value is critical, the plant status is critical
        $isCritical = $isLightCritical || $isHumidityCritical || $isTemperatureCritical || $isWaterCritical;

        // Generate status message
        $statusMessage = "";
        if ($isCritical) {
            $statusMessage = "Attention requise : ";

            if ($isLightCritical) {
                $statusMessage .= "niveau de lumière anormal. ";
            }
            if ($isHumidityCritical) {
                $statusMessage .= "niveau d'humidité anormal. ";
            }
            if ($isTemperatureCritical) {
                $statusMessage .= "température anormale. ";
            }
            if ($isWaterCritical) {
                $statusMessage .= "niveau d'eau anormal. ";
            }
        } else {
            $statusMessage = "Votre plante est en bonne santé";
        }

        return [
            'type' => $isCritical ? 'Plant Not Safe' : 'Safe Plant',
            'message' => $statusMessage,
            'isCritical' => $isCritical,
        ];
    }
}