<?php

namespace BagOfIdeas\Models\Wiki;

use BagOfIdeas\Helpers\Authentication;
use BagOfIdeas\Models\Wiki\Base\Wiki as BaseWiki;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'wiki' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Wiki extends BaseWiki
{

    function getPathParts(): array {
        $parts = preg_split('%\s*[/\,]\s*%', $this->getPath());

        return $parts;

    }

    public function preSave(ConnectionInterface $con = null)
    {
        $this->setUser(Authentication::getUserOrException());
        return parent::preSave($con);
    }


}
