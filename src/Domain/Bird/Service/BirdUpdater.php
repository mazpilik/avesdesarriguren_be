<?php

namespace App\Domain\Bird\Service;

use App\Domain\Bird\Repository\BirdUpdaterRepository;
use App\Domain\Bird\Data\BirdAditionalData;
use App\Domain\Bird\Repository\BirdFinderRepository;
use App\Domain\Bird\Repository\BirdCreatorRepository;
use App\Domain\Frecuency\Repository\FrecuencyFinderRepository;


final class BirdUpdater
{
    private BirdUpdaterRepository $birdUpdaterRepository;
    private BirdFinderRepository $birdFinderRepository;

    public function __construct(
        BirdUpdaterRepository $birdUpdaterRepository,
        BirdFinderRepository $birdFinderRepository, 
        BirdCreatorRepository $birdCreatorRepository,
        FrecuencyFinderRepository $frecuencyFinderRepository
    )
    {
        $this->birdUpdaterRepository = $birdUpdaterRepository;
        $this->birdFinderRepository = $birdFinderRepository;
        $this->birdCreatorRepository = $birdCreatorRepository;
        $this->frecuencyFinderRepository = $frecuencyFinderRepository;
    }

    public function update(int $id, array $bird_data): string
    {
        // update bird data
        $family_id = $bird_data['familyId'];
        $name = $bird_data['name'];
        $this->birdUpdaterRepository->updateBird($id, $family_id, $name);

        // update additional data
        $additional_data = [];
        foreach($bird_data['birdData'] as $ad_by_language) {
            $ad_by_language['bird_id'] = $id;
            $bird_additional_data = new BirdAditionalData($ad_by_language);
            $additional_data[] = $bird_additional_data->getAllData();
        }
        $this->birdUpdaterRepository->updateAdditionalData($id, $additional_data);
        
        // Todo update frecuency
        // get frecuency by id from repository
        $frecuency_db = $this->birdFinderRepository->findFrecuencyByBirdId($id);
        $frecuency_db_names = [];
        // conver to plain array
        foreach ($frecuency_db as $frecuency_db_item) {
            $frecuency_db_names[] = $frecuency_db_item['frecuencyName'];
        }

        // compare with frecuency from request
        $frecuency = $bird_data['frecuency'];
        $new_frecuency = array_diff($frecuency, $frecuency_db_names);
        $frecuency_to_delete = array_diff($frecuency_db_names, $frecuency);

        if(count($new_frecuency) > 0) {
            $frecuency_new_data = [];
            foreach($new_frecuency as $frecuency_item) {
                // get frecuency id from db
                $frecuency_in_db = $this->frecuencyFinderRepository->findByName($frecuency_item);
                $frecuency_new_data[] = [
                    'bird_id' => $id,
                    'frecuency_id' => $frecuency_in_db['id']
                ];
            }
            // add new frecuency
            $this->birdCreatorRepository->frecuency($frecuency_new_data);
        }

        // if frecuency to delete is not empty
        if(count($frecuency_to_delete) > 0) {
            $frecuency_to_delete_data = [];
            foreach($frecuency_to_delete as $frecuency_item) {
                // get frecuency id from db
                $frecuency_in_db = $this->frecuencyFinderRepository->findByName($frecuency_item);
                $frecuency_to_delete_data[] = [
                    'bird_id' => $id,
                    'frecuency_id' => $frecuency_in_db['id']
                ];
            }
            // delete frecuency
            $this->birdUpdaterRepository->deleteFrecuency($frecuency_to_delete_data);
        }


        // TODO update months
        // get months in db
        $months_db = $this->birdFinderRepository->findMonthsByBirdId($id);
        $months_in_db = [];
        // conver to plain array
        foreach ($months_db as $months_db_item) {
            $months_in_db[] = $months_db_item['p_month'];
        }

        // compare with months from request
        $months = $bird_data['months'];

        $new_months = array_diff($months, $months_in_db);
        $months_to_delete = array_diff($months_in_db, $months);

        if(count($new_months) > 0) {
            $months_new_data = [];
            foreach($new_months as $month) {
                $months_new_data[] = [
                    'bird' => $id,
                    'p_month' => $month
                ];
            }
            // add new months
            $this->birdCreatorRepository->presenceMonths($months_new_data);
        }
        // if months to delete is not empty
        if(count($months_to_delete) > 0) {
            $months_to_delete_data = [];
            foreach($months_to_delete as $month) {
                $months_to_delete_data[] = [
                    'bird_id' => $id,
                    'p_month' => $month
                ];
            }
            // delete months
            $this->birdUpdaterRepository->deletePresenceMonth($months_to_delete_data);
        }
        // update images
        // get images in db
        $images_db = $this->birdFinderRepository->findImagesByBirdId($id);
        $images_in_db = [];
        // conver to plain array
        foreach ($images_db as $images_db_item) {
            $images_in_db[] = $images_db_item['img'];
        }

        // compare with images from request
        $request_images = $bird_data['images'];
        $images_to_delete = array_diff($images_in_db, $request_images);

        // if images to delete is not empty
        // in this case we only look for images that are not in request
        // because upload goes by other way
        if(count($images_to_delete) > 0) {
            $images_to_delete_data = [];
            foreach($images_to_delete as $image) {
                $images_to_delete_data[] = [
                    'bird_id' => $id,
                    'img' => $image
                ];
            }

            // delete images
            $this->birdUpdaterRepository->deleteImages($images_to_delete_data);

            // remove image from server folder public/images/birds
            foreach($images_to_delete as $image) {
                $image_path = './images/birds/' . $image;
                if(file_exists($image_path)) {
                    unlink($image_path);
                }
            }
        }


        // return success message
        return 'SUCCESS_UPDATED';

        
    }
}