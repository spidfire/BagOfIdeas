<?php


namespace BagOfIdeas\Modules\Wiki\Helpers;


use BagOfIdeas\Helpers\Request;
use BagOfIdeas\Models\Wiki\Wiki;

class MapHelper
{
    public function runViewMap(array $params, Wiki $wiki, Request $requestParams): array
    {
        if (!empty($wiki->getImage())) {
            $image = $wiki->getImage();
            $params['image'] = [];
            $params['image']['url'] = EXT_ROOT . 'storage/' . $image;
            $r = imagecreatefromjpeg(ROOT . 'storage/' .$image);
            $ratio = imagesx($r) / imagesy($r);
            $params['image']['x'] = 50;
            $params['image']['y'] = 50/$ratio;

            $points = [];


//
            $params['image']['points'] = $points;


        }


        return $params;
    }
    public function runEditMap(array $params, Wiki $wiki, Request $requestParams): array
    {
        if (!empty($wiki->getImage())) {
            $image = $wiki->getImage();
            $params['image'] = [];
            $params['image']['url'] = EXT_ROOT . 'storage/' . $image;
            $r = imagecreatefromjpeg(ROOT . 'storage/' .$image);
            $ratio = imagesx($r) / imagesy($r);
            $params['image']['x'] = 50;
            $params['image']['y'] = 50/$ratio;

            $points = [];


//
            $params['image']['points'] = $points;


        }


        return $params;
    }


}