<?php

namespace BagOfIdeas\Models\Map\Map;

use BagOfIdeas\Models\Map\MapPointVersion;
use BagOfIdeas\Models\Map\MapPointVersionQuery;
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
 * This class defines the structure of the 'map_point_version' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MapPointVersionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Map.Map.MapPointVersionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'map_point_version';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\BagOfIdeas\\Models\\Map\\MapPointVersion';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Map.MapPointVersion';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the id field
     */
    const COL_ID = 'map_point_version.id';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'map_point_version.title';

    /**
     * the column name for the position field
     */
    const COL_POSITION = 'map_point_version.position';

    /**
     * the column name for the parent_wiki_id field
     */
    const COL_PARENT_WIKI_ID = 'map_point_version.parent_wiki_id';

    /**
     * the column name for the target_wiki_id field
     */
    const COL_TARGET_WIKI_ID = 'map_point_version.target_wiki_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'map_point_version.user_id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'map_point_version.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'map_point_version.updated_at';

    /**
     * the column name for the version field
     */
    const COL_VERSION = 'map_point_version.version';

    /**
     * the column name for the parent_wiki_id_version field
     */
    const COL_PARENT_WIKI_ID_VERSION = 'map_point_version.parent_wiki_id_version';

    /**
     * the column name for the target_wiki_id_version field
     */
    const COL_TARGET_WIKI_ID_VERSION = 'map_point_version.target_wiki_id_version';

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
        self::TYPE_PHPNAME       => array('Id', 'Title', 'Position', 'ParentWikiId', 'TargetWikiId', 'UserId', 'CreatedAt', 'UpdatedAt', 'Version', 'ParentWikiIdVersion', 'TargetWikiIdVersion', ),
        self::TYPE_CAMELNAME     => array('id', 'title', 'position', 'parentWikiId', 'targetWikiId', 'userId', 'createdAt', 'updatedAt', 'version', 'parentWikiIdVersion', 'targetWikiIdVersion', ),
        self::TYPE_COLNAME       => array(MapPointVersionTableMap::COL_ID, MapPointVersionTableMap::COL_TITLE, MapPointVersionTableMap::COL_POSITION, MapPointVersionTableMap::COL_PARENT_WIKI_ID, MapPointVersionTableMap::COL_TARGET_WIKI_ID, MapPointVersionTableMap::COL_USER_ID, MapPointVersionTableMap::COL_CREATED_AT, MapPointVersionTableMap::COL_UPDATED_AT, MapPointVersionTableMap::COL_VERSION, MapPointVersionTableMap::COL_PARENT_WIKI_ID_VERSION, MapPointVersionTableMap::COL_TARGET_WIKI_ID_VERSION, ),
        self::TYPE_FIELDNAME     => array('id', 'title', 'position', 'parent_wiki_id', 'target_wiki_id', 'user_id', 'created_at', 'updated_at', 'version', 'parent_wiki_id_version', 'target_wiki_id_version', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Title' => 1, 'Position' => 2, 'ParentWikiId' => 3, 'TargetWikiId' => 4, 'UserId' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, 'Version' => 8, 'ParentWikiIdVersion' => 9, 'TargetWikiIdVersion' => 10, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'title' => 1, 'position' => 2, 'parentWikiId' => 3, 'targetWikiId' => 4, 'userId' => 5, 'createdAt' => 6, 'updatedAt' => 7, 'version' => 8, 'parentWikiIdVersion' => 9, 'targetWikiIdVersion' => 10, ),
        self::TYPE_COLNAME       => array(MapPointVersionTableMap::COL_ID => 0, MapPointVersionTableMap::COL_TITLE => 1, MapPointVersionTableMap::COL_POSITION => 2, MapPointVersionTableMap::COL_PARENT_WIKI_ID => 3, MapPointVersionTableMap::COL_TARGET_WIKI_ID => 4, MapPointVersionTableMap::COL_USER_ID => 5, MapPointVersionTableMap::COL_CREATED_AT => 6, MapPointVersionTableMap::COL_UPDATED_AT => 7, MapPointVersionTableMap::COL_VERSION => 8, MapPointVersionTableMap::COL_PARENT_WIKI_ID_VERSION => 9, MapPointVersionTableMap::COL_TARGET_WIKI_ID_VERSION => 10, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'title' => 1, 'position' => 2, 'parent_wiki_id' => 3, 'target_wiki_id' => 4, 'user_id' => 5, 'created_at' => 6, 'updated_at' => 7, 'version' => 8, 'parent_wiki_id_version' => 9, 'target_wiki_id_version' => 10, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
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
        $this->setName('map_point_version');
        $this->setPhpName('MapPointVersion');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\BagOfIdeas\\Models\\Map\\MapPointVersion');
        $this->setPackage('Map');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('id', 'Id', 'INTEGER' , 'map_point', 'id', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('position', 'Position', 'VARCHAR', true, 255, null);
        $this->addColumn('parent_wiki_id', 'ParentWikiId', 'INTEGER', true, null, null);
        $this->addColumn('target_wiki_id', 'TargetWikiId', 'INTEGER', false, null, null);
        $this->addColumn('user_id', 'UserId', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addPrimaryKey('version', 'Version', 'INTEGER', true, null, 0);
        $this->addColumn('parent_wiki_id_version', 'ParentWikiIdVersion', 'INTEGER', false, null, 0);
        $this->addColumn('target_wiki_id_version', 'TargetWikiIdVersion', 'INTEGER', false, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('MapPoint', '\\BagOfIdeas\\Models\\Map\\MapPoint', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \BagOfIdeas\Models\Map\MapPointVersion $obj A \BagOfIdeas\Models\Map\MapPointVersion object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getId() || is_scalar($obj->getId()) || is_callable([$obj->getId(), '__toString']) ? (string) $obj->getId() : $obj->getId()), (null === $obj->getVersion() || is_scalar($obj->getVersion()) || is_callable([$obj->getVersion(), '__toString']) ? (string) $obj->getVersion() : $obj->getVersion())]);
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \BagOfIdeas\Models\Map\MapPointVersion object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \BagOfIdeas\Models\Map\MapPointVersion) {
                $key = serialize([(null === $value->getId() || is_scalar($value->getId()) || is_callable([$value->getId(), '__toString']) ? (string) $value->getId() : $value->getId()), (null === $value->getVersion() || is_scalar($value->getVersion()) || is_callable([$value->getVersion(), '__toString']) ? (string) $value->getVersion() : $value->getVersion())]);

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \BagOfIdeas\Models\Map\MapPointVersion object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 8 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 8 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 8 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 8 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 8 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 8 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)])]);
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
            $pks = [];

        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 8 + $offset
                : self::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)
        ];

        return $pks;
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
        return $withPrefix ? MapPointVersionTableMap::CLASS_DEFAULT : MapPointVersionTableMap::OM_CLASS;
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
     * @return array           (MapPointVersion object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MapPointVersionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MapPointVersionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MapPointVersionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MapPointVersionTableMap::OM_CLASS;
            /** @var MapPointVersion $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MapPointVersionTableMap::addInstanceToPool($obj, $key);
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
            $key = MapPointVersionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MapPointVersionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var MapPointVersion $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MapPointVersionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_ID);
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_TITLE);
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_POSITION);
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_PARENT_WIKI_ID);
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_TARGET_WIKI_ID);
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_USER_ID);
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_UPDATED_AT);
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_VERSION);
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_PARENT_WIKI_ID_VERSION);
            $criteria->addSelectColumn(MapPointVersionTableMap::COL_TARGET_WIKI_ID_VERSION);
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
            $criteria->addSelectColumn($alias . '.parent_wiki_id_version');
            $criteria->addSelectColumn($alias . '.target_wiki_id_version');
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
        return Propel::getServiceContainer()->getDatabaseMap(MapPointVersionTableMap::DATABASE_NAME)->getTable(MapPointVersionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MapPointVersionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MapPointVersionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MapPointVersionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a MapPointVersion or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or MapPointVersion object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MapPointVersionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \BagOfIdeas\Models\Map\MapPointVersion) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MapPointVersionTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(MapPointVersionTableMap::COL_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(MapPointVersionTableMap::COL_VERSION, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = MapPointVersionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MapPointVersionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MapPointVersionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the map_point_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MapPointVersionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a MapPointVersion or Criteria object.
     *
     * @param mixed               $criteria Criteria or MapPointVersion object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapPointVersionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from MapPointVersion object
        }


        // Set the correct dbName
        $query = MapPointVersionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MapPointVersionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MapPointVersionTableMap::buildTableMap();
