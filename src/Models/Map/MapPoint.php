<?php

namespace BagOfIdeas\Models\Map;

use BagOfIdeas\Helpers\Authentication;
use BagOfIdeas\Models\Map\Base\MapPoint as BaseMapPoint;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'map_point' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class MapPoint extends BaseMapPoint
{

    public function preSave(ConnectionInterface $con = null)
    {
        $this->setUser(Authentication::getUserOrException());
        return parent::preSave($con);
    }
}
