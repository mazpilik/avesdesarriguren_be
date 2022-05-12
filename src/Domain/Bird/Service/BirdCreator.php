<?php

namespace App\Domain\Bird\Service;

use App\Domain\Bird\Repository\BirdCreatorRepository;
use App\Domain\Frecuency\Repository\FrecuencyFinderRepository;
use App\Domain\Bird\Data\BirdAditionalData;


final class BirdCreator
{
    private BirdCreatorRepository $birdCreatorRepository;
    private FrecuencyFinderRepository $frecuencyFinderRepository;

    public function __construct(BirdCreatorRepository $birdCreatorRepository, FrecuencyFinderRepository $frecuencyFinderRepository)
    {
        $this->birdCreatorRepository = $birdCreatorRepository;
        $this->frecuencyFinderRepository = $frecuencyFinderRepository;
    }

    public function create(array $bird_data): string
    {
        // get basic data and create bird
        $family_id = $bird_data['family_id'];
        $name = $bird_data['name'];
        $bird_id = $this->birdCreatorRepository->create($family_id, $name);

        // add additional data and bind to bird
        $additional_raw_data_array = $bird_data['additionalData'];
        $additional_data_array = [];
        foreach ($additional_raw_data_array as $additional_data_by_language) {
            $additional_data_by_language['bird_id'] = $bird_id;

            // we use an auxiliar class to ensure that the data is in the correct format
            $bird_additional_data = new BirdAditionalData($additional_data_by_language);
            $additional_data_array[] = $bird_additional_data->getAllData();
        }
        // return json_encode($additional_data_array);
        $this->birdCreatorRepository->additionalData($additional_data_array);

        // if frecuency is set, add it to bird
        if (isset($bird_data['frecuency'])) {
            $frecuency = $bird_data['frecuency'];
            $frecuencies = $this->frecuencyFinderRepository->findAll();
            $frecuency_bird = [];

            // prepare frecuency for bird data
            foreach ($frecuency as $frecuency_item) {
                foreach ($frecuencies as $frecuency_item_db) {
                    if ($frecuency_item_db['name'] === $frecuency_item) {
                        $frecuency_bird[] = [
                            'bird_id' => $bird_id,
                            'frecuency_id' => $frecuency_item_db['id']
                        ];
                    }
                }
            }
            $this->birdCreatorRepository->frecuency($frecuency_bird);
        }

        // if presence months is set, add it to bird
        if (isset($bird_data['presence_months'])) {

            // prepare presence months for bird data
            $presence_months = $bird_data['presence_months'];
            $presence_months_bird = [];
            forEach($presence_months as $month) {
                $presence_months_bird[] = [
                    'bird_id' => $bird_id,
                    'p_month' => $month
                ];
            }
            $this->birdCreatorRepository->presenceMonths($presence_months_bird);
        }

        return $bird_id;
    }

    // bind image to bird
    public function bindImage(int $bird_id, string $filename): void
    {
        $this->birdCreatorRepository->bindImage($bird_id, $filename);
    }

    // check if bird exists
    public function checkBirdExists(int $bird_id): bool
    {
        return $this->birdCreatorRepository->checkBirdExists($bird_id);
    }
}