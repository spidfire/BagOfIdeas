<?php


namespace BagOfIdeas\Modules\Map;


use BagOfIdeas\Helpers\AbstractController;
use BagOfIdeas\Helpers\AbstractResponse;
use BagOfIdeas\Helpers\Request;
use BagOfIdeas\Modules\Map\Controllers\MapView;

class Route extends AbstractController
{

    function handleRequest(Request $requestParams): AbstractResponse
    {
        $urlParam = $requestParams->getRoutePart(0);
        if(ctype_digit($urlParam)){
            return (new MapView())->handleRequest($requestParams);
        } else {
            return (new MapView())->handleRequest($requestParams);
        }
    }
}