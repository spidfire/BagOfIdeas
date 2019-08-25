<?php


namespace BagOfIdeas\Modules\Wiki;


use BagOfIdeas\Helpers\AbstractController;
use BagOfIdeas\Helpers\AbstractResponse;
use BagOfIdeas\Helpers\Request;
use BagOfIdeas\Helpers\TwigResponse;
use BagOfIdeas\Modules\Map\Controllers\MapView;
use BagOfIdeas\Modules\Wiki\Controllers\EditWiki;
use BagOfIdeas\Modules\Wiki\Controllers\OverviewWiki;
use BagOfIdeas\Modules\Wiki\Controllers\ViewWiki;

class Route extends AbstractController
{

    function handleRequest(Request $requestParams): AbstractResponse
    {

        $urlParam = $requestParams->getRoutePart(0);
        if(ctype_digit($urlParam)){
            if($requestParams->getRoutePart(1) === 'edit'){
                return (new EditWiki())->handleRequest($requestParams);
            }
            return (new ViewWiki())->handleRequest($requestParams);
        } else {
            return (new OverviewWiki())->handleRequest($requestParams);
        }
    }
}