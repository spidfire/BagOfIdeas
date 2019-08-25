<?php

namespace BagOfIdeas\Models\Wiki\Base;

use \Exception;
use \PDO;
use BagOfIdeas\Models\Wiki\WikiVersion as ChildWikiVersion;
use BagOfIdeas\Models\Wiki\WikiVersionQuery as ChildWikiVersionQuery;
use BagOfIdeas\Models\Wiki\Map\WikiVersionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'wiki_version' table.
 *
 *
 *
 * @method     ChildWikiVersionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildWikiVersionQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildWikiVersionQuery orderByPath($order = Criteria::ASC) Order by the path column
 * @method     ChildWikiVersionQuery orderByImage($order = Criteria::ASC) Order by the image column
 * @method     ChildWikiVersionQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method     ChildWikiVersionQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildWikiVersionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildWikiVersionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildWikiVersionQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method     ChildWikiVersionQuery orderByMapPointIds($order = Criteria::ASC) Order by the map_point_ids column
 * @method     ChildWikiVersionQuery orderByMapPointVersions($order = Criteria::ASC) Order by the map_point_versions column
 *
 * @method     ChildWikiVersionQuery groupById() Group by the id column
 * @method     ChildWikiVersionQuery groupByTitle() Group by the title column
 * @method     ChildWikiVersionQuery groupByPath() Group by the path column
 * @method     ChildWikiVersionQuery groupByImage() Group by the image column
 * @method     ChildWikiVersionQuery groupByContent() Group by the content column
 * @method     ChildWikiVersionQuery groupByUserId() Group by the user_id column
 * @method     ChildWikiVersionQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildWikiVersionQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildWikiVersionQuery groupByVersion() Group by the version column
 * @method     ChildWikiVersionQuery groupByMapPointIds() Group by the map_point_ids column
 * @method     ChildWikiVersionQuery groupByMapPointVersions() Group by the map_point_versions column
 *
 * @method     ChildWikiVersionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildWikiVersionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildWikiVersionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildWikiVersionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildWikiVersionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildWikiVersionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildWikiVersionQuery leftJoinWiki($relationAlias = null) Adds a LEFT JOIN clause to the query using the Wiki relation
 * @method     ChildWikiVersionQuery rightJoinWiki($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Wiki relation
 * @method     ChildWikiVersionQuery innerJoinWiki($relationAlias = null) Adds a INNER JOIN clause to the query using the Wiki relation
 *
 * @method     ChildWikiVersionQuery joinWithWiki($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Wiki relation
 *
 * @method     ChildWikiVersionQuery leftJoinWithWiki() Adds a LEFT JOIN clause and with to the query using the Wiki relation
 * @method     ChildWikiVersionQuery rightJoinWithWiki() Adds a RIGHT JOIN clause and with to the query using the Wiki relation
 * @method     ChildWikiVersionQuery innerJoinWithWiki() Adds a INNER JOIN clause and with to the query using the Wiki relation
 *
 * @method     \BagOfIdeas\Models\Wiki\WikiQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildWikiVersion findOne(ConnectionInterface $con = null) Return the first ChildWikiVersion matching the query
 * @method     ChildWikiVersion findOneOrCreate(ConnectionInterface $con = null) Return the first ChildWikiVersion matching the query, or a new ChildWikiVersion object populated from the query conditions when no match is found
 *
 * @method     ChildWikiVersion findOneById(int $id) Return the first ChildWikiVersion filtered by the id column
 * @method     ChildWikiVersion findOneByTitle(string $title) Return the first ChildWikiVersion filtered by the title column
 * @method     ChildWikiVersion findOneByPath(string $path) Return the first ChildWikiVersion filtered by the path column
 * @method     ChildWikiVersion findOneByImage(string $image) Return the first ChildWikiVersion filtered by the image column
 * @method     ChildWikiVersion findOneByContent(string $content) Return the first ChildWikiVersion filtered by the content column
 * @method     ChildWikiVersion findOneByUserId(int $user_id) Return the first ChildWikiVersion filtered by the user_id column
 * @method     ChildWikiVersion findOneByCreatedAt(string $created_at) Return the first ChildWikiVersion filtered by the created_at column
 * @method     ChildWikiVersion findOneByUpdatedAt(string $updated_at) Return the first ChildWikiVersion filtered by the updated_at column
 * @method     ChildWikiVersion findOneByVersion(int $version) Return the first ChildWikiVersion filtered by the version column
 * @method     ChildWikiVersion findOneByMapPointIds(array $map_point_ids) Return the first ChildWikiVersion filtered by the map_point_ids column
 * @method     ChildWikiVersion findOneByMapPointVersions(array $map_point_versions) Return the first ChildWikiVersion filtered by the map_point_versions column *

 * @method     ChildWikiVersion requirePk($key, ConnectionInterface $con = null) Return the ChildWikiVersion by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOne(ConnectionInterface $con = null) Return the first ChildWikiVersion matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWikiVersion requireOneById(int $id) Return the first ChildWikiVersion filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOneByTitle(string $title) Return the first ChildWikiVersion filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOneByPath(string $path) Return the first ChildWikiVersion filtered by the path column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOneByImage(string $image) Return the first ChildWikiVersion filtered by the image column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOneByContent(string $content) Return the first ChildWikiVersion filtered by the content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOneByUserId(int $user_id) Return the first ChildWikiVersion filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOneByCreatedAt(string $created_at) Return the first ChildWikiVersion filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOneByUpdatedAt(string $updated_at) Return the first ChildWikiVersion filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOneByVersion(int $version) Return the first ChildWikiVersion filtered by the version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOneByMapPointIds(array $map_point_ids) Return the first ChildWikiVersion filtered by the map_point_ids column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWikiVersion requireOneByMapPointVersions(array $map_point_versions) Return the first ChildWikiVersion filtered by the map_point_versions column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWikiVersion[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildWikiVersion objects based on current ModelCriteria
 * @method     ChildWikiVersion[]|ObjectCollection findById(int $id) Return ChildWikiVersion objects filtered by the id column
 * @method     ChildWikiVersion[]|ObjectCollection findByTitle(string $title) Return ChildWikiVersion objects filtered by the title column
 * @method     ChildWikiVersion[]|ObjectCollection findByPath(string $path) Return ChildWikiVersion objects filtered by the path column
 * @method     ChildWikiVersion[]|ObjectCollection findByImage(string $image) Return ChildWikiVersion objects filtered by the image column
 * @method     ChildWikiVersion[]|ObjectCollection findByContent(string $content) Return ChildWikiVersion objects filtered by the content column
 * @method     ChildWikiVersion[]|ObjectCollection findByUserId(int $user_id) Return ChildWikiVersion objects filtered by the user_id column
 * @method     ChildWikiVersion[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildWikiVersion objects filtered by the created_at column
 * @method     ChildWikiVersion[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildWikiVersion objects filtered by the updated_at column
 * @method     ChildWikiVersion[]|ObjectCollection findByVersion(int $version) Return ChildWikiVersion objects filtered by the version column
 * @method     ChildWikiVersion[]|ObjectCollection findByMapPointIds(array $map_point_ids) Return ChildWikiVersion objects filtered by the map_point_ids column
 * @method     ChildWikiVersion[]|ObjectCollection findByMapPointVersions(array $map_point_versions) Return ChildWikiVersion objects filtered by the map_point_versions column
 * @method     ChildWikiVersion[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class WikiVersionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \BagOfIdeas\Models\Wiki\Base\WikiVersionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\BagOfIdeas\\Models\\Wiki\\WikiVersion', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildWikiVersionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildWikiVersionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildWikiVersionQuery) {
            return $criteria;
        }
        $query = new ChildWikiVersionQuery();
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
     * @return ChildWikiVersion|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(WikiVersionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = WikiVersionTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildWikiVersion A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `title`, `path`, `image`, `content`, `user_id`, `created_at`, `updated_at`, `version`, `map_point_ids`, `map_point_versions` FROM `wiki_version` WHERE `id` = :p0 AND `version` = :p1';
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
            /** @var ChildWikiVersion $obj */
            $obj = new ChildWikiVersion();
            $obj->hydrate($row);
            WikiVersionTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildWikiVersion|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(WikiVersionTableMap::COL_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(WikiVersionTableMap::COL_VERSION, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(WikiVersionTableMap::COL_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(WikiVersionTableMap::COL_VERSION, $key[1], Criteria::EQUAL);
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
     * @see       filterByWiki()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(WikiVersionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(WikiVersionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the path column
     *
     * Example usage:
     * <code>
     * $query->filterByPath('fooValue');   // WHERE path = 'fooValue'
     * $query->filterByPath('%fooValue%', Criteria::LIKE); // WHERE path LIKE '%fooValue%'
     * </code>
     *
     * @param     string $path The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByPath($path = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($path)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_PATH, $path, $comparison);
    }

    /**
     * Filter the query on the image column
     *
     * Example usage:
     * <code>
     * $query->filterByImage('fooValue');   // WHERE image = 'fooValue'
     * $query->filterByImage('%fooValue%', Criteria::LIKE); // WHERE image LIKE '%fooValue%'
     * </code>
     *
     * @param     string $image The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByImage($image = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($image)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_IMAGE, $image, $comparison);
    }

    /**
     * Filter the query on the content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE content = 'fooValue'
     * $query->filterByContent('%fooValue%', Criteria::LIKE); // WHERE content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $content The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_CONTENT, $content, $comparison);
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
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(WikiVersionTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(WikiVersionTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(WikiVersionTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(WikiVersionTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(WikiVersionTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(WikiVersionTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
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
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(WikiVersionTableMap::COL_VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(WikiVersionTableMap::COL_VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_VERSION, $version, $comparison);
    }

    /**
     * Filter the query on the map_point_ids column
     *
     * @param     array $mapPointIds The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByMapPointIds($mapPointIds = null, $comparison = null)
    {
        $key = $this->getAliasedColName(WikiVersionTableMap::COL_MAP_POINT_IDS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($mapPointIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($mapPointIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($mapPointIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_MAP_POINT_IDS, $mapPointIds, $comparison);
    }

    /**
     * Filter the query on the map_point_ids column
     * @param     mixed $mapPointIds The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByMapPointId($mapPointIds = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($mapPointIds)) {
                $mapPointIds = '%| ' . $mapPointIds . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $mapPointIds = '%| ' . $mapPointIds . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(WikiVersionTableMap::COL_MAP_POINT_IDS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $mapPointIds, $comparison);
            } else {
                $this->addAnd($key, $mapPointIds, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_MAP_POINT_IDS, $mapPointIds, $comparison);
    }

    /**
     * Filter the query on the map_point_versions column
     *
     * @param     array $mapPointVersions The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByMapPointVersions($mapPointVersions = null, $comparison = null)
    {
        $key = $this->getAliasedColName(WikiVersionTableMap::COL_MAP_POINT_VERSIONS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($mapPointVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($mapPointVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($mapPointVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_MAP_POINT_VERSIONS, $mapPointVersions, $comparison);
    }

    /**
     * Filter the query on the map_point_versions column
     * @param     mixed $mapPointVersions The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByMapPointVersion($mapPointVersions = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($mapPointVersions)) {
                $mapPointVersions = '%| ' . $mapPointVersions . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $mapPointVersions = '%| ' . $mapPointVersions . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(WikiVersionTableMap::COL_MAP_POINT_VERSIONS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $mapPointVersions, $comparison);
            } else {
                $this->addAnd($key, $mapPointVersions, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(WikiVersionTableMap::COL_MAP_POINT_VERSIONS, $mapPointVersions, $comparison);
    }

    /**
     * Filter the query by a related \BagOfIdeas\Models\Wiki\Wiki object
     *
     * @param \BagOfIdeas\Models\Wiki\Wiki|ObjectCollection $wiki The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildWikiVersionQuery The current query, for fluid interface
     */
    public function filterByWiki($wiki, $comparison = null)
    {
        if ($wiki instanceof \BagOfIdeas\Models\Wiki\Wiki) {
            return $this
                ->addUsingAlias(WikiVersionTableMap::COL_ID, $wiki->getId(), $comparison);
        } elseif ($wiki instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WikiVersionTableMap::COL_ID, $wiki->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByWiki() only accepts arguments of type \BagOfIdeas\Models\Wiki\Wiki or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Wiki relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function joinWiki($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Wiki');

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
            $this->addJoinObject($join, 'Wiki');
        }

        return $this;
    }

    /**
     * Use the Wiki relation Wiki object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BagOfIdeas\Models\Wiki\WikiQuery A secondary query class using the current class as primary query
     */
    public function useWikiQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWiki($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Wiki', '\BagOfIdeas\Models\Wiki\WikiQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildWikiVersion $wikiVersion Object to remove from the list of results
     *
     * @return $this|ChildWikiVersionQuery The current query, for fluid interface
     */
    public function prune($wikiVersion = null)
    {
        if ($wikiVersion) {
            $this->addCond('pruneCond0', $this->getAliasedColName(WikiVersionTableMap::COL_ID), $wikiVersion->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(WikiVersionTableMap::COL_VERSION), $wikiVersion->getVersion(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the wiki_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WikiVersionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            WikiVersionTableMap::clearInstancePool();
            WikiVersionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(WikiVersionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(WikiVersionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            WikiVersionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            WikiVersionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // WikiVersionQuery
