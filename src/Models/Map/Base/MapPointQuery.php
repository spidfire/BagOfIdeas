<?php

namespace BagOfIdeas\Models\Map\Base;

use \Exception;
use \PDO;
use BagOfIdeas\Models\Map\MapPoint as ChildMapPoint;
use BagOfIdeas\Models\Map\MapPointQuery as ChildMapPointQuery;
use BagOfIdeas\Models\Map\Map\MapPointTableMap;
use BagOfIdeas\Models\User\User;
use BagOfIdeas\Models\Wiki\Wiki;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'map_point' table.
 *
 *
 *
 * @method     ChildMapPointQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMapPointQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildMapPointQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method     ChildMapPointQuery orderByParentWikiId($order = Criteria::ASC) Order by the parent_wiki_id column
 * @method     ChildMapPointQuery orderByTargetWikiId($order = Criteria::ASC) Order by the target_wiki_id column
 * @method     ChildMapPointQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildMapPointQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildMapPointQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildMapPointQuery orderByVersion($order = Criteria::ASC) Order by the version column
 *
 * @method     ChildMapPointQuery groupById() Group by the id column
 * @method     ChildMapPointQuery groupByTitle() Group by the title column
 * @method     ChildMapPointQuery groupByPosition() Group by the position column
 * @method     ChildMapPointQuery groupByParentWikiId() Group by the parent_wiki_id column
 * @method     ChildMapPointQuery groupByTargetWikiId() Group by the target_wiki_id column
 * @method     ChildMapPointQuery groupByUserId() Group by the user_id column
 * @method     ChildMapPointQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildMapPointQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildMapPointQuery groupByVersion() Group by the version column
 *
 * @method     ChildMapPointQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMapPointQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMapPointQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMapPointQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMapPointQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMapPointQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMapPointQuery leftJoinParentWiki($relationAlias = null) Adds a LEFT JOIN clause to the query using the ParentWiki relation
 * @method     ChildMapPointQuery rightJoinParentWiki($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ParentWiki relation
 * @method     ChildMapPointQuery innerJoinParentWiki($relationAlias = null) Adds a INNER JOIN clause to the query using the ParentWiki relation
 *
 * @method     ChildMapPointQuery joinWithParentWiki($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ParentWiki relation
 *
 * @method     ChildMapPointQuery leftJoinWithParentWiki() Adds a LEFT JOIN clause and with to the query using the ParentWiki relation
 * @method     ChildMapPointQuery rightJoinWithParentWiki() Adds a RIGHT JOIN clause and with to the query using the ParentWiki relation
 * @method     ChildMapPointQuery innerJoinWithParentWiki() Adds a INNER JOIN clause and with to the query using the ParentWiki relation
 *
 * @method     ChildMapPointQuery leftJoinTargetWiki($relationAlias = null) Adds a LEFT JOIN clause to the query using the TargetWiki relation
 * @method     ChildMapPointQuery rightJoinTargetWiki($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TargetWiki relation
 * @method     ChildMapPointQuery innerJoinTargetWiki($relationAlias = null) Adds a INNER JOIN clause to the query using the TargetWiki relation
 *
 * @method     ChildMapPointQuery joinWithTargetWiki($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TargetWiki relation
 *
 * @method     ChildMapPointQuery leftJoinWithTargetWiki() Adds a LEFT JOIN clause and with to the query using the TargetWiki relation
 * @method     ChildMapPointQuery rightJoinWithTargetWiki() Adds a RIGHT JOIN clause and with to the query using the TargetWiki relation
 * @method     ChildMapPointQuery innerJoinWithTargetWiki() Adds a INNER JOIN clause and with to the query using the TargetWiki relation
 *
 * @method     ChildMapPointQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildMapPointQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildMapPointQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildMapPointQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildMapPointQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildMapPointQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildMapPointQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildMapPointQuery leftJoinMapPointVersion($relationAlias = null) Adds a LEFT JOIN clause to the query using the MapPointVersion relation
 * @method     ChildMapPointQuery rightJoinMapPointVersion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MapPointVersion relation
 * @method     ChildMapPointQuery innerJoinMapPointVersion($relationAlias = null) Adds a INNER JOIN clause to the query using the MapPointVersion relation
 *
 * @method     ChildMapPointQuery joinWithMapPointVersion($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MapPointVersion relation
 *
 * @method     ChildMapPointQuery leftJoinWithMapPointVersion() Adds a LEFT JOIN clause and with to the query using the MapPointVersion relation
 * @method     ChildMapPointQuery rightJoinWithMapPointVersion() Adds a RIGHT JOIN clause and with to the query using the MapPointVersion relation
 * @method     ChildMapPointQuery innerJoinWithMapPointVersion() Adds a INNER JOIN clause and with to the query using the MapPointVersion relation
 *
 * @method     \BagOfIdeas\Models\Wiki\WikiQuery|\BagOfIdeas\Models\User\UserQuery|\BagOfIdeas\Models\Map\MapPointVersionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMapPoint findOne(ConnectionInterface $con = null) Return the first ChildMapPoint matching the query
 * @method     ChildMapPoint findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMapPoint matching the query, or a new ChildMapPoint object populated from the query conditions when no match is found
 *
 * @method     ChildMapPoint findOneById(int $id) Return the first ChildMapPoint filtered by the id column
 * @method     ChildMapPoint findOneByTitle(string $title) Return the first ChildMapPoint filtered by the title column
 * @method     ChildMapPoint findOneByPosition(string $position) Return the first ChildMapPoint filtered by the position column
 * @method     ChildMapPoint findOneByParentWikiId(int $parent_wiki_id) Return the first ChildMapPoint filtered by the parent_wiki_id column
 * @method     ChildMapPoint findOneByTargetWikiId(int $target_wiki_id) Return the first ChildMapPoint filtered by the target_wiki_id column
 * @method     ChildMapPoint findOneByUserId(int $user_id) Return the first ChildMapPoint filtered by the user_id column
 * @method     ChildMapPoint findOneByCreatedAt(string $created_at) Return the first ChildMapPoint filtered by the created_at column
 * @method     ChildMapPoint findOneByUpdatedAt(string $updated_at) Return the first ChildMapPoint filtered by the updated_at column
 * @method     ChildMapPoint findOneByVersion(int $version) Return the first ChildMapPoint filtered by the version column *

 * @method     ChildMapPoint requirePk($key, ConnectionInterface $con = null) Return the ChildMapPoint by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPoint requireOne(ConnectionInterface $con = null) Return the first ChildMapPoint matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMapPoint requireOneById(int $id) Return the first ChildMapPoint filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPoint requireOneByTitle(string $title) Return the first ChildMapPoint filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPoint requireOneByPosition(string $position) Return the first ChildMapPoint filtered by the position column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPoint requireOneByParentWikiId(int $parent_wiki_id) Return the first ChildMapPoint filtered by the parent_wiki_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPoint requireOneByTargetWikiId(int $target_wiki_id) Return the first ChildMapPoint filtered by the target_wiki_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPoint requireOneByUserId(int $user_id) Return the first ChildMapPoint filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPoint requireOneByCreatedAt(string $created_at) Return the first ChildMapPoint filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPoint requireOneByUpdatedAt(string $updated_at) Return the first ChildMapPoint filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPoint requireOneByVersion(int $version) Return the first ChildMapPoint filtered by the version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMapPoint[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMapPoint objects based on current ModelCriteria
 * @method     ChildMapPoint[]|ObjectCollection findById(int $id) Return ChildMapPoint objects filtered by the id column
 * @method     ChildMapPoint[]|ObjectCollection findByTitle(string $title) Return ChildMapPoint objects filtered by the title column
 * @method     ChildMapPoint[]|ObjectCollection findByPosition(string $position) Return ChildMapPoint objects filtered by the position column
 * @method     ChildMapPoint[]|ObjectCollection findByParentWikiId(int $parent_wiki_id) Return ChildMapPoint objects filtered by the parent_wiki_id column
 * @method     ChildMapPoint[]|ObjectCollection findByTargetWikiId(int $target_wiki_id) Return ChildMapPoint objects filtered by the target_wiki_id column
 * @method     ChildMapPoint[]|ObjectCollection findByUserId(int $user_id) Return ChildMapPoint objects filtered by the user_id column
 * @method     ChildMapPoint[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildMapPoint objects filtered by the created_at column
 * @method     ChildMapPoint[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildMapPoint objects filtered by the updated_at column
 * @method     ChildMapPoint[]|ObjectCollection findByVersion(int $version) Return ChildMapPoint objects filtered by the version column
 * @method     ChildMapPoint[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MapPointQuery extends ModelCriteria
{

    // versionable behavior

    /**
     * Whether the versioning is enabled
     */
    static $isVersioningEnabled = true;
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \BagOfIdeas\Models\Map\Base\MapPointQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\BagOfIdeas\\Models\\Map\\MapPoint', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMapPointQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMapPointQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMapPointQuery) {
            return $criteria;
        }
        $query = new ChildMapPointQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMapPoint|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MapPointTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MapPointTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMapPoint A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `title`, `position`, `parent_wiki_id`, `target_wiki_id`, `user_id`, `created_at`, `updated_at`, `version` FROM `map_point` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildMapPoint $obj */
            $obj = new ChildMapPoint();
            $obj->hydrate($row);
            MapPointTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildMapPoint|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MapPointTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MapPointTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MapPointTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MapPointTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the position column
     *
     * Example usage:
     * <code>
     * $query->filterByPosition('fooValue');   // WHERE position = 'fooValue'
     * $query->filterByPosition('%fooValue%', Criteria::LIKE); // WHERE position LIKE '%fooValue%'
     * </code>
     *
     * @param     string $position The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($position)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointTableMap::COL_POSITION, $position, $comparison);
    }

    /**
     * Filter the query on the parent_wiki_id column
     *
     * Example usage:
     * <code>
     * $query->filterByParentWikiId(1234); // WHERE parent_wiki_id = 1234
     * $query->filterByParentWikiId(array(12, 34)); // WHERE parent_wiki_id IN (12, 34)
     * $query->filterByParentWikiId(array('min' => 12)); // WHERE parent_wiki_id > 12
     * </code>
     *
     * @see       filterByParentWiki()
     *
     * @param     mixed $parentWikiId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByParentWikiId($parentWikiId = null, $comparison = null)
    {
        if (is_array($parentWikiId)) {
            $useMinMax = false;
            if (isset($parentWikiId['min'])) {
                $this->addUsingAlias(MapPointTableMap::COL_PARENT_WIKI_ID, $parentWikiId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentWikiId['max'])) {
                $this->addUsingAlias(MapPointTableMap::COL_PARENT_WIKI_ID, $parentWikiId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointTableMap::COL_PARENT_WIKI_ID, $parentWikiId, $comparison);
    }

    /**
     * Filter the query on the target_wiki_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTargetWikiId(1234); // WHERE target_wiki_id = 1234
     * $query->filterByTargetWikiId(array(12, 34)); // WHERE target_wiki_id IN (12, 34)
     * $query->filterByTargetWikiId(array('min' => 12)); // WHERE target_wiki_id > 12
     * </code>
     *
     * @see       filterByTargetWiki()
     *
     * @param     mixed $targetWikiId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByTargetWikiId($targetWikiId = null, $comparison = null)
    {
        if (is_array($targetWikiId)) {
            $useMinMax = false;
            if (isset($targetWikiId['min'])) {
                $this->addUsingAlias(MapPointTableMap::COL_TARGET_WIKI_ID, $targetWikiId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($targetWikiId['max'])) {
                $this->addUsingAlias(MapPointTableMap::COL_TARGET_WIKI_ID, $targetWikiId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointTableMap::COL_TARGET_WIKI_ID, $targetWikiId, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(MapPointTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(MapPointTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MapPointTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MapPointTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MapPointTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MapPointTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion(1234); // WHERE version = 1234
     * $query->filterByVersion(array(12, 34)); // WHERE version IN (12, 34)
     * $query->filterByVersion(array('min' => 12)); // WHERE version > 12
     * </code>
     *
     * @param     mixed $version The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(MapPointTableMap::COL_VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(MapPointTableMap::COL_VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointTableMap::COL_VERSION, $version, $comparison);
    }

    /**
     * Filter the query by a related \BagOfIdeas\Models\Wiki\Wiki object
     *
     * @param \BagOfIdeas\Models\Wiki\Wiki|ObjectCollection $wiki The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByParentWiki($wiki, $comparison = null)
    {
        if ($wiki instanceof \BagOfIdeas\Models\Wiki\Wiki) {
            return $this
                ->addUsingAlias(MapPointTableMap::COL_PARENT_WIKI_ID, $wiki->getId(), $comparison);
        } elseif ($wiki instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MapPointTableMap::COL_PARENT_WIKI_ID, $wiki->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByParentWiki() only accepts arguments of type \BagOfIdeas\Models\Wiki\Wiki or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ParentWiki relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function joinParentWiki($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ParentWiki');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ParentWiki');
        }

        return $this;
    }

    /**
     * Use the ParentWiki relation Wiki object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BagOfIdeas\Models\Wiki\WikiQuery A secondary query class using the current class as primary query
     */
    public function useParentWikiQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinParentWiki($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ParentWiki', '\BagOfIdeas\Models\Wiki\WikiQuery');
    }

    /**
     * Filter the query by a related \BagOfIdeas\Models\Wiki\Wiki object
     *
     * @param \BagOfIdeas\Models\Wiki\Wiki|ObjectCollection $wiki The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByTargetWiki($wiki, $comparison = null)
    {
        if ($wiki instanceof \BagOfIdeas\Models\Wiki\Wiki) {
            return $this
                ->addUsingAlias(MapPointTableMap::COL_TARGET_WIKI_ID, $wiki->getId(), $comparison);
        } elseif ($wiki instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MapPointTableMap::COL_TARGET_WIKI_ID, $wiki->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTargetWiki() only accepts arguments of type \BagOfIdeas\Models\Wiki\Wiki or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TargetWiki relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function joinTargetWiki($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TargetWiki');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'TargetWiki');
        }

        return $this;
    }

    /**
     * Use the TargetWiki relation Wiki object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BagOfIdeas\Models\Wiki\WikiQuery A secondary query class using the current class as primary query
     */
    public function useTargetWikiQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTargetWiki($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TargetWiki', '\BagOfIdeas\Models\Wiki\WikiQuery');
    }

    /**
     * Filter the query by a related \BagOfIdeas\Models\User\User object
     *
     * @param \BagOfIdeas\Models\User\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \BagOfIdeas\Models\User\User) {
            return $this
                ->addUsingAlias(MapPointTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MapPointTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \BagOfIdeas\Models\User\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BagOfIdeas\Models\User\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\BagOfIdeas\Models\User\UserQuery');
    }

    /**
     * Filter the query by a related \BagOfIdeas\Models\Map\MapPointVersion object
     *
     * @param \BagOfIdeas\Models\Map\MapPointVersion|ObjectCollection $mapPointVersion the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMapPointQuery The current query, for fluid interface
     */
    public function filterByMapPointVersion($mapPointVersion, $comparison = null)
    {
        if ($mapPointVersion instanceof \BagOfIdeas\Models\Map\MapPointVersion) {
            return $this
                ->addUsingAlias(MapPointTableMap::COL_ID, $mapPointVersion->getId(), $comparison);
        } elseif ($mapPointVersion instanceof ObjectCollection) {
            return $this
                ->useMapPointVersionQuery()
                ->filterByPrimaryKeys($mapPointVersion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMapPointVersion() only accepts arguments of type \BagOfIdeas\Models\Map\MapPointVersion or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MapPointVersion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function joinMapPointVersion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MapPointVersion');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'MapPointVersion');
        }

        return $this;
    }

    /**
     * Use the MapPointVersion relation MapPointVersion object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BagOfIdeas\Models\Map\MapPointVersionQuery A secondary query class using the current class as primary query
     */
    public function useMapPointVersionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMapPointVersion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MapPointVersion', '\BagOfIdeas\Models\Map\MapPointVersionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMapPoint $mapPoint Object to remove from the list of results
     *
     * @return $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function prune($mapPoint = null)
    {
        if ($mapPoint) {
            $this->addUsingAlias(MapPointTableMap::COL_ID, $mapPoint->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the map_point table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapPointTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MapPointTableMap::clearInstancePool();
            MapPointTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapPointTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MapPointTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MapPointTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MapPointTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // versionable behavior

    /**
     * Checks whether versioning is enabled
     *
     * @return boolean
     */
    static public function isVersioningEnabled()
    {
        return self::$isVersioningEnabled;
    }

    /**
     * Enables versioning
     */
    static public function enableVersioning()
    {
        self::$isVersioningEnabled = true;
    }

    /**
     * Disables versioning
     */
    static public function disableVersioning()
    {
        self::$isVersioningEnabled = false;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(MapPointTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(MapPointTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(MapPointTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(MapPointTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(MapPointTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildMapPointQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(MapPointTableMap::COL_CREATED_AT);
    }

} // MapPointQuery
