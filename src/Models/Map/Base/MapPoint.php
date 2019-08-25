<?php

namespace BagOfIdeas\Models\Map\Base;

use \DateTime;
use \Exception;
use \PDO;
use BagOfIdeas\Models\Map\MapPoint as ChildMapPoint;
use BagOfIdeas\Models\Map\MapPointQuery as ChildMapPointQuery;
use BagOfIdeas\Models\Map\MapPointVersion as ChildMapPointVersion;
use BagOfIdeas\Models\Map\MapPointVersionQuery as ChildMapPointVersionQuery;
use BagOfIdeas\Models\Map\Map\MapPointTableMap;
use BagOfIdeas\Models\Map\Map\MapPointVersionTableMap;
use BagOfIdeas\Models\User\User;
use BagOfIdeas\Models\User\UserQuery;
use BagOfIdeas\Models\Wiki\Wiki;
use BagOfIdeas\Models\Wiki\WikiQuery;
use BagOfIdeas\Models\Wiki\WikiVersionQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'map_point' table.
 *
 *
 *
 * @package    propel.generator.Map.Base
 */
abstract class MapPoint implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\BagOfIdeas\\Models\\Map\\Map\\MapPointTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the title field.
     *
     * @var        string
     */
    protected $title;

    /**
     * The value for the position field.
     *
     * @var        string
     */
    protected $position;

    /**
     * The value for the parent_wiki_id field.
     *
     * @var        int
     */
    protected $parent_wiki_id;

    /**
     * The value for the target_wiki_id field.
     *
     * @var        int
     */
    protected $target_wiki_id;

    /**
     * The value for the user_id field.
     *
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * The value for the version field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $version;

    /**
     * @var        Wiki
     */
    protected $aParentWiki;

    /**
     * @var        Wiki
     */
    protected $aTargetWiki;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildMapPointVersion[] Collection to store aggregation of ChildMapPointVersion objects.
     */
    protected $collMapPointVersions;
    protected $collMapPointVersionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // versionable behavior


    /**
     * @var bool
     */
    protected $enforceVersion = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMapPointVersion[]
     */
    protected $mapPointVersionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->version = 0;
    }

    /**
     * Initializes internal state of BagOfIdeas\Models\Map\Base\MapPoint object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>MapPoint</code> instance.  If
     * <code>obj</code> is an instance of <code>MapPoint</code>, delegates to
     * <code>equals(MapPoint)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|MapPoint The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [position] column value.
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Get the [parent_wiki_id] column value.
     *
     * @return int
     */
    public function getParentWikiId()
    {
        return $this->parent_wiki_id;
    }

    /**
     * Get the [target_wiki_id] column value.
     *
     * @return int
     */
    public function getTargetWikiId()
    {
        return $this->target_wiki_id;
    }

    /**
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Get the [version] column value.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[MapPointTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[MapPointTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [position] column.
     *
     * @param string $v new value
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     */
    public function setPosition($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->position !== $v) {
            $this->position = $v;
            $this->modifiedColumns[MapPointTableMap::COL_POSITION] = true;
        }

        return $this;
    } // setPosition()

    /**
     * Set the value of [parent_wiki_id] column.
     *
     * @param int $v new value
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     */
    public function setParentWikiId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->parent_wiki_id !== $v) {
            $this->parent_wiki_id = $v;
            $this->modifiedColumns[MapPointTableMap::COL_PARENT_WIKI_ID] = true;
        }

        if ($this->aParentWiki !== null && $this->aParentWiki->getId() !== $v) {
            $this->aParentWiki = null;
        }

        return $this;
    } // setParentWikiId()

    /**
     * Set the value of [target_wiki_id] column.
     *
     * @param int $v new value
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     */
    public function setTargetWikiId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->target_wiki_id !== $v) {
            $this->target_wiki_id = $v;
            $this->modifiedColumns[MapPointTableMap::COL_TARGET_WIKI_ID] = true;
        }

        if ($this->aTargetWiki !== null && $this->aTargetWiki->getId() !== $v) {
            $this->aTargetWiki = null;
        }

        return $this;
    } // setTargetWikiId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[MapPointTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setUserId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MapPointTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MapPointTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [version] column.
     *
     * @param int $v new value
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->version !== $v) {
            $this->version = $v;
            $this->modifiedColumns[MapPointTableMap::COL_VERSION] = true;
        }

        return $this;
    } // setVersion()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->version !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MapPointTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MapPointTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MapPointTableMap::translateFieldName('Position', TableMap::TYPE_PHPNAME, $indexType)];
            $this->position = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MapPointTableMap::translateFieldName('ParentWikiId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->parent_wiki_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MapPointTableMap::translateFieldName('TargetWikiId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->target_wiki_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : MapPointTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : MapPointTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : MapPointTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : MapPointTableMap::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = MapPointTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\BagOfIdeas\\Models\\Map\\MapPoint'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aParentWiki !== null && $this->parent_wiki_id !== $this->aParentWiki->getId()) {
            $this->aParentWiki = null;
        }
        if ($this->aTargetWiki !== null && $this->target_wiki_id !== $this->aTargetWiki->getId()) {
            $this->aTargetWiki = null;
        }
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MapPointTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMapPointQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aParentWiki = null;
            $this->aTargetWiki = null;
            $this->aUser = null;
            $this->collMapPointVersions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see MapPoint::setDeleted()
     * @see MapPoint::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapPointTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMapPointQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapPointTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            // versionable behavior
            if ($this->isVersioningNecessary()) {
                $this->setVersion($this->isNew() ? 1 : $this->getLastVersionNumber($con) + 1);
                $createVersion = true; // for postSave hook
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(MapPointTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(MapPointTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(MapPointTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                // versionable behavior
                if (isset($createVersion)) {
                    $this->addVersion($con);
                }
                MapPointTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aParentWiki !== null) {
                if ($this->aParentWiki->isModified() || $this->aParentWiki->isNew()) {
                    $affectedRows += $this->aParentWiki->save($con);
                }
                $this->setParentWiki($this->aParentWiki);
            }

            if ($this->aTargetWiki !== null) {
                if ($this->aTargetWiki->isModified() || $this->aTargetWiki->isNew()) {
                    $affectedRows += $this->aTargetWiki->save($con);
                }
                $this->setTargetWiki($this->aTargetWiki);
            }

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->mapPointVersionsScheduledForDeletion !== null) {
                if (!$this->mapPointVersionsScheduledForDeletion->isEmpty()) {
                    \BagOfIdeas\Models\Map\MapPointVersionQuery::create()
                        ->filterByPrimaryKeys($this->mapPointVersionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->mapPointVersionsScheduledForDeletion = null;
                }
            }

            if ($this->collMapPointVersions !== null) {
                foreach ($this->collMapPointVersions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[MapPointTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MapPointTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MapPointTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(MapPointTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(MapPointTableMap::COL_POSITION)) {
            $modifiedColumns[':p' . $index++]  = '`position`';
        }
        if ($this->isColumnModified(MapPointTableMap::COL_PARENT_WIKI_ID)) {
            $modifiedColumns[':p' . $index++]  = '`parent_wiki_id`';
        }
        if ($this->isColumnModified(MapPointTableMap::COL_TARGET_WIKI_ID)) {
            $modifiedColumns[':p' . $index++]  = '`target_wiki_id`';
        }
        if ($this->isColumnModified(MapPointTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }
        if ($this->isColumnModified(MapPointTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(MapPointTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(MapPointTableMap::COL_VERSION)) {
            $modifiedColumns[':p' . $index++]  = '`version`';
        }

        $sql = sprintf(
            'INSERT INTO `map_point` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`position`':
                        $stmt->bindValue($identifier, $this->position, PDO::PARAM_STR);
                        break;
                    case '`parent_wiki_id`':
                        $stmt->bindValue($identifier, $this->parent_wiki_id, PDO::PARAM_INT);
                        break;
                    case '`target_wiki_id`':
                        $stmt->bindValue($identifier, $this->target_wiki_id, PDO::PARAM_INT);
                        break;
                    case '`user_id`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`version`':
                        $stmt->bindValue($identifier, $this->version, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MapPointTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getTitle();
                break;
            case 2:
                return $this->getPosition();
                break;
            case 3:
                return $this->getParentWikiId();
                break;
            case 4:
                return $this->getTargetWikiId();
                break;
            case 5:
                return $this->getUserId();
                break;
            case 6:
                return $this->getCreatedAt();
                break;
            case 7:
                return $this->getUpdatedAt();
                break;
            case 8:
                return $this->getVersion();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['MapPoint'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['MapPoint'][$this->hashCode()] = true;
        $keys = MapPointTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getPosition(),
            $keys[3] => $this->getParentWikiId(),
            $keys[4] => $this->getTargetWikiId(),
            $keys[5] => $this->getUserId(),
            $keys[6] => $this->getCreatedAt(),
            $keys[7] => $this->getUpdatedAt(),
            $keys[8] => $this->getVersion(),
        );
        if ($result[$keys[6]] instanceof \DateTimeInterface) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        if ($result[$keys[7]] instanceof \DateTimeInterface) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aParentWiki) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'wiki';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'wiki';
                        break;
                    default:
                        $key = 'ParentWiki';
                }

                $result[$key] = $this->aParentWiki->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTargetWiki) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'wiki';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'wiki';
                        break;
                    default:
                        $key = 'TargetWiki';
                }

                $result[$key] = $this->aTargetWiki->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUser) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collMapPointVersions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'mapPointVersions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'map_point_versions';
                        break;
                    default:
                        $key = 'MapPointVersions';
                }

                $result[$key] = $this->collMapPointVersions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\BagOfIdeas\Models\Map\MapPoint
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MapPointTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\BagOfIdeas\Models\Map\MapPoint
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setTitle($value);
                break;
            case 2:
                $this->setPosition($value);
                break;
            case 3:
                $this->setParentWikiId($value);
                break;
            case 4:
                $this->setTargetWikiId($value);
                break;
            case 5:
                $this->setUserId($value);
                break;
            case 6:
                $this->setCreatedAt($value);
                break;
            case 7:
                $this->setUpdatedAt($value);
                break;
            case 8:
                $this->setVersion($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = MapPointTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTitle($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPosition($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setParentWikiId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setTargetWikiId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setUserId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCreatedAt($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setUpdatedAt($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setVersion($arr[$keys[8]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MapPointTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MapPointTableMap::COL_ID)) {
            $criteria->add(MapPointTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(MapPointTableMap::COL_TITLE)) {
            $criteria->add(MapPointTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(MapPointTableMap::COL_POSITION)) {
            $criteria->add(MapPointTableMap::COL_POSITION, $this->position);
        }
        if ($this->isColumnModified(MapPointTableMap::COL_PARENT_WIKI_ID)) {
            $criteria->add(MapPointTableMap::COL_PARENT_WIKI_ID, $this->parent_wiki_id);
        }
        if ($this->isColumnModified(MapPointTableMap::COL_TARGET_WIKI_ID)) {
            $criteria->add(MapPointTableMap::COL_TARGET_WIKI_ID, $this->target_wiki_id);
        }
        if ($this->isColumnModified(MapPointTableMap::COL_USER_ID)) {
            $criteria->add(MapPointTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(MapPointTableMap::COL_CREATED_AT)) {
            $criteria->add(MapPointTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(MapPointTableMap::COL_UPDATED_AT)) {
            $criteria->add(MapPointTableMap::COL_UPDATED_AT, $this->updated_at);
        }
        if ($this->isColumnModified(MapPointTableMap::COL_VERSION)) {
            $criteria->add(MapPointTableMap::COL_VERSION, $this->version);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildMapPointQuery::create();
        $criteria->add(MapPointTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \BagOfIdeas\Models\Map\MapPoint (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setPosition($this->getPosition());
        $copyObj->setParentWikiId($this->getParentWikiId());
        $copyObj->setTargetWikiId($this->getTargetWikiId());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setVersion($this->getVersion());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMapPointVersions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMapPointVersion($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \BagOfIdeas\Models\Map\MapPoint Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a Wiki object.
     *
     * @param  Wiki $v
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     * @throws PropelException
     */
    public function setParentWiki(Wiki $v = null)
    {
        if ($v === null) {
            $this->setParentWikiId(NULL);
        } else {
            $this->setParentWikiId($v->getId());
        }

        $this->aParentWiki = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Wiki object, it will not be re-added.
        if ($v !== null) {
            $v->addMapPointRelatedByParentWikiId($this);
        }


        return $this;
    }


    /**
     * Get the associated Wiki object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Wiki The associated Wiki object.
     * @throws PropelException
     */
    public function getParentWiki(ConnectionInterface $con = null)
    {
        if ($this->aParentWiki === null && ($this->parent_wiki_id != 0)) {
            $this->aParentWiki = WikiQuery::create()->findPk($this->parent_wiki_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aParentWiki->addMapPointsRelatedByParentWikiId($this);
             */
        }

        return $this->aParentWiki;
    }

    /**
     * Declares an association between this object and a Wiki object.
     *
     * @param  Wiki $v
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTargetWiki(Wiki $v = null)
    {
        if ($v === null) {
            $this->setTargetWikiId(NULL);
        } else {
            $this->setTargetWikiId($v->getId());
        }

        $this->aTargetWiki = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Wiki object, it will not be re-added.
        if ($v !== null) {
            $v->addMapPointRelatedByTargetWikiId($this);
        }


        return $this;
    }


    /**
     * Get the associated Wiki object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Wiki The associated Wiki object.
     * @throws PropelException
     */
    public function getTargetWiki(ConnectionInterface $con = null)
    {
        if ($this->aTargetWiki === null && ($this->target_wiki_id != 0)) {
            $this->aTargetWiki = WikiQuery::create()->findPk($this->target_wiki_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTargetWiki->addMapPointsRelatedByTargetWikiId($this);
             */
        }

        return $this->aTargetWiki;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param  User $v
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addMapPoint($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->user_id != 0)) {
            $this->aUser = UserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addMapPoints($this);
             */
        }

        return $this->aUser;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('MapPointVersion' == $relationName) {
            $this->initMapPointVersions();
            return;
        }
    }

    /**
     * Clears out the collMapPointVersions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMapPointVersions()
     */
    public function clearMapPointVersions()
    {
        $this->collMapPointVersions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMapPointVersions collection loaded partially.
     */
    public function resetPartialMapPointVersions($v = true)
    {
        $this->collMapPointVersionsPartial = $v;
    }

    /**
     * Initializes the collMapPointVersions collection.
     *
     * By default this just sets the collMapPointVersions collection to an empty array (like clearcollMapPointVersions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMapPointVersions($overrideExisting = true)
    {
        if (null !== $this->collMapPointVersions && !$overrideExisting) {
            return;
        }

        $collectionClassName = MapPointVersionTableMap::getTableMap()->getCollectionClassName();

        $this->collMapPointVersions = new $collectionClassName;
        $this->collMapPointVersions->setModel('\BagOfIdeas\Models\Map\MapPointVersion');
    }

    /**
     * Gets an array of ChildMapPointVersion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMapPoint is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMapPointVersion[] List of ChildMapPointVersion objects
     * @throws PropelException
     */
    public function getMapPointVersions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMapPointVersionsPartial && !$this->isNew();
        if (null === $this->collMapPointVersions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMapPointVersions) {
                // return empty collection
                $this->initMapPointVersions();
            } else {
                $collMapPointVersions = ChildMapPointVersionQuery::create(null, $criteria)
                    ->filterByMapPoint($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMapPointVersionsPartial && count($collMapPointVersions)) {
                        $this->initMapPointVersions(false);

                        foreach ($collMapPointVersions as $obj) {
                            if (false == $this->collMapPointVersions->contains($obj)) {
                                $this->collMapPointVersions->append($obj);
                            }
                        }

                        $this->collMapPointVersionsPartial = true;
                    }

                    return $collMapPointVersions;
                }

                if ($partial && $this->collMapPointVersions) {
                    foreach ($this->collMapPointVersions as $obj) {
                        if ($obj->isNew()) {
                            $collMapPointVersions[] = $obj;
                        }
                    }
                }

                $this->collMapPointVersions = $collMapPointVersions;
                $this->collMapPointVersionsPartial = false;
            }
        }

        return $this->collMapPointVersions;
    }

    /**
     * Sets a collection of ChildMapPointVersion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $mapPointVersions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMapPoint The current object (for fluent API support)
     */
    public function setMapPointVersions(Collection $mapPointVersions, ConnectionInterface $con = null)
    {
        /** @var ChildMapPointVersion[] $mapPointVersionsToDelete */
        $mapPointVersionsToDelete = $this->getMapPointVersions(new Criteria(), $con)->diff($mapPointVersions);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->mapPointVersionsScheduledForDeletion = clone $mapPointVersionsToDelete;

        foreach ($mapPointVersionsToDelete as $mapPointVersionRemoved) {
            $mapPointVersionRemoved->setMapPoint(null);
        }

        $this->collMapPointVersions = null;
        foreach ($mapPointVersions as $mapPointVersion) {
            $this->addMapPointVersion($mapPointVersion);
        }

        $this->collMapPointVersions = $mapPointVersions;
        $this->collMapPointVersionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MapPointVersion objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MapPointVersion objects.
     * @throws PropelException
     */
    public function countMapPointVersions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMapPointVersionsPartial && !$this->isNew();
        if (null === $this->collMapPointVersions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMapPointVersions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMapPointVersions());
            }

            $query = ChildMapPointVersionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMapPoint($this)
                ->count($con);
        }

        return count($this->collMapPointVersions);
    }

    /**
     * Method called to associate a ChildMapPointVersion object to this object
     * through the ChildMapPointVersion foreign key attribute.
     *
     * @param  ChildMapPointVersion $l ChildMapPointVersion
     * @return $this|\BagOfIdeas\Models\Map\MapPoint The current object (for fluent API support)
     */
    public function addMapPointVersion(ChildMapPointVersion $l)
    {
        if ($this->collMapPointVersions === null) {
            $this->initMapPointVersions();
            $this->collMapPointVersionsPartial = true;
        }

        if (!$this->collMapPointVersions->contains($l)) {
            $this->doAddMapPointVersion($l);

            if ($this->mapPointVersionsScheduledForDeletion and $this->mapPointVersionsScheduledForDeletion->contains($l)) {
                $this->mapPointVersionsScheduledForDeletion->remove($this->mapPointVersionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMapPointVersion $mapPointVersion The ChildMapPointVersion object to add.
     */
    protected function doAddMapPointVersion(ChildMapPointVersion $mapPointVersion)
    {
        $this->collMapPointVersions[]= $mapPointVersion;
        $mapPointVersion->setMapPoint($this);
    }

    /**
     * @param  ChildMapPointVersion $mapPointVersion The ChildMapPointVersion object to remove.
     * @return $this|ChildMapPoint The current object (for fluent API support)
     */
    public function removeMapPointVersion(ChildMapPointVersion $mapPointVersion)
    {
        if ($this->getMapPointVersions()->contains($mapPointVersion)) {
            $pos = $this->collMapPointVersions->search($mapPointVersion);
            $this->collMapPointVersions->remove($pos);
            if (null === $this->mapPointVersionsScheduledForDeletion) {
                $this->mapPointVersionsScheduledForDeletion = clone $this->collMapPointVersions;
                $this->mapPointVersionsScheduledForDeletion->clear();
            }
            $this->mapPointVersionsScheduledForDeletion[]= clone $mapPointVersion;
            $mapPointVersion->setMapPoint(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aParentWiki) {
            $this->aParentWiki->removeMapPointRelatedByParentWikiId($this);
        }
        if (null !== $this->aTargetWiki) {
            $this->aTargetWiki->removeMapPointRelatedByTargetWikiId($this);
        }
        if (null !== $this->aUser) {
            $this->aUser->removeMapPoint($this);
        }
        $this->id = null;
        $this->title = null;
        $this->position = null;
        $this->parent_wiki_id = null;
        $this->target_wiki_id = null;
        $this->user_id = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->version = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collMapPointVersions) {
                foreach ($this->collMapPointVersions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMapPointVersions = null;
        $this->aParentWiki = null;
        $this->aTargetWiki = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MapPointTableMap::DEFAULT_STRING_FORMAT);
    }

    // versionable behavior

    /**
     * Enforce a new Version of this object upon next save.
     *
     * @return $this|\BagOfIdeas\Models\Map\MapPoint
     */
    public function enforceVersioning()
    {
        $this->enforceVersion = true;

        return $this;
    }

    /**
     * Checks whether the current state must be recorded as a version
     *
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     * @return  boolean
     */
    public function isVersioningNecessary(ConnectionInterface $con = null)
    {
        if ($this->alreadyInSave) {
            return false;
        }

        if ($this->enforceVersion) {
            return true;
        }

        if (ChildMapPointQuery::isVersioningEnabled() && ($this->isNew() || $this->isModified()) || $this->isDeleted()) {
            return true;
        }
        if (null !== ($object = $this->getParentWiki($con)) && $object->isVersioningNecessary($con)) {
            return true;
        }

        if (null !== ($object = $this->getTargetWiki($con)) && $object->isVersioningNecessary($con)) {
            return true;
        }


        return false;
    }

    /**
     * Creates a version of the current object and saves it.
     *
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     *
     * @return  ChildMapPointVersion A version object
     */
    public function addVersion(ConnectionInterface $con = null)
    {
        $this->enforceVersion = false;

        $version = new ChildMapPointVersion();
        $version->setId($this->getId());
        $version->setTitle($this->getTitle());
        $version->setPosition($this->getPosition());
        $version->setParentWikiId($this->getParentWikiId());
        $version->setTargetWikiId($this->getTargetWikiId());
        $version->setUserId($this->getUserId());
        $version->setCreatedAt($this->getCreatedAt());
        $version->setUpdatedAt($this->getUpdatedAt());
        $version->setVersion($this->getVersion());
        $version->setMapPoint($this);
        if (($related = $this->getParentWiki(null, $con)) && $related->getVersion()) {
            $version->setParentWikiIdVersion($related->getVersion());
        }
        if (($related = $this->getTargetWiki(null, $con)) && $related->getVersion()) {
            $version->setTargetWikiIdVersion($related->getVersion());
        }
        $version->save($con);

        return $version;
    }

    /**
     * Sets the properties of the current object to the value they had at a specific version
     *
     * @param   integer $versionNumber The version number to read
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     *
     * @return  $this|ChildMapPoint The current object (for fluent API support)
     */
    public function toVersion($versionNumber, ConnectionInterface $con = null)
    {
        $version = $this->getOneVersion($versionNumber, $con);
        if (!$version) {
            throw new PropelException(sprintf('No ChildMapPoint object found with version %d', $version));
        }
        $this->populateFromVersion($version, $con);

        return $this;
    }

    /**
     * Sets the properties of the current object to the value they had at a specific version
     *
     * @param ChildMapPointVersion $version The version object to use
     * @param ConnectionInterface   $con the connection to use
     * @param array                 $loadedObjects objects that been loaded in a chain of populateFromVersion calls on referrer or fk objects.
     *
     * @return $this|ChildMapPoint The current object (for fluent API support)
     */
    public function populateFromVersion($version, $con = null, &$loadedObjects = array())
    {
        $loadedObjects['ChildMapPoint'][$version->getId()][$version->getVersion()] = $this;
        $this->setId($version->getId());
        $this->setTitle($version->getTitle());
        $this->setPosition($version->getPosition());
        $this->setParentWikiId($version->getParentWikiId());
        $this->setTargetWikiId($version->getTargetWikiId());
        $this->setUserId($version->getUserId());
        $this->setCreatedAt($version->getCreatedAt());
        $this->setUpdatedAt($version->getUpdatedAt());
        $this->setVersion($version->getVersion());
        if ($fkValue = $version->getParentWikiId()) {
            if (isset($loadedObjects['Wiki']) && isset($loadedObjects['Wiki'][$fkValue]) && isset($loadedObjects['Wiki'][$fkValue][$version->getParentWikiIdVersion()])) {
                $related = $loadedObjects['Wiki'][$fkValue][$version->getParentWikiIdVersion()];
            } else {
                $related = new Wiki();
                $relatedVersion = WikiVersionQuery::create()
                    ->filterById($fkValue)
                    ->filterByVersion($version->getParentWikiIdVersion())
                    ->findOne($con);
                $related->populateFromVersion($relatedVersion, $con, $loadedObjects);
                $related->setNew(false);
            }
            $this->setParentWiki($related);
        }
        if ($fkValue = $version->getTargetWikiId()) {
            if (isset($loadedObjects['Wiki']) && isset($loadedObjects['Wiki'][$fkValue]) && isset($loadedObjects['Wiki'][$fkValue][$version->getTargetWikiIdVersion()])) {
                $related = $loadedObjects['Wiki'][$fkValue][$version->getTargetWikiIdVersion()];
            } else {
                $related = new Wiki();
                $relatedVersion = WikiVersionQuery::create()
                    ->filterById($fkValue)
                    ->filterByVersion($version->getTargetWikiIdVersion())
                    ->findOne($con);
                $related->populateFromVersion($relatedVersion, $con, $loadedObjects);
                $related->setNew(false);
            }
            $this->setTargetWiki($related);
        }

        return $this;
    }

    /**
     * Gets the latest persisted version number for the current object
     *
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     *
     * @return  integer
     */
    public function getLastVersionNumber(ConnectionInterface $con = null)
    {
        $v = ChildMapPointVersionQuery::create()
            ->filterByMapPoint($this)
            ->orderByVersion('desc')
            ->findOne($con);
        if (!$v) {
            return 0;
        }

        return $v->getVersion();
    }

    /**
     * Checks whether the current object is the latest one
     *
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     *
     * @return  Boolean
     */
    public function isLastVersion(ConnectionInterface $con = null)
    {
        return $this->getLastVersionNumber($con) == $this->getVersion();
    }

    /**
     * Retrieves a version object for this entity and a version number
     *
     * @param   integer $versionNumber The version number to read
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     *
     * @return  ChildMapPointVersion A version object
     */
    public function getOneVersion($versionNumber, ConnectionInterface $con = null)
    {
        return ChildMapPointVersionQuery::create()
            ->filterByMapPoint($this)
            ->filterByVersion($versionNumber)
            ->findOne($con);
    }

    /**
     * Gets all the versions of this object, in incremental order
     *
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     *
     * @return  ObjectCollection|ChildMapPointVersion[] A list of ChildMapPointVersion objects
     */
    public function getAllVersions(ConnectionInterface $con = null)
    {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(MapPointVersionTableMap::COL_VERSION);

        return $this->getMapPointVersions($criteria, $con);
    }

    /**
     * Compares the current object with another of its version.
     * <code>
     * print_r($book->compareVersion(1));
     * => array(
     *   '1' => array('Title' => 'Book title at version 1'),
     *   '2' => array('Title' => 'Book title at version 2')
     * );
     * </code>
     *
     * @param   integer             $versionNumber
     * @param   string              $keys Main key used for the result diff (versions|columns)
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     * @param   array               $ignoredColumns  The columns to exclude from the diff.
     *
     * @return  array A list of differences
     */
    public function compareVersion($versionNumber, $keys = 'columns', ConnectionInterface $con = null, $ignoredColumns = array())
    {
        $fromVersion = $this->toArray();
        $toVersion = $this->getOneVersion($versionNumber, $con)->toArray();

        return $this->computeDiff($fromVersion, $toVersion, $keys, $ignoredColumns);
    }

    /**
     * Compares two versions of the current object.
     * <code>
     * print_r($book->compareVersions(1, 2));
     * => array(
     *   '1' => array('Title' => 'Book title at version 1'),
     *   '2' => array('Title' => 'Book title at version 2')
     * );
     * </code>
     *
     * @param   integer             $fromVersionNumber
     * @param   integer             $toVersionNumber
     * @param   string              $keys Main key used for the result diff (versions|columns)
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     * @param   array               $ignoredColumns  The columns to exclude from the diff.
     *
     * @return  array A list of differences
     */
    public function compareVersions($fromVersionNumber, $toVersionNumber, $keys = 'columns', ConnectionInterface $con = null, $ignoredColumns = array())
    {
        $fromVersion = $this->getOneVersion($fromVersionNumber, $con)->toArray();
        $toVersion = $this->getOneVersion($toVersionNumber, $con)->toArray();

        return $this->computeDiff($fromVersion, $toVersion, $keys, $ignoredColumns);
    }

    /**
     * Computes the diff between two versions.
     * <code>
     * print_r($book->computeDiff(1, 2));
     * => array(
     *   '1' => array('Title' => 'Book title at version 1'),
     *   '2' => array('Title' => 'Book title at version 2')
     * );
     * </code>
     *
     * @param   array     $fromVersion     An array representing the original version.
     * @param   array     $toVersion       An array representing the destination version.
     * @param   string    $keys            Main key used for the result diff (versions|columns).
     * @param   array     $ignoredColumns  The columns to exclude from the diff.
     *
     * @return  array A list of differences
     */
    protected function computeDiff($fromVersion, $toVersion, $keys = 'columns', $ignoredColumns = array())
    {
        $fromVersionNumber = $fromVersion['Version'];
        $toVersionNumber = $toVersion['Version'];
        $ignoredColumns = array_merge(array(
            'Version',
        ), $ignoredColumns);
        $diff = array();
        foreach ($fromVersion as $key => $value) {
            if (in_array($key, $ignoredColumns)) {
                continue;
            }
            if ($toVersion[$key] != $value) {
                switch ($keys) {
                    case 'versions':
                        $diff[$fromVersionNumber][$key] = $value;
                        $diff[$toVersionNumber][$key] = $toVersion[$key];
                        break;
                    default:
                        $diff[$key] = array(
                            $fromVersionNumber => $value,
                            $toVersionNumber => $toVersion[$key],
                        );
                        break;
                }
            }
        }

        return $diff;
    }
    /**
     * retrieve the last $number versions.
     *
     * @param  Integer             $number The number of record to return.
     * @param  Criteria            $criteria The Criteria object containing modified values.
     * @param  ConnectionInterface $con The ConnectionInterface connection to use.
     *
     * @return PropelCollection|\BagOfIdeas\Models\Map\MapPointVersion[] List of \BagOfIdeas\Models\Map\MapPointVersion objects
     */
    public function getLastVersions($number = 10, $criteria = null, ConnectionInterface $con = null)
    {
        $criteria = ChildMapPointVersionQuery::create(null, $criteria);
        $criteria->addDescendingOrderByColumn(MapPointVersionTableMap::COL_VERSION);
        $criteria->limit($number);

        return $this->getMapPointVersions($criteria, $con);
    }
    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildMapPoint The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[MapPointTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
