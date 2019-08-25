<?php


namespace BagOfIdeas\Modules\Wiki\Controllers;


use BagOfIdeas\Helpers\AbstractController;
use BagOfIdeas\Helpers\AbstractResponse;
use BagOfIdeas\Helpers\Exceptions\PageNotFound;
use BagOfIdeas\Helpers\Request;
use BagOfIdeas\Helpers\TwigResponse;
use BagOfIdeas\Models\Wiki\Wiki;
use BagOfIdeas\Models\Wiki\WikiQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class OverviewWiki extends AbstractController
{

    function handleRequest(Request $requestParams): AbstractResponse
    {
        $contents = WikiQuery::create()->find();
        $hierarchy = [
            'name' => 'Wiki',
            'children' => [],
            'items' => [],
        ];
        foreach ($contents as $c) {
            $pathparts = $c->getPathParts();

            $this->addToHierarchy($pathparts, $hierarchy, $c);

        }

        $params = [];

        $params['title'] = 'Bag of good ol\' ideas';
        $params['hierarchy'] = $hierarchy;

        $lastChanges = WikiQuery::create()
            ->orderByUpdatedAt(Criteria::DESC)
            ->limit(10)
            ->find();
        $params['last_changes'] = [];
        foreach ($lastChanges as $change){
            $params['last_changes'][] = [
                'title' => $change->getTitle(),
                'link' => EXT_ROOT . 'wiki/' . $change->getId(),
                'edit_link' => EXT_ROOT . 'wiki/' . $change->getId() . '/edit'
            ];

        }

        return new TwigResponse($requestParams, 'overview.twig', $params);
    }


    function addToHierarchy($path, array &$location, Wiki $newItem){
        if(count($path) > 0){
            $pos = array_shift($path);

            if(!array_key_exists($pos, $location['children'])){
                $location['children'][$pos] = [
                    'name' => $pos,
                    'children' => [],
                    'items' => []
                ];
            }

            $this->addToHierarchy($path, $location['children'][$pos], $newItem);


        } else {
            $location['items'][] = [
                'title' => $newItem->getTitle(),
                'link' => EXT_ROOT . 'wiki/' . $newItem->getId(),
                'edit_link' => EXT_ROOT . 'wiki/' . $newItem->getId() . '/edit',

            ];


        }

    }



}