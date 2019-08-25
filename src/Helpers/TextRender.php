<?php


namespace BagOfIdeas\Helpers;


class TextRender
{
    function renderText(string $text): string
    {

        if (preg_match_all('/(?:\n|\A)\s*#(.*?)(?:\n|\Z)/is', $text, $results, PREG_SET_ORDER)) {
            foreach ($results as $r) {
                $replace = '<h1>' . $r[1] . '</h1>';
                $text = str_replace($r[0], $replace, $text);
            }
        }

        if (preg_match_all('/<<([0-9.]*?)\|([0-9.]*?)\|(.*?)>>/is', $text, $results, PREG_SET_ORDER)) {
            foreach ($results as $r) {
                $link = '<button class="btn btn-outline-info map-view-button" data-key=' . var_export($r[0], true) . '>' . $r[3] . '</button>';
                $text = str_replace($r[0], $link, $text);
            }
        }


        $text = nl2br($text);


        return $text;
    }
}