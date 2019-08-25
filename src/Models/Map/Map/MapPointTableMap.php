<?php

namespace BagOfIdeas\Models\Map\Map;

use BagOfIdeas\Models\Map\MapPoint;
use BagOfIdeas\Models\Map\MapPointQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'map_point' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MapPointTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Map.Map.MapPointTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'map_point';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\BagOfIdeas\\Models\\Map\\MapPoint';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Map.MapPoint';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'map_point.id';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'map_point.title';

    /**
     * the column name for the position field
     */
    const COL_POSITION = 'map_point.position';

    /**
     * the column name for the parent_wiki_id field
     */
    const COL_PARENT_WIKI_ID = 'map_point.parent_wiki_id';

    /**
     * the column name for the target_wiki_id field
     */
    const COL_TARGET_WIKI_ID = 'map_point.target_wiki_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'map_point.user_id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'map_point.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'map_point.updated_at';

    /**
     * the column name for the version field
     */
    const COL_VERSION = 'map_point.version';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Title', 'Position', 'ParentWikiId', 'TargetWikiId', 'UserId', 'CreatedAt', 'UpdatedAt', 'Version', ),
        self::TYPE_CAMELNAME     => array('id', 'title', 'position', 'parentWikiId', 'targetWikiId', 'userId', 'createdAt', 'updatedAt', 'version', ),
        self::TYPE_COLNAME       => array(MapPointTableMap::COL_ID, MapPointTableMap::COL_TITLE, MapPointTableMap::COL_POSITION, MapPointTableMap::COL_PARENT_WIKI_ID, MapPointTableMap::COL_TARGET_WIKI_ID, MapPointTableMap::COL_USER_ID, MapPointTableMap::COL_CREATED_AT, MapPointTableMap::COL_UPDATED_AT, MapPointTableMap::COL_VERSION, ),
        self::TYPE_FIELDNAME     => array('id', 'title', 'position', 'parent_wiki_id', 'target_wiki_id', 'user_id', 'created_at', 'updated_at', 'version', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Title' => 1, 'Position' => 2, 'ParentWikiId' => 3, 'TargetWikiId' => 4, 'UserId' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, 'Version' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'title' => 1, 'position' => 2, 'parentWikiId' => 3, 'targetWikiId' => 4, 'userId' => 5, 'createdAt' => 6, 'updatedAt' => 7, 'version' => 8, ),
        self::TYPE_COLNAME       => array(MapPointTableMap::COL_ID => 0, MapPointTableMap::COL_TITLE => 1, MapPointTableMap::COL_POSITION => 2, MapPointTableMap::COL_PARENT_WIKI_ID => 3, MapPointTableMap::COL_TARGET_WIKI_ID => 4, MapPointTableMap::COL_USER_ID => 5, MapPointTableMap::COL_CREATED_AT => 6, MapPointTableMap::COL_UPDATED_AT => 7, MapPointTableMap::COL_VERSION => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'title' => 1, 'position' => 2, 'parent_wiki_id' => 3, 'target_wiki_id' => 4, 'user_id' => 5, 'created_at' => 6, 'updated_at' => 7, 'version' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('map_point');
        $this->setPhpName('MapPoint');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\BagOfIdeas\\Models\\Map\\MapPoint');
        $this->setPackage('Map');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('position', 'Position', 'VARCHAR', true, 255, null);
        $this->addForeignKey('parent_wiki_id', 'ParentWikiId', 'INTEGER', 'wiki', 'id', true, null, null);
        $this->addForeignKey('target_wiki_id', 'TargetWikiId', 'INTEGER', 'wiki', 'id', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'user', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('version', 'Version', 'INTEGER', false, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ParentWiki', '\\BagOfIdeas\\Models\\Wiki\\Wiki', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':parent_wiki_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('TargetWiki', '\\BagOfIdeas\\Models\\Wiki\\Wiki', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':target_wiki_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('User', '\\BagOfIdeas\\Models\\User\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('MapPointVersion', '\\BagOfIdeas\\Models\\Map\\MapPointVersion', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':id',
    1 => ':id',
  ),
), 'CASCADE', null, 'MapPointVersions', false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'versionable' => array('version_column' => 'version', 'version_table' => '', 'log_created_at' => 'false', 'log_created_by' => 'false', 'log_comment' => 'false', 'version_created_at_column' => 'version_created_at', 'version_created_by_column' => 'version_created_by', 'version_comment_column' => 'version_comment', 'indices' => 'false', ),
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to map_point     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        MapPointVersionTableMap::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? MapPointTableMap::CLASS_DEFAULT : MapPointTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (MapPoint object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MapPointTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MapPointTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MapPointTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MapPointTableMap::OM_CLASS;
            /** @var MapPoint $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MapPointTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = MapPointTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MapPointTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var MapPoint $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MapPointTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(MapPointTableMap::COL_ID);
            $criteria->addSelectColumn(MapPointTableMap::COL_TITLE);
            $criteria->addSelectColumn(MapPointTableMap::COL_POSITION);
            $criteria->addSelectColumn(MapPointTableMap::COL_PARENT_WIKI_ID);
            $criteria->addSelectColumn(MapPointTableMap::COL_TARGET_WIKI_ID);
            $criteria->addSelectColumn(MapPointTableMap::COL_USER_ID);
            $criteria->addSelectColumn(MapPointTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(MapPointTableMap::COL_UPDATED_AT);
            $criteria->addSelectColumn(MapPointTableMap::COL_VERSION);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.position');
            $criteria->addSelectColumn($alias . '.parent_wiki_id');
            $criteria->addSelectColumn($alias . '.target_wiki_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.version');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(MapPointTableMap::DATABASE_NAME)->getTable(MapPointTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MapPointTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MapPointTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MapPointTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a MapPoint or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or MapPoint object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapPointTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \BagOfIdeas\Models\Map\MapPoint) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MapPointTableMap::DATABASE_NAME);
            $criteria->add(MapPointTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = MapPointQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MapPointTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MapPointTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the map_point table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MapPointQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a MapPoint or Criteria object.
     *
     * @param mixed               $criteria Criteria or MapPoint object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapPointTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from MapPoint object
        }

        if ($criteria->containsKey(MapPointTableMap::COL_ID) && $criteria->keyContainsValue(MapPointTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MapPointTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = MapPointQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MapPointTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MapPointTableMap::buildTableMap();
