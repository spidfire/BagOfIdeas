<?php

namespace BagOfIdeas\Models\Wiki\Base;

use \Exception;
use \PDO;
use BagOfIdeas\Models\Map\MapPoint;
use BagOfIdeas\Models\User\User;
use BagOfIdeas\Models\Wiki\Wiki as ChildWiki;
use BagOfIdeas\Models\Wiki\WikiQuery as ChildWikiQuery;
use BagOfIdeas\Models\Wiki\Map\WikiTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'wiki' table.
 *
 *
 *
 * @method     ChildWikiQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildWikiQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildWikiQuery orderByPath($order = Criteria::ASC) Order by the path column
 * @method     ChildWikiQuery orderByImage($order = Criteria::ASC) Order by the image column
 * @method     ChildWikiQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method     ChildWikiQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildWikiQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildWikiQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildWikiQuery orderByVersion($order = Criteria::ASC) Order by the version column
 *
 * @method     ChildWikiQuery groupById() Group by the id column
 * @method     ChildWikiQuery groupByTitle() Group by the title column
 * @method     ChildWikiQuery groupByPath() Group by the path column
 * @method     ChildWikiQuery groupByImage() Group by the image column
 * @method     ChildWikiQuery groupByContent() Group by the content column
 * @method     ChildWikiQuery groupByUserId() Group by the user_id column
 * @method     ChildWikiQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildWikiQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildWikiQuery groupByVersion() Group by the version column
 *
 * @method     ChildWikiQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildWikiQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildWikiQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildWikiQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildWikiQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildWikiQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildWikiQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildWikiQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildWikiQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildWikiQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildWikiQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildWikiQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildWikiQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildWikiQuery leftJoinMapPointRelatedByParentWikiId($relationAlias = null) Adds a LEFT JOIN clause to the query using the MapPointRelatedByParentWikiId relation
 * @method     ChildWikiQuery rightJoinMapPointRelatedByParentWikiId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MapPointRelatedByParentWikiId relation
 * @method     ChildWikiQuery innerJoinMapPointRelatedByParentWikiId($relationAlias = null) Adds a INNER JOIN clause to the query using the MapPointRelatedByParentWikiId relation
 *
 * @method     ChildWikiQuery joinWithMapPointRelatedByParentWikiId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MapPointRelatedByParentWikiId relation
 *
 * @method     ChildWikiQuery leftJoinWithMapPointRelatedByParentWikiId() Adds a LEFT JOIN clause and with to the query using the MapPointRelatedByParentWikiId relation
 * @method     ChildWikiQuery rightJoinWithMapPointRelatedByParentWikiId() Adds a RIGHT JOIN clause and with to the query using the MapPointRelatedByParentWikiId relation
 * @method     ChildWikiQuery innerJoinWithMapPointRelatedByParentWikiId() Adds a INNER JOIN clause and with to the query using the MapPointRelatedByParentWikiId relation
 *
 * @method     ChildWikiQuery leftJoinMapPointRelatedByTargetWikiId($relationAlias = null) Adds a LEFT JOIN clause to the query using the MapPointRelatedByTargetWikiId relation
 * @method     ChildWikiQuery rightJoinMapPointRelatedByTargetWikiId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MapPointRelatedByTargetWikiId relation
 * @method     ChildWikiQuery innerJoinMapPointRelatedByTargetWikiId($relationAlias = null) Adds a INNER JOIN clause to the query using the MapPointRelatedByTargetWikiId relation
 *
 * @method     ChildWikiQuery joinWithMapPointRelatedByTargetWikiId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MapPointRelatedByTargetWikiId relation
 *
 * @method     ChildWikiQuery leftJoinWithMapPointRelatedByTargetWikiId() Adds a LEFT JOIN clause and with to the query using the MapPointRelatedByTargetWikiId relation
 * @method     ChildWikiQuery rightJoinWithMapPointRelatedByTargetWikiId() Adds a RIGHT JOIN clause and with to the query using the MapPointRelatedByTargetWikiId relation
 * @method     ChildWikiQuery innerJoinWithMapPointRelatedByTargetWikiId() Adds a INNER JOIN clause and with to the query using the MapPointRelatedByTargetWikiId relation
 *
 * @method     ChildWikiQuery leftJoinWikiVersion($relationAlias = null) Adds a LEFT JOIN clause to the query using the WikiVersion relation
 * @method     ChildWikiQuery rightJoinWikiVersion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WikiVersion relation
 * @method     ChildWikiQuery innerJoinWikiVersion($relationAlias = null) Adds a INNER JOIN clause to the query using the WikiVersion relation
 *
 * @method     ChildWikiQuery joinWithWikiVersion($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the WikiVersion relation
 *
 * @method     ChildWikiQuery leftJoinWithWikiVersion() Adds a LEFT JOIN clause and with to the query using the WikiVersion relation
 * @method     ChildWikiQuery rightJoinWithWikiVersion() Adds a RIGHT JOIN clause and with to the query using the WikiVersion relation
 * @method     ChildWikiQuery innerJoinWithWikiVersion() Adds a INNER JOIN clause and with to the query using the WikiVersion relation
 *
 * @method     \BagOfIdeas\Models\User\UserQuery|\BagOfIdeas\Models\Map\MapPointQuery|\BagOfIdeas\Models\Wiki\WikiVersionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildWiki findOne(ConnectionInterface $con = null) Return the first ChildWiki matching the query
 * @method     ChildWiki findOneOrCreate(ConnectionInterface $con = null) Return the first ChildWiki matching the query, or a new ChildWiki object populated from the query conditions when no match is found
 *
 * @method     ChildWiki findOneById(int $id) Return the first ChildWiki filtered by the id column
 * @method     ChildWiki findOneByTitle(string $title) Return the first ChildWiki filtered by the title column
 * @method     ChildWiki findOneByPath(string $path) Return the first ChildWiki filtered by the path column
 * @method     ChildWiki findOneByImage(string $image) Return the first ChildWiki filtered by the image column
 * @method     ChildWiki findOneByContent(string $content) Return the first ChildWiki filtered by the content column
 * @method     ChildWiki findOneByUserId(int $user_id) Return the first ChildWiki filtered by the user_id column
 * @method     ChildWiki findOneByCreatedAt(string $created_at) Return the first ChildWiki filtered by the created_at column
 * @method     ChildWiki findOneByUpdatedAt(string $updated_at) Return the first ChildWiki filtered by the updated_at column
 * @method     ChildWiki findOneByVersion(int $version) Return the first ChildWiki filtered by the version column *

 * @method     ChildWiki requirePk($key, ConnectionInterface $con = null) Return the ChildWiki by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWiki requireOne(ConnectionInterface $con = null) Return the first ChildWiki matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWiki requireOneById(int $id) Return the first ChildWiki filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWiki requireOneByTitle(string $title) Return the first ChildWiki filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWiki requireOneByPath(string $path) Return the first ChildWiki filtered by the path column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWiki requireOneByImage(string $image) Return the first ChildWiki filtered by the image column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWiki requireOneByContent(string $content) Return the first ChildWiki filtered by the content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWiki requireOneByUserId(int $user_id) Return the first ChildWiki filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWiki requireOneByCreatedAt(string $created_at) Return the first ChildWiki filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWiki requireOneByUpdatedAt(string $updated_at) Return the first ChildWiki filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWiki requireOneByVersion(int $version) Return the first ChildWiki filtered by the version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWiki[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildWiki objects based on current ModelCriteria
 * @method     ChildWiki[]|ObjectCollection findById(int $id) Return ChildWiki objects filtered by the id column
 * @method     ChildWiki[]|ObjectCollection findByTitle(string $title) Return ChildWiki objects filtered by the title column
 * @method     ChildWiki[]|ObjectCollection findByPath(string $path) Return ChildWiki objects filtered by the path column
 * @method     ChildWiki[]|ObjectCollection findByImage(string $image) Return ChildWiki objects filtered by the image column
 * @method     ChildWiki[]|ObjectCollection findByContent(string $content) Return ChildWiki objects filtered by the content column
 * @method     ChildWiki[]|ObjectCollection findByUserId(int $user_id) Return ChildWiki objects filtered by the user_id column
 * @method     ChildWiki[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildWiki objects filtered by the created_at column
 * @method     ChildWiki[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildWiki objects filtered by the updated_at column
 * @method     ChildWiki[]|ObjectCollection findByVersion(int $version) Return ChildWiki objects filtered by the version column
 * @method     ChildWiki[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class WikiQuery extends ModelCriteria
{

    // versionable behavior

    /**
     * Whether the versioning is enabled
     */
    static $isVersioningEnabled = true;
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \BagOfIdeas\Models\Wiki\Base\WikiQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\BagOfIdeas\\Models\\Wiki\\Wiki', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildWikiQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildWikiQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildWikiQuery) {
            return $criteria;
        }
        $query = new ChildWikiQuery();
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
     * @return ChildWiki|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(WikiTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = WikiTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildWiki A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `title`, `path`, `image`, `content`, `user_id`, `created_at`, `updated_at`, `version` FROM `wiki` WHERE `id` = :p0';
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
            /** @var ChildWiki $obj */
            $obj = new ChildWiki();
            $obj->hydrate($row);
            WikiTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildWiki|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(WikiTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(WikiTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(WikiTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(WikiTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterByPath($path = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($path)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiTableMap::COL_PATH, $path, $comparison);
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterByImage($image = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($image)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiTableMap::COL_IMAGE, $image, $comparison);
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiTableMap::COL_CONTENT, $content, $comparison);
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(WikiTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(WikiTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(WikiTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(WikiTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(WikiTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(WikiTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(WikiTableMap::COL_VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(WikiTableMap::COL_VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WikiTableMap::COL_VERSION, $version, $comparison);
    }

    /**
     * Filter the query by a related \BagOfIdeas\Models\User\User object
     *
     * @param \BagOfIdeas\Models\User\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildWikiQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \BagOfIdeas\Models\User\User) {
            return $this
                ->addUsingAlias(WikiTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WikiTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildWikiQuery The current query, for fluid interface
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
     * Filter the query by a related \BagOfIdeas\Models\Map\MapPoint object
     *
     * @param \BagOfIdeas\Models\Map\MapPoint|ObjectCollection $mapPoint the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildWikiQuery The current query, for fluid interface
     */
    public function filterByMapPointRelatedByParentWikiId($mapPoint, $comparison = null)
    {
        if ($mapPoint instanceof \BagOfIdeas\Models\Map\MapPoint) {
            return $this
                ->addUsingAlias(WikiTableMap::COL_ID, $mapPoint->getParentWikiId(), $comparison);
        } elseif ($mapPoint instanceof ObjectCollection) {
            return $this
                ->useMapPointRelatedByParentWikiIdQuery()
                ->filterByPrimaryKeys($mapPoint->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMapPointRelatedByParentWikiId() only accepts arguments of type \BagOfIdeas\Models\Map\MapPoint or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MapPointRelatedByParentWikiId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function joinMapPointRelatedByParentWikiId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MapPointRelatedByParentWikiId');

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
            $this->addJoinObject($join, 'MapPointRelatedByParentWikiId');
        }

        return $this;
    }

    /**
     * Use the MapPointRelatedByParentWikiId relation MapPoint object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BagOfIdeas\Models\Map\MapPointQuery A secondary query class using the current class as primary query
     */
    public function useMapPointRelatedByParentWikiIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMapPointRelatedByParentWikiId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MapPointRelatedByParentWikiId', '\BagOfIdeas\Models\Map\MapPointQuery');
    }

    /**
     * Filter the query by a related \BagOfIdeas\Models\Map\MapPoint object
     *
     * @param \BagOfIdeas\Models\Map\MapPoint|ObjectCollection $mapPoint the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildWikiQuery The current query, for fluid interface
     */
    public function filterByMapPointRelatedByTargetWikiId($mapPoint, $comparison = null)
    {
        if ($mapPoint instanceof \BagOfIdeas\Models\Map\MapPoint) {
            return $this
                ->addUsingAlias(WikiTableMap::COL_ID, $mapPoint->getTargetWikiId(), $comparison);
        } elseif ($mapPoint instanceof ObjectCollection) {
            return $this
                ->useMapPointRelatedByTargetWikiIdQuery()
                ->filterByPrimaryKeys($mapPoint->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMapPointRelatedByTargetWikiId() only accepts arguments of type \BagOfIdeas\Models\Map\MapPoint or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MapPointRelatedByTargetWikiId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function joinMapPointRelatedByTargetWikiId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MapPointRelatedByTargetWikiId');

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
            $this->addJoinObject($join, 'MapPointRelatedByTargetWikiId');
        }

        return $this;
    }

    /**
     * Use the MapPointRelatedByTargetWikiId relation MapPoint object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BagOfIdeas\Models\Map\MapPointQuery A secondary query class using the current class as primary query
     */
    public function useMapPointRelatedByTargetWikiIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMapPointRelatedByTargetWikiId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MapPointRelatedByTargetWikiId', '\BagOfIdeas\Models\Map\MapPointQuery');
    }

    /**
     * Filter the query by a related \BagOfIdeas\Models\Wiki\WikiVersion object
     *
     * @param \BagOfIdeas\Models\Wiki\WikiVersion|ObjectCollection $wikiVersion the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildWikiQuery The current query, for fluid interface
     */
    public function filterByWikiVersion($wikiVersion, $comparison = null)
    {
        if ($wikiVersion instanceof \BagOfIdeas\Models\Wiki\WikiVersion) {
            return $this
                ->addUsingAlias(WikiTableMap::COL_ID, $wikiVersion->getId(), $comparison);
        } elseif ($wikiVersion instanceof ObjectCollection) {
            return $this
                ->useWikiVersionQuery()
                ->filterByPrimaryKeys($wikiVersion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByWikiVersion() only accepts arguments of type \BagOfIdeas\Models\Wiki\WikiVersion or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the WikiVersion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function joinWikiVersion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('WikiVersion');

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
            $this->addJoinObject($join, 'WikiVersion');
        }

        return $this;
    }

    /**
     * Use the WikiVersion relation WikiVersion object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BagOfIdeas\Models\Wiki\WikiVersionQuery A secondary query class using the current class as primary query
     */
    public function useWikiVersionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWikiVersion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'WikiVersion', '\BagOfIdeas\Models\Wiki\WikiVersionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildWiki $wiki Object to remove from the list of results
     *
     * @return $this|ChildWikiQuery The current query, for fluid interface
     */
    public function prune($wiki = null)
    {
        if ($wiki) {
            $this->addUsingAlias(WikiTableMap::COL_ID, $wiki->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the wiki table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WikiTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            WikiTableMap::clearInstancePool();
            WikiTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(WikiTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(WikiTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            WikiTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            WikiTableMap::clearRelatedInstancePool();

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
     * @return     $this|ChildWikiQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(WikiTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildWikiQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(WikiTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildWikiQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(WikiTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildWikiQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(WikiTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildWikiQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(WikiTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildWikiQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(WikiTableMap::COL_CREATED_AT);
    }

} // WikiQuery
