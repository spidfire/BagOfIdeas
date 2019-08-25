<?php


namespace BagOfIdeas\Helpers;


abstract class AbstractController
{
    abstract function handleRequest(Request $requestParams): AbstractResponse;
}