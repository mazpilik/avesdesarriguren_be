<?php

namespace App\Domain\Bird\Data;

final class BirdAditionalData
{
    private int $bird_id;
    private string $name;
    private string $language;
    private string $summary;
    private string $bird_length;
    private string $wingspan;
    private string $identification;
    private string $singing;
    private string $moving;
    private string $habitat;
    private string $feeding;
    private string $reproduction;
    private string $population;
    private string $conservation_threat;
    private string $world_distribution;
    private string $peninsula_distribution;

    public function __construct(array $bird_data)
    {
        $this->bird_id = $bird_data['bird_id'];
        $this->name = $bird_data['name'];
        $this->language = $bird_data['lang'];
        $this->summary = $bird_data['summary'];
        $this->bird_length = $bird_data['birdLength'];
        $this->wingspan = $bird_data['wingspan'];
        $this->identification = $bird_data['identification'];
        $this->singing = $bird_data['singing'];
        $this->moving = $bird_data['moving'];
        $this->habitat = $bird_data['habitat'];
        $this->feeding = $bird_data['feeding'];
        $this->reproduction = $bird_data['reproduction'];
        $this->population = $bird_data['population'];
        $this->conservation_threat = $bird_data['conservationThreat'];
        $this->world_distribution = $bird_data['worldDistribution'];
        $this->peninsula_distribution = $bird_data['peninsulaDistribution'];
    }

    public function getBirdId(): int
    {
        return $this->bird_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getBirdLength(): string
    {
        return $this->bird_length;
    }

    public function getWingspan(): string
    {
        return $this->wingspan;
    }

    public function getIdentification(): string
    {
        return $this->identification;
    }

    public function getSinging(): string
    {
        return $this->singing;
    }

    public function getMoving(): string
    {
        return $this->moving;
    }

    public function getHabitat(): string
    {
        return $this->habitat;
    }

    public function getFeeding(): string
    {
        return $this->feeding;
    }

    public function getReproduction(): string
    {
        return $this->reproduction;
    }

    public function getPopulation(): string
    {
        return $this->population;
    }

    public function getConservationThreat(): string
    {
        return $this->conservation_threat;
    }

    public function getWorldDistribution(): string
    {
        return $this->world_distribution;
    }

    public function getPeninsulaDistribution(): string
    {
        return $this->peninsula_distribution;
    }

    public function getAllData(): array
    {
        return [
            'bird_id' => $this->bird_id,
            'name' => $this->name,
            'summary' => $this->summary,
            'bird_length' => $this->bird_length,
            'wingspan' => $this->wingspan,
            'identification' => $this->identification,
            'singing' => $this->singing,
            'moving' => $this->moving,
            'habitat' => $this->habitat,
            'feeding' => $this->feeding,
            'reproduction' => $this->reproduction,
            'population' => $this->population,
            'conservation_threats' => $this->conservation_threat,
            'world_distribution' => $this->world_distribution,
            'peninsula_distribution' => $this->peninsula_distribution,
            'language' => $this->language,
        ];
    }
};