<?php


namespace BagOfIdeas\Modules\Map\Controllers;


use BagOfIdeas\Helpers\AbstractController;
use BagOfIdeas\Helpers\AbstractResponse;
use BagOfIdeas\Helpers\Exceptions\PageNotFound;
use BagOfIdeas\Helpers\Request;
use BagOfIdeas\Helpers\TextRender;
use BagOfIdeas\Helpers\TwigResponse;
use BagOfIdeas\Models\Wiki\WikiQuery;

class MapView extends AbstractController
{

    function handleRequest(Request $requestParams): AbstractResponse
    {

        $params = [];
        $params['title'] = 'Map view';
        $imagefile = 'Templates/images/worldmap.jpg';
        $params['image'] = EXT_ROOT . $imagefile;
        $r = imagecreatefromjpeg(ROOT . $imagefile);
        $ratio = imagesx($r) / imagesy($r);
        $params['size']['x'] = 50;
        $params['size']['y'] = 50/$ratio;

        $points = [];


//
        $params['points'] = $points;


        return new TwigResponse($requestParams, 'view.twig', $params);
    }


}