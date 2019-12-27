<?php


namespace BagOfIdeas\Modules\Wiki\Controllers;


use BagOfIdeas\Helpers\AbstractController;
use BagOfIdeas\Helpers\AbstractResponse;
use BagOfIdeas\Helpers\Exceptions\PageNotFound;
use BagOfIdeas\Helpers\Request;
use BagOfIdeas\Helpers\TwigResponse;
use BagOfIdeas\Models\Wiki\WikiQuery;
use BagOfIdeas\Modules\Wiki\Helpers\MapHelper;

class EditWiki extends AbstractController
{

    function handleRequest(Request $requestParams): AbstractResponse
    {
        $id = $requestParams->getRoutePart(0);
        $contents = WikiQuery::create()->findOneById($id);

        if($contents === null) {
            throw new PageNotFound('Sorry this page is not found');
        }

        $params = [];
        $post = $requestParams->postParams();
        if(array_key_exists('submit', $post)){
            $contents->setTitle($post['title']);
            $contents->setPath($post['path']);
            $contents->setContent($post['content']);

            if(isset($_FILES['file'])){
                $check = getimagesize($_FILES['file']['tmp_name']);
                if($check !== false) {
                    $parts = explode('.', $_FILES['file']['name']);
                    $filename = md5($_FILES['file']['name']. time()). '.' . end($parts);
                    move_uploaded_file(
                        $_FILES['file']['tmp_name'],
                        STORAGE . '/' .$filename
                    );
                    $contents->setImage($filename);

                } else {
                    $params['error'][] = 'Could not find image';
                }

            }


            $contents->save();

        }


        $params['id'] = $contents->getId();
        $params['title'] = "Edit: " . $contents->getTitle();
        $params['form'] = $contents->toArray();

        $mapHelper = new MapHelper();
        $params = $mapHelper->runEditMap($params, $contents, $requestParams);

        return new TwigResponse($requestParams, 'edit.twig', $params);
    }
}