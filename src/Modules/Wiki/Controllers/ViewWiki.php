<?php


namespace BagOfIdeas\Modules\Wiki\Controllers;


use BagOfIdeas\Helpers\AbstractController;
use BagOfIdeas\Helpers\AbstractResponse;
use BagOfIdeas\Helpers\Exceptions\PageNotFound;
use BagOfIdeas\Helpers\Request;
use BagOfIdeas\Helpers\TextRender;
use BagOfIdeas\Helpers\TwigResponse;
use BagOfIdeas\Models\Wiki\WikiQuery;
use BagOfIdeas\Modules\Wiki\Helpers\MapHelper;

class ViewWiki extends AbstractController
{

    function handleRequest(Request $requestParams): AbstractResponse
    {
        $id = $requestParams->getRoutePart(0);
        $contents = WikiQuery::create()->findOneById($id);

        if($contents === null) {
            throw new PageNotFound('Sorry this page is not found');
        }

        $params = [];
        $params['title'] = $contents->getPath() . ' -> ' . $contents->getTitle();
        $params['id'] = $contents->getId();
        $renderer = new TextRender();
        $params['plain_contents'] = $contents->getContent();
        $params['contents'] = $renderer->renderText($contents->getContent());
        /** @noinspection PhpUnhandledExceptionInspection */
        $params['created'] = $contents->getCreatedAt('d-M-Y H:i');
        /** @noinspection PhpUnhandledExceptionInspection */
        $params['updated'] = $contents->getUpdatedAt('d-M-Y H:i');


        $mapHelper = new MapHelper();
        $params = $mapHelper->runViewMap($params, $contents, $requestParams);

        return new TwigResponse($requestParams, 'view.twig', $params);
    }
}