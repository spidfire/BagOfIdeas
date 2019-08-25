<?php

namespace BagOfIdeas\Models\Map\Base;

use \Exception;
use \PDO;
use BagOfIdeas\Models\Map\MapPointVersion as ChildMapPointVersion;
use BagOfIdeas\Models\Map\MapPointVersionQuery as ChildMapPointVersionQuery;
use BagOfIdeas\Models\Map\Map\MapPointVersionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'map_point_version' table.
 *
 *
 *
 * @method     ChildMapPointVersionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMapPointVersionQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildMapPointVersionQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method     ChildMapPointVersionQuery orderByParentWikiId($order = Criteria::ASC) Order by the parent_wiki_id column
 * @method     ChildMapPointVersionQuery orderByTargetWikiId($order = Criteria::ASC) Order by the target_wiki_id column
 * @method     ChildMapPointVersionQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildMapPointVersionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildMapPointVersionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildMapPointVersionQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method     ChildMapPointVersionQuery orderByParentWikiIdVersion($order = Criteria::ASC) Order by the parent_wiki_id_version column
 * @method     ChildMapPointVersionQuery orderByTargetWikiIdVersion($order = Criteria::ASC) Order by the target_wiki_id_version column
 *
 * @method     ChildMapPointVersionQuery groupById() Group by the id column
 * @method     ChildMapPointVersionQuery groupByTitle() Group by the title column
 * @method     ChildMapPointVersionQuery groupByPosition() Group by the position column
 * @method     ChildMapPointVersionQuery groupByParentWikiId() Group by the parent_wiki_id column
 * @method     ChildMapPointVersionQuery groupByTargetWikiId() Group by the target_wiki_id column
 * @method     ChildMapPointVersionQuery groupByUserId() Group by the user_id column
 * @method     ChildMapPointVersionQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildMapPointVersionQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildMapPointVersionQuery groupByVersion() Group by the version column
 * @method     ChildMapPointVersionQuery groupByParentWikiIdVersion() Group by the parent_wiki_id_version column
 * @method     ChildMapPointVersionQuery groupByTargetWikiIdVersion() Group by the target_wiki_id_version column
 *
 * @method     ChildMapPointVersionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMapPointVersionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMapPointVersionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMapPointVersionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMapPointVersionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMapPointVersionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMapPointVersionQuery leftJoinMapPoint($relationAlias = null) Adds a LEFT JOIN clause to the query using the MapPoint relation
 * @method     ChildMapPointVersionQuery rightJoinMapPoint($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MapPoint relation
 * @method     ChildMapPointVersionQuery innerJoinMapPoint($relationAlias = null) Adds a INNER JOIN clause to the query using the MapPoint relation
 *
 * @method     ChildMapPointVersionQuery joinWithMapPoint($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MapPoint relation
 *
 * @method     ChildMapPointVersionQuery leftJoinWithMapPoint() Adds a LEFT JOIN clause and with to the query using the MapPoint relation
 * @method     ChildMapPointVersionQuery rightJoinWithMapPoint() Adds a RIGHT JOIN clause and with to the query using the MapPoint relation
 * @method     ChildMapPointVersionQuery innerJoinWithMapPoint() Adds a INNER JOIN clause and with to the query using the MapPoint relation
 *
 * @method     \BagOfIdeas\Models\Map\MapPointQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMapPointVersion findOne(ConnectionInterface $con = null) Return the first ChildMapPointVersion matching the query
 * @method     ChildMapPointVersion findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMapPointVersion matching the query, or a new ChildMapPointVersion object populated from the query conditions when no match is found
 *
 * @method     ChildMapPointVersion findOneById(int $id) Return the first ChildMapPointVersion filtered by the id column
 * @method     ChildMapPointVersion findOneByTitle(string $title) Return the first ChildMapPointVersion filtered by the title column
 * @method     ChildMapPointVersion findOneByPosition(string $position) Return the first ChildMapPointVersion filtered by the position column
 * @method     ChildMapPointVersion findOneByParentWikiId(int $parent_wiki_id) Return the first ChildMapPointVersion filtered by the parent_wiki_id column
 * @method     ChildMapPointVersion findOneByTargetWikiId(int $target_wiki_id) Return the first ChildMapPointVersion filtered by the target_wiki_id column
 * @method     ChildMapPointVersion findOneByUserId(int $user_id) Return the first ChildMapPointVersion filtered by the user_id column
 * @method     ChildMapPointVersion findOneByCreatedAt(string $created_at) Return the first ChildMapPointVersion filtered by the created_at column
 * @method     ChildMapPointVersion findOneByUpdatedAt(string $updated_at) Return the first ChildMapPointVersion filtered by the updated_at column
 * @method     ChildMapPointVersion findOneByVersion(int $version) Return the first ChildMapPointVersion filtered by the version column
 * @method     ChildMapPointVersion findOneByParentWikiIdVersion(int $parent_wiki_id_version) Return the first ChildMapPointVersion filtered by the parent_wiki_id_version column
 * @method     ChildMapPointVersion findOneByTargetWikiIdVersion(int $target_wiki_id_version) Return the first ChildMapPointVersion filtered by the target_wiki_id_version column *

 * @method     ChildMapPointVersion requirePk($key, ConnectionInterface $con = null) Return the ChildMapPointVersion by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOne(ConnectionInterface $con = null) Return the first ChildMapPointVersion matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMapPointVersion requireOneById(int $id) Return the first ChildMapPointVersion filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOneByTitle(string $title) Return the first ChildMapPointVersion filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOneByPosition(string $position) Return the first ChildMapPointVersion filtered by the position column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOneByParentWikiId(int $parent_wiki_id) Return the first ChildMapPointVersion filtered by the parent_wiki_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOneByTargetWikiId(int $target_wiki_id) Return the first ChildMapPointVersion filtered by the target_wiki_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOneByUserId(int $user_id) Return the first ChildMapPointVersion filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOneByCreatedAt(string $created_at) Return the first ChildMapPointVersion filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOneByUpdatedAt(string $updated_at) Return the first ChildMapPointVersion filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOneByVersion(int $version) Return the first ChildMapPointVersion filtered by the version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOneByParentWikiIdVersion(int $parent_wiki_id_version) Return the first ChildMapPointVersion filtered by the parent_wiki_id_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMapPointVersion requireOneByTargetWikiIdVersion(int $target_wiki_id_version) Return the first ChildMapPointVersion filtered by the target_wiki_id_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMapPointVersion[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMapPointVersion objects based on current ModelCriteria
 * @method     ChildMapPointVersion[]|ObjectCollection findById(int $id) Return ChildMapPointVersion objects filtered by the id column
 * @method     ChildMapPointVersion[]|ObjectCollection findByTitle(string $title) Return ChildMapPointVersion objects filtered by the title column
 * @method     ChildMapPointVersion[]|ObjectCollection findByPosition(string $position) Return ChildMapPointVersion objects filtered by the position column
 * @method     ChildMapPointVersion[]|ObjectCollection findByParentWikiId(int $parent_wiki_id) Return ChildMapPointVersion objects filtered by the parent_wiki_id column
 * @method     ChildMapPointVersion[]|ObjectCollection findByTargetWikiId(int $target_wiki_id) Return ChildMapPointVersion objects filtered by the target_wiki_id column
 * @method     ChildMapPointVersion[]|ObjectCollection findByUserId(int $user_id) Return ChildMapPointVersion objects filtered by the user_id column
 * @method     ChildMapPointVersion[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildMapPointVersion objects filtered by the created_at column
 * @method     ChildMapPointVersion[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildMapPointVersion objects filtered by the updated_at column
 * @method     ChildMapPointVersion[]|ObjectCollection findByVersion(int $version) Return ChildMapPointVersion objects filtered by the version column
 * @method     ChildMapPointVersion[]|ObjectCollection findByParentWikiIdVersion(int $parent_wiki_id_version) Return ChildMapPointVersion objects filtered by the parent_wiki_id_version column
 * @method     ChildMapPointVersion[]|ObjectCollection findByTargetWikiIdVersion(int $target_wiki_id_version) Return ChildMapPointVersion objects filtered by the target_wiki_id_version column
 * @method     ChildMapPointVersion[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MapPointVersionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \BagOfIdeas\Models\Map\Base\MapPointVersionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\BagOfIdeas\\Models\\Map\\MapPointVersion', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMapPointVersionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMapPointVersionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMapPointVersionQuery) {
            return $criteria;
        }
        $query = new ChildMapPointVersionQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$id, $version] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMapPointVersion|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MapPointVersionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MapPointVersionTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildMapPointVersion A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `title`, `position`, `parent_wiki_id`, `target_wiki_id`, `user_id`, `created_at`, `updated_at`, `version`, `parent_wiki_id_version`, `target_wiki_id_version` FROM `map_point_version` WHERE `id` = :p0 AND `version` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildMapPointVersion $obj */
            $obj = new ChildMapPointVersion();
            $obj->hydrate($row);
            MapPointVersionTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildMapPointVersion|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MapPointVersionTableMap::COL_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MapPointVersionTableMap::COL_VERSION, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MapPointVersionTableMap::COL_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MapPointVersionTableMap::COL_VERSION, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @see       filterByMapPoint()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($position)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_POSITION, $position, $comparison);
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
     * @param     mixed $parentWikiId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByParentWikiId($parentWikiId = null, $comparison = null)
    {
        if (is_array($parentWikiId)) {
            $useMinMax = false;
            if (isset($parentWikiId['min'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_PARENT_WIKI_ID, $parentWikiId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentWikiId['max'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_PARENT_WIKI_ID, $parentWikiId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_PARENT_WIKI_ID, $parentWikiId, $comparison);
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
     * @param     mixed $targetWikiId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByTargetWikiId($targetWikiId = null, $comparison = null)
    {
        if (is_array($targetWikiId)) {
            $useMinMax = false;
            if (isset($targetWikiId['min'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_TARGET_WIKI_ID, $targetWikiId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($targetWikiId['max'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_TARGET_WIKI_ID, $targetWikiId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_TARGET_WIKI_ID, $targetWikiId, $comparison);
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
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
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
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_VERSION, $version, $comparison);
    }

    /**
     * Filter the query on the parent_wiki_id_version column
     *
     * Example usage:
     * <code>
     * $query->filterByParentWikiIdVersion(1234); // WHERE parent_wiki_id_version = 1234
     * $query->filterByParentWikiIdVersion(array(12, 34)); // WHERE parent_wiki_id_version IN (12, 34)
     * $query->filterByParentWikiIdVersion(array('min' => 12)); // WHERE parent_wiki_id_version > 12
     * </code>
     *
     * @param     mixed $parentWikiIdVersion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByParentWikiIdVersion($parentWikiIdVersion = null, $comparison = null)
    {
        if (is_array($parentWikiIdVersion)) {
            $useMinMax = false;
            if (isset($parentWikiIdVersion['min'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_PARENT_WIKI_ID_VERSION, $parentWikiIdVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentWikiIdVersion['max'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_PARENT_WIKI_ID_VERSION, $parentWikiIdVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_PARENT_WIKI_ID_VERSION, $parentWikiIdVersion, $comparison);
    }

    /**
     * Filter the query on the target_wiki_id_version column
     *
     * Example usage:
     * <code>
     * $query->filterByTargetWikiIdVersion(1234); // WHERE target_wiki_id_version = 1234
     * $query->filterByTargetWikiIdVersion(array(12, 34)); // WHERE target_wiki_id_version IN (12, 34)
     * $query->filterByTargetWikiIdVersion(array('min' => 12)); // WHERE target_wiki_id_version > 12
     * </code>
     *
     * @param     mixed $targetWikiIdVersion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByTargetWikiIdVersion($targetWikiIdVersion = null, $comparison = null)
    {
        if (is_array($targetWikiIdVersion)) {
            $useMinMax = false;
            if (isset($targetWikiIdVersion['min'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_TARGET_WIKI_ID_VERSION, $targetWikiIdVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($targetWikiIdVersion['max'])) {
                $this->addUsingAlias(MapPointVersionTableMap::COL_TARGET_WIKI_ID_VERSION, $targetWikiIdVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapPointVersionTableMap::COL_TARGET_WIKI_ID_VERSION, $targetWikiIdVersion, $comparison);
    }

    /**
     * Filter the query by a related \BagOfIdeas\Models\Map\MapPoint object
     *
     * @param \BagOfIdeas\Models\Map\MapPoint|ObjectCollection $mapPoint The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function filterByMapPoint($mapPoint, $comparison = null)
    {
        if ($mapPoint instanceof \BagOfIdeas\Models\Map\MapPoint) {
            return $this
                ->addUsingAlias(MapPointVersionTableMap::COL_ID, $mapPoint->getId(), $comparison);
        } elseif ($mapPoint instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MapPointVersionTableMap::COL_ID, $mapPoint->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMapPoint() only accepts arguments of type \BagOfIdeas\Models\Map\MapPoint or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MapPoint relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function joinMapPoint($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MapPoint');

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
            $this->addJoinObject($join, 'MapPoint');
        }

        return $this;
    }

    /**
     * Use the MapPoint relation MapPoint object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BagOfIdeas\Models\Map\MapPointQuery A secondary query class using the current class as primary query
     */
    public function useMapPointQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMapPoint($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MapPoint', '\BagOfIdeas\Models\Map\MapPointQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMapPointVersion $mapPointVersion Object to remove from the list of results
     *
     * @return $this|ChildMapPointVersionQuery The current query, for fluid interface
     */
    public function prune($mapPointVersion = null)
    {
        if ($mapPointVersion) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MapPointVersionTableMap::COL_ID), $mapPointVersion->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MapPointVersionTableMap::COL_VERSION), $mapPointVersion->getVersion(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the map_point_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapPointVersionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MapPointVersionTableMap::clearInstancePool();
            MapPointVersionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MapPointVersionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MapPointVersionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MapPointVersionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MapPointVersionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MapPointVersionQuery
