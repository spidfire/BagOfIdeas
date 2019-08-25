<?php

namespace BagOfIdeas\Models\Wiki\Base;

use \DateTime;
use \Exception;
use \PDO;
use BagOfIdeas\Models\Map\MapPoint;
use BagOfIdeas\Models\Map\MapPointQuery;
use BagOfIdeas\Models\Map\MapPointVersionQuery;
use BagOfIdeas\Models\Map\Base\MapPoint as BaseMapPoint;
use BagOfIdeas\Models\Map\Map\MapPointTableMap;
use BagOfIdeas\Models\Map\Map\MapPointVersionTableMap;
use BagOfIdeas\Models\User\User;
use BagOfIdeas\Models\User\UserQuery;
use BagOfIdeas\Models\Wiki\Wiki as ChildWiki;
use BagOfIdeas\Models\Wiki\WikiQuery as ChildWikiQuery;
use BagOfIdeas\Models\Wiki\WikiVersion as ChildWikiVersion;
use BagOfIdeas\Models\Wiki\WikiVersionQuery as ChildWikiVersionQuery;
use BagOfIdeas\Models\Wiki\Map\WikiTableMap;
use BagOfIdeas\Models\Wiki\Map\WikiVersionTableMap;
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
 * Base class that represents a row from the 'wiki' table.
 *
 *
 *
 * @package    propel.generator.Wiki.Base
 */
abstract class Wiki implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\BagOfIdeas\\Models\\Wiki\\Map\\WikiTableMap';


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
     * The value for the path field.
     *
     * @var        string
     */
    protected $path;

    /**
     * The value for the image field.
     *
     * @var        string
     */
    protected $image;

    /**
     * The value for the content field.
     *
     * @var        string
     */
    protected $content;

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
     * @var        User
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|MapPoint[] Collection to store aggregation of MapPoint objects.
     */
    protected $collMapPointsRelatedByParentWikiId;
    protected $collMapPointsRelatedByParentWikiIdPartial;

    /**
     * @var        ObjectCollection|MapPoint[] Collection to store aggregation of MapPoint objects.
     */
    protected $collMapPointsRelatedByTargetWikiId;
    protected $collMapPointsRelatedByTargetWikiIdPartial;

    /**
     * @var        ObjectCollection|ChildWikiVersion[] Collection to store aggregation of ChildWikiVersion objects.
     */
    protected $collWikiVersions;
    protected $collWikiVersionsPartial;

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
     * @var ObjectCollection|MapPoint[]
     */
    protected $mapPointsRelatedByParentWikiIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|MapPoint[]
     */
    protected $mapPointsRelatedByTargetWikiIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildWikiVersion[]
     */
    protected $wikiVersionsScheduledForDeletion = null;

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
     * Initializes internal state of BagOfIdeas\Models\Wiki\Base\Wiki object.
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
     * Compares this with another <code>Wiki</code> instance.  If
     * <code>obj</code> is an instance of <code>Wiki</code>, delegates to
     * <code>equals(Wiki)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Wiki The current object, for fluid interface
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
     * Get the [path] column value.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the [image] column value.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get the [content] column value.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[WikiTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[WikiTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [path] column.
     *
     * @param string $v new value
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function setPath($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->path !== $v) {
            $this->path = $v;
            $this->modifiedColumns[WikiTableMap::COL_PATH] = true;
        }

        return $this;
    } // setPath()

    /**
     * Set the value of [image] column.
     *
     * @param string $v new value
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function setImage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->image !== $v) {
            $this->image = $v;
            $this->modifiedColumns[WikiTableMap::COL_IMAGE] = true;
        }

        return $this;
    } // setImage()

    /**
     * Set the value of [content] column.
     *
     * @param string $v new value
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function setContent($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->content !== $v) {
            $this->content = $v;
            $this->modifiedColumns[WikiTableMap::COL_CONTENT] = true;
        }

        return $this;
    } // setContent()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[WikiTableMap::COL_USER_ID] = true;
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
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[WikiTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[WikiTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [version] column.
     *
     * @param int $v new value
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->version !== $v) {
            $this->version = $v;
            $this->modifiedColumns[WikiTableMap::COL_VERSION] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : WikiTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : WikiTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : WikiTableMap::translateFieldName('Path', TableMap::TYPE_PHPNAME, $indexType)];
            $this->path = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : WikiTableMap::translateFieldName('Image', TableMap::TYPE_PHPNAME, $indexType)];
            $this->image = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : WikiTableMap::translateFieldName('Content', TableMap::TYPE_PHPNAME, $indexType)];
            $this->content = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : WikiTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : WikiTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : WikiTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : WikiTableMap::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = WikiTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\BagOfIdeas\\Models\\Wiki\\Wiki'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(WikiTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildWikiQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collMapPointsRelatedByParentWikiId = null;

            $this->collMapPointsRelatedByTargetWikiId = null;

            $this->collWikiVersions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Wiki::setDeleted()
     * @see Wiki::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(WikiTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildWikiQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(WikiTableMap::DATABASE_NAME);
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
                if (!$this->isColumnModified(WikiTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(WikiTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(WikiTableMap::COL_UPDATED_AT)) {
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
                WikiTableMap::addInstanceToPool($this);
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

            if ($this->mapPointsRelatedByParentWikiIdScheduledForDeletion !== null) {
                if (!$this->mapPointsRelatedByParentWikiIdScheduledForDeletion->isEmpty()) {
                    \BagOfIdeas\Models\Map\MapPointQuery::create()
                        ->filterByPrimaryKeys($this->mapPointsRelatedByParentWikiIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->mapPointsRelatedByParentWikiIdScheduledForDeletion = null;
                }
            }

            if ($this->collMapPointsRelatedByParentWikiId !== null) {
                foreach ($this->collMapPointsRelatedByParentWikiId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->mapPointsRelatedByTargetWikiIdScheduledForDeletion !== null) {
                if (!$this->mapPointsRelatedByTargetWikiIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->mapPointsRelatedByTargetWikiIdScheduledForDeletion as $mapPointRelatedByTargetWikiId) {
                        // need to save related object because we set the relation to null
                        $mapPointRelatedByTargetWikiId->save($con);
                    }
                    $this->mapPointsRelatedByTargetWikiIdScheduledForDeletion = null;
                }
            }

            if ($this->collMapPointsRelatedByTargetWikiId !== null) {
                foreach ($this->collMapPointsRelatedByTargetWikiId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->wikiVersionsScheduledForDeletion !== null) {
                if (!$this->wikiVersionsScheduledForDeletion->isEmpty()) {
                    \BagOfIdeas\Models\Wiki\WikiVersionQuery::create()
                        ->filterByPrimaryKeys($this->wikiVersionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->wikiVersionsScheduledForDeletion = null;
                }
            }

            if ($this->collWikiVersions !== null) {
                foreach ($this->collWikiVersions as $referrerFK) {
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

        $this->modifiedColumns[WikiTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . WikiTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(WikiTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(WikiTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(WikiTableMap::COL_PATH)) {
            $modifiedColumns[':p' . $index++]  = '`path`';
        }
        if ($this->isColumnModified(WikiTableMap::COL_IMAGE)) {
            $modifiedColumns[':p' . $index++]  = '`image`';
        }
        if ($this->isColumnModified(WikiTableMap::COL_CONTENT)) {
            $modifiedColumns[':p' . $index++]  = '`content`';
        }
        if ($this->isColumnModified(WikiTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }
        if ($this->isColumnModified(WikiTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(WikiTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(WikiTableMap::COL_VERSION)) {
            $modifiedColumns[':p' . $index++]  = '`version`';
        }

        $sql = sprintf(
            'INSERT INTO `wiki` (%s) VALUES (%s)',
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
                    case '`path`':
                        $stmt->bindValue($identifier, $this->path, PDO::PARAM_STR);
                        break;
                    case '`image`':
                        $stmt->bindValue($identifier, $this->image, PDO::PARAM_STR);
                        break;
                    case '`content`':
                        $stmt->bindValue($identifier, $this->content, PDO::PARAM_STR);
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
        $pos = WikiTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getPath();
                break;
            case 3:
                return $this->getImage();
                break;
            case 4:
                return $this->getContent();
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

        if (isset($alreadyDumpedObjects['Wiki'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Wiki'][$this->hashCode()] = true;
        $keys = WikiTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getPath(),
            $keys[3] => $this->getImage(),
            $keys[4] => $this->getContent(),
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
            if (null !== $this->collMapPointsRelatedByParentWikiId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'mapPoints';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'map_points';
                        break;
                    default:
                        $key = 'MapPoints';
                }

                $result[$key] = $this->collMapPointsRelatedByParentWikiId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMapPointsRelatedByTargetWikiId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'mapPoints';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'map_points';
                        break;
                    default:
                        $key = 'MapPoints';
                }

                $result[$key] = $this->collMapPointsRelatedByTargetWikiId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWikiVersions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'wikiVersions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'wiki_versions';
                        break;
                    default:
                        $key = 'WikiVersions';
                }

                $result[$key] = $this->collWikiVersions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = WikiTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki
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
                $this->setPath($value);
                break;
            case 3:
                $this->setImage($value);
                break;
            case 4:
                $this->setContent($value);
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
        $keys = WikiTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTitle($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPath($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setImage($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setContent($arr[$keys[4]]);
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
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object, for fluid interface
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
        $criteria = new Criteria(WikiTableMap::DATABASE_NAME);

        if ($this->isColumnModified(WikiTableMap::COL_ID)) {
            $criteria->add(WikiTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(WikiTableMap::COL_TITLE)) {
            $criteria->add(WikiTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(WikiTableMap::COL_PATH)) {
            $criteria->add(WikiTableMap::COL_PATH, $this->path);
        }
        if ($this->isColumnModified(WikiTableMap::COL_IMAGE)) {
            $criteria->add(WikiTableMap::COL_IMAGE, $this->image);
        }
        if ($this->isColumnModified(WikiTableMap::COL_CONTENT)) {
            $criteria->add(WikiTableMap::COL_CONTENT, $this->content);
        }
        if ($this->isColumnModified(WikiTableMap::COL_USER_ID)) {
            $criteria->add(WikiTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(WikiTableMap::COL_CREATED_AT)) {
            $criteria->add(WikiTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(WikiTableMap::COL_UPDATED_AT)) {
            $criteria->add(WikiTableMap::COL_UPDATED_AT, $this->updated_at);
        }
        if ($this->isColumnModified(WikiTableMap::COL_VERSION)) {
            $criteria->add(WikiTableMap::COL_VERSION, $this->version);
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
        $criteria = ChildWikiQuery::create();
        $criteria->add(WikiTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \BagOfIdeas\Models\Wiki\Wiki (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setPath($this->getPath());
        $copyObj->setImage($this->getImage());
        $copyObj->setContent($this->getContent());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setVersion($this->getVersion());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMapPointsRelatedByParentWikiId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMapPointRelatedByParentWikiId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMapPointsRelatedByTargetWikiId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMapPointRelatedByTargetWikiId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWikiVersions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWikiVersion($relObj->copy($deepCopy));
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
     * @return \BagOfIdeas\Models\Wiki\Wiki Clone of current object.
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
     * Declares an association between this object and a User object.
     *
     * @param  User $v
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
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
            $v->addWiki($this);
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
                $this->aUser->addWikis($this);
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
        if ('MapPointRelatedByParentWikiId' == $relationName) {
            $this->initMapPointsRelatedByParentWikiId();
            return;
        }
        if ('MapPointRelatedByTargetWikiId' == $relationName) {
            $this->initMapPointsRelatedByTargetWikiId();
            return;
        }
        if ('WikiVersion' == $relationName) {
            $this->initWikiVersions();
            return;
        }
    }

    /**
     * Clears out the collMapPointsRelatedByParentWikiId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMapPointsRelatedByParentWikiId()
     */
    public function clearMapPointsRelatedByParentWikiId()
    {
        $this->collMapPointsRelatedByParentWikiId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMapPointsRelatedByParentWikiId collection loaded partially.
     */
    public function resetPartialMapPointsRelatedByParentWikiId($v = true)
    {
        $this->collMapPointsRelatedByParentWikiIdPartial = $v;
    }

    /**
     * Initializes the collMapPointsRelatedByParentWikiId collection.
     *
     * By default this just sets the collMapPointsRelatedByParentWikiId collection to an empty array (like clearcollMapPointsRelatedByParentWikiId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMapPointsRelatedByParentWikiId($overrideExisting = true)
    {
        if (null !== $this->collMapPointsRelatedByParentWikiId && !$overrideExisting) {
            return;
        }

        $collectionClassName = MapPointTableMap::getTableMap()->getCollectionClassName();

        $this->collMapPointsRelatedByParentWikiId = new $collectionClassName;
        $this->collMapPointsRelatedByParentWikiId->setModel('\BagOfIdeas\Models\Map\MapPoint');
    }

    /**
     * Gets an array of MapPoint objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildWiki is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|MapPoint[] List of MapPoint objects
     * @throws PropelException
     */
    public function getMapPointsRelatedByParentWikiId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMapPointsRelatedByParentWikiIdPartial && !$this->isNew();
        if (null === $this->collMapPointsRelatedByParentWikiId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMapPointsRelatedByParentWikiId) {
                // return empty collection
                $this->initMapPointsRelatedByParentWikiId();
            } else {
                $collMapPointsRelatedByParentWikiId = MapPointQuery::create(null, $criteria)
                    ->filterByParentWiki($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMapPointsRelatedByParentWikiIdPartial && count($collMapPointsRelatedByParentWikiId)) {
                        $this->initMapPointsRelatedByParentWikiId(false);

                        foreach ($collMapPointsRelatedByParentWikiId as $obj) {
                            if (false == $this->collMapPointsRelatedByParentWikiId->contains($obj)) {
                                $this->collMapPointsRelatedByParentWikiId->append($obj);
                            }
                        }

                        $this->collMapPointsRelatedByParentWikiIdPartial = true;
                    }

                    return $collMapPointsRelatedByParentWikiId;
                }

                if ($partial && $this->collMapPointsRelatedByParentWikiId) {
                    foreach ($this->collMapPointsRelatedByParentWikiId as $obj) {
                        if ($obj->isNew()) {
                            $collMapPointsRelatedByParentWikiId[] = $obj;
                        }
                    }
                }

                $this->collMapPointsRelatedByParentWikiId = $collMapPointsRelatedByParentWikiId;
                $this->collMapPointsRelatedByParentWikiIdPartial = false;
            }
        }

        return $this->collMapPointsRelatedByParentWikiId;
    }

    /**
     * Sets a collection of MapPoint objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $mapPointsRelatedByParentWikiId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildWiki The current object (for fluent API support)
     */
    public function setMapPointsRelatedByParentWikiId(Collection $mapPointsRelatedByParentWikiId, ConnectionInterface $con = null)
    {
        /** @var MapPoint[] $mapPointsRelatedByParentWikiIdToDelete */
        $mapPointsRelatedByParentWikiIdToDelete = $this->getMapPointsRelatedByParentWikiId(new Criteria(), $con)->diff($mapPointsRelatedByParentWikiId);


        $this->mapPointsRelatedByParentWikiIdScheduledForDeletion = $mapPointsRelatedByParentWikiIdToDelete;

        foreach ($mapPointsRelatedByParentWikiIdToDelete as $mapPointRelatedByParentWikiIdRemoved) {
            $mapPointRelatedByParentWikiIdRemoved->setParentWiki(null);
        }

        $this->collMapPointsRelatedByParentWikiId = null;
        foreach ($mapPointsRelatedByParentWikiId as $mapPointRelatedByParentWikiId) {
            $this->addMapPointRelatedByParentWikiId($mapPointRelatedByParentWikiId);
        }

        $this->collMapPointsRelatedByParentWikiId = $mapPointsRelatedByParentWikiId;
        $this->collMapPointsRelatedByParentWikiIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseMapPoint objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseMapPoint objects.
     * @throws PropelException
     */
    public function countMapPointsRelatedByParentWikiId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMapPointsRelatedByParentWikiIdPartial && !$this->isNew();
        if (null === $this->collMapPointsRelatedByParentWikiId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMapPointsRelatedByParentWikiId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMapPointsRelatedByParentWikiId());
            }

            $query = MapPointQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByParentWiki($this)
                ->count($con);
        }

        return count($this->collMapPointsRelatedByParentWikiId);
    }

    /**
     * Method called to associate a MapPoint object to this object
     * through the MapPoint foreign key attribute.
     *
     * @param  MapPoint $l MapPoint
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function addMapPointRelatedByParentWikiId(MapPoint $l)
    {
        if ($this->collMapPointsRelatedByParentWikiId === null) {
            $this->initMapPointsRelatedByParentWikiId();
            $this->collMapPointsRelatedByParentWikiIdPartial = true;
        }

        if (!$this->collMapPointsRelatedByParentWikiId->contains($l)) {
            $this->doAddMapPointRelatedByParentWikiId($l);

            if ($this->mapPointsRelatedByParentWikiIdScheduledForDeletion and $this->mapPointsRelatedByParentWikiIdScheduledForDeletion->contains($l)) {
                $this->mapPointsRelatedByParentWikiIdScheduledForDeletion->remove($this->mapPointsRelatedByParentWikiIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param MapPoint $mapPointRelatedByParentWikiId The MapPoint object to add.
     */
    protected function doAddMapPointRelatedByParentWikiId(MapPoint $mapPointRelatedByParentWikiId)
    {
        $this->collMapPointsRelatedByParentWikiId[]= $mapPointRelatedByParentWikiId;
        $mapPointRelatedByParentWikiId->setParentWiki($this);
    }

    /**
     * @param  MapPoint $mapPointRelatedByParentWikiId The MapPoint object to remove.
     * @return $this|ChildWiki The current object (for fluent API support)
     */
    public function removeMapPointRelatedByParentWikiId(MapPoint $mapPointRelatedByParentWikiId)
    {
        if ($this->getMapPointsRelatedByParentWikiId()->contains($mapPointRelatedByParentWikiId)) {
            $pos = $this->collMapPointsRelatedByParentWikiId->search($mapPointRelatedByParentWikiId);
            $this->collMapPointsRelatedByParentWikiId->remove($pos);
            if (null === $this->mapPointsRelatedByParentWikiIdScheduledForDeletion) {
                $this->mapPointsRelatedByParentWikiIdScheduledForDeletion = clone $this->collMapPointsRelatedByParentWikiId;
                $this->mapPointsRelatedByParentWikiIdScheduledForDeletion->clear();
            }
            $this->mapPointsRelatedByParentWikiIdScheduledForDeletion[]= clone $mapPointRelatedByParentWikiId;
            $mapPointRelatedByParentWikiId->setParentWiki(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Wiki is new, it will return
     * an empty collection; or if this Wiki has previously
     * been saved, it will retrieve related MapPointsRelatedByParentWikiId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Wiki.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|MapPoint[] List of MapPoint objects
     */
    public function getMapPointsRelatedByParentWikiIdJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = MapPointQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getMapPointsRelatedByParentWikiId($query, $con);
    }

    /**
     * Clears out the collMapPointsRelatedByTargetWikiId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMapPointsRelatedByTargetWikiId()
     */
    public function clearMapPointsRelatedByTargetWikiId()
    {
        $this->collMapPointsRelatedByTargetWikiId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMapPointsRelatedByTargetWikiId collection loaded partially.
     */
    public function resetPartialMapPointsRelatedByTargetWikiId($v = true)
    {
        $this->collMapPointsRelatedByTargetWikiIdPartial = $v;
    }

    /**
     * Initializes the collMapPointsRelatedByTargetWikiId collection.
     *
     * By default this just sets the collMapPointsRelatedByTargetWikiId collection to an empty array (like clearcollMapPointsRelatedByTargetWikiId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMapPointsRelatedByTargetWikiId($overrideExisting = true)
    {
        if (null !== $this->collMapPointsRelatedByTargetWikiId && !$overrideExisting) {
            return;
        }

        $collectionClassName = MapPointTableMap::getTableMap()->getCollectionClassName();

        $this->collMapPointsRelatedByTargetWikiId = new $collectionClassName;
        $this->collMapPointsRelatedByTargetWikiId->setModel('\BagOfIdeas\Models\Map\MapPoint');
    }

    /**
     * Gets an array of MapPoint objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildWiki is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|MapPoint[] List of MapPoint objects
     * @throws PropelException
     */
    public function getMapPointsRelatedByTargetWikiId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMapPointsRelatedByTargetWikiIdPartial && !$this->isNew();
        if (null === $this->collMapPointsRelatedByTargetWikiId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMapPointsRelatedByTargetWikiId) {
                // return empty collection
                $this->initMapPointsRelatedByTargetWikiId();
            } else {
                $collMapPointsRelatedByTargetWikiId = MapPointQuery::create(null, $criteria)
                    ->filterByTargetWiki($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMapPointsRelatedByTargetWikiIdPartial && count($collMapPointsRelatedByTargetWikiId)) {
                        $this->initMapPointsRelatedByTargetWikiId(false);

                        foreach ($collMapPointsRelatedByTargetWikiId as $obj) {
                            if (false == $this->collMapPointsRelatedByTargetWikiId->contains($obj)) {
                                $this->collMapPointsRelatedByTargetWikiId->append($obj);
                            }
                        }

                        $this->collMapPointsRelatedByTargetWikiIdPartial = true;
                    }

                    return $collMapPointsRelatedByTargetWikiId;
                }

                if ($partial && $this->collMapPointsRelatedByTargetWikiId) {
                    foreach ($this->collMapPointsRelatedByTargetWikiId as $obj) {
                        if ($obj->isNew()) {
                            $collMapPointsRelatedByTargetWikiId[] = $obj;
                        }
                    }
                }

                $this->collMapPointsRelatedByTargetWikiId = $collMapPointsRelatedByTargetWikiId;
                $this->collMapPointsRelatedByTargetWikiIdPartial = false;
            }
        }

        return $this->collMapPointsRelatedByTargetWikiId;
    }

    /**
     * Sets a collection of MapPoint objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $mapPointsRelatedByTargetWikiId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildWiki The current object (for fluent API support)
     */
    public function setMapPointsRelatedByTargetWikiId(Collection $mapPointsRelatedByTargetWikiId, ConnectionInterface $con = null)
    {
        /** @var MapPoint[] $mapPointsRelatedByTargetWikiIdToDelete */
        $mapPointsRelatedByTargetWikiIdToDelete = $this->getMapPointsRelatedByTargetWikiId(new Criteria(), $con)->diff($mapPointsRelatedByTargetWikiId);


        $this->mapPointsRelatedByTargetWikiIdScheduledForDeletion = $mapPointsRelatedByTargetWikiIdToDelete;

        foreach ($mapPointsRelatedByTargetWikiIdToDelete as $mapPointRelatedByTargetWikiIdRemoved) {
            $mapPointRelatedByTargetWikiIdRemoved->setTargetWiki(null);
        }

        $this->collMapPointsRelatedByTargetWikiId = null;
        foreach ($mapPointsRelatedByTargetWikiId as $mapPointRelatedByTargetWikiId) {
            $this->addMapPointRelatedByTargetWikiId($mapPointRelatedByTargetWikiId);
        }

        $this->collMapPointsRelatedByTargetWikiId = $mapPointsRelatedByTargetWikiId;
        $this->collMapPointsRelatedByTargetWikiIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseMapPoint objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseMapPoint objects.
     * @throws PropelException
     */
    public function countMapPointsRelatedByTargetWikiId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMapPointsRelatedByTargetWikiIdPartial && !$this->isNew();
        if (null === $this->collMapPointsRelatedByTargetWikiId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMapPointsRelatedByTargetWikiId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMapPointsRelatedByTargetWikiId());
            }

            $query = MapPointQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTargetWiki($this)
                ->count($con);
        }

        return count($this->collMapPointsRelatedByTargetWikiId);
    }

    /**
     * Method called to associate a MapPoint object to this object
     * through the MapPoint foreign key attribute.
     *
     * @param  MapPoint $l MapPoint
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function addMapPointRelatedByTargetWikiId(MapPoint $l)
    {
        if ($this->collMapPointsRelatedByTargetWikiId === null) {
            $this->initMapPointsRelatedByTargetWikiId();
            $this->collMapPointsRelatedByTargetWikiIdPartial = true;
        }

        if (!$this->collMapPointsRelatedByTargetWikiId->contains($l)) {
            $this->doAddMapPointRelatedByTargetWikiId($l);

            if ($this->mapPointsRelatedByTargetWikiIdScheduledForDeletion and $this->mapPointsRelatedByTargetWikiIdScheduledForDeletion->contains($l)) {
                $this->mapPointsRelatedByTargetWikiIdScheduledForDeletion->remove($this->mapPointsRelatedByTargetWikiIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param MapPoint $mapPointRelatedByTargetWikiId The MapPoint object to add.
     */
    protected function doAddMapPointRelatedByTargetWikiId(MapPoint $mapPointRelatedByTargetWikiId)
    {
        $this->collMapPointsRelatedByTargetWikiId[]= $mapPointRelatedByTargetWikiId;
        $mapPointRelatedByTargetWikiId->setTargetWiki($this);
    }

    /**
     * @param  MapPoint $mapPointRelatedByTargetWikiId The MapPoint object to remove.
     * @return $this|ChildWiki The current object (for fluent API support)
     */
    public function removeMapPointRelatedByTargetWikiId(MapPoint $mapPointRelatedByTargetWikiId)
    {
        if ($this->getMapPointsRelatedByTargetWikiId()->contains($mapPointRelatedByTargetWikiId)) {
            $pos = $this->collMapPointsRelatedByTargetWikiId->search($mapPointRelatedByTargetWikiId);
            $this->collMapPointsRelatedByTargetWikiId->remove($pos);
            if (null === $this->mapPointsRelatedByTargetWikiIdScheduledForDeletion) {
                $this->mapPointsRelatedByTargetWikiIdScheduledForDeletion = clone $this->collMapPointsRelatedByTargetWikiId;
                $this->mapPointsRelatedByTargetWikiIdScheduledForDeletion->clear();
            }
            $this->mapPointsRelatedByTargetWikiIdScheduledForDeletion[]= $mapPointRelatedByTargetWikiId;
            $mapPointRelatedByTargetWikiId->setTargetWiki(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Wiki is new, it will return
     * an empty collection; or if this Wiki has previously
     * been saved, it will retrieve related MapPointsRelatedByTargetWikiId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Wiki.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|MapPoint[] List of MapPoint objects
     */
    public function getMapPointsRelatedByTargetWikiIdJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = MapPointQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getMapPointsRelatedByTargetWikiId($query, $con);
    }

    /**
     * Clears out the collWikiVersions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addWikiVersions()
     */
    public function clearWikiVersions()
    {
        $this->collWikiVersions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collWikiVersions collection loaded partially.
     */
    public function resetPartialWikiVersions($v = true)
    {
        $this->collWikiVersionsPartial = $v;
    }

    /**
     * Initializes the collWikiVersions collection.
     *
     * By default this just sets the collWikiVersions collection to an empty array (like clearcollWikiVersions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWikiVersions($overrideExisting = true)
    {
        if (null !== $this->collWikiVersions && !$overrideExisting) {
            return;
        }

        $collectionClassName = WikiVersionTableMap::getTableMap()->getCollectionClassName();

        $this->collWikiVersions = new $collectionClassName;
        $this->collWikiVersions->setModel('\BagOfIdeas\Models\Wiki\WikiVersion');
    }

    /**
     * Gets an array of ChildWikiVersion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildWiki is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildWikiVersion[] List of ChildWikiVersion objects
     * @throws PropelException
     */
    public function getWikiVersions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collWikiVersionsPartial && !$this->isNew();
        if (null === $this->collWikiVersions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collWikiVersions) {
                // return empty collection
                $this->initWikiVersions();
            } else {
                $collWikiVersions = ChildWikiVersionQuery::create(null, $criteria)
                    ->filterByWiki($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collWikiVersionsPartial && count($collWikiVersions)) {
                        $this->initWikiVersions(false);

                        foreach ($collWikiVersions as $obj) {
                            if (false == $this->collWikiVersions->contains($obj)) {
                                $this->collWikiVersions->append($obj);
                            }
                        }

                        $this->collWikiVersionsPartial = true;
                    }

                    return $collWikiVersions;
                }

                if ($partial && $this->collWikiVersions) {
                    foreach ($this->collWikiVersions as $obj) {
                        if ($obj->isNew()) {
                            $collWikiVersions[] = $obj;
                        }
                    }
                }

                $this->collWikiVersions = $collWikiVersions;
                $this->collWikiVersionsPartial = false;
            }
        }

        return $this->collWikiVersions;
    }

    /**
     * Sets a collection of ChildWikiVersion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $wikiVersions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildWiki The current object (for fluent API support)
     */
    public function setWikiVersions(Collection $wikiVersions, ConnectionInterface $con = null)
    {
        /** @var ChildWikiVersion[] $wikiVersionsToDelete */
        $wikiVersionsToDelete = $this->getWikiVersions(new Criteria(), $con)->diff($wikiVersions);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->wikiVersionsScheduledForDeletion = clone $wikiVersionsToDelete;

        foreach ($wikiVersionsToDelete as $wikiVersionRemoved) {
            $wikiVersionRemoved->setWiki(null);
        }

        $this->collWikiVersions = null;
        foreach ($wikiVersions as $wikiVersion) {
            $this->addWikiVersion($wikiVersion);
        }

        $this->collWikiVersions = $wikiVersions;
        $this->collWikiVersionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related WikiVersion objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related WikiVersion objects.
     * @throws PropelException
     */
    public function countWikiVersions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collWikiVersionsPartial && !$this->isNew();
        if (null === $this->collWikiVersions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWikiVersions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getWikiVersions());
            }

            $query = ChildWikiVersionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByWiki($this)
                ->count($con);
        }

        return count($this->collWikiVersions);
    }

    /**
     * Method called to associate a ChildWikiVersion object to this object
     * through the ChildWikiVersion foreign key attribute.
     *
     * @param  ChildWikiVersion $l ChildWikiVersion
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki The current object (for fluent API support)
     */
    public function addWikiVersion(ChildWikiVersion $l)
    {
        if ($this->collWikiVersions === null) {
            $this->initWikiVersions();
            $this->collWikiVersionsPartial = true;
        }

        if (!$this->collWikiVersions->contains($l)) {
            $this->doAddWikiVersion($l);

            if ($this->wikiVersionsScheduledForDeletion and $this->wikiVersionsScheduledForDeletion->contains($l)) {
                $this->wikiVersionsScheduledForDeletion->remove($this->wikiVersionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildWikiVersion $wikiVersion The ChildWikiVersion object to add.
     */
    protected function doAddWikiVersion(ChildWikiVersion $wikiVersion)
    {
        $this->collWikiVersions[]= $wikiVersion;
        $wikiVersion->setWiki($this);
    }

    /**
     * @param  ChildWikiVersion $wikiVersion The ChildWikiVersion object to remove.
     * @return $this|ChildWiki The current object (for fluent API support)
     */
    public function removeWikiVersion(ChildWikiVersion $wikiVersion)
    {
        if ($this->getWikiVersions()->contains($wikiVersion)) {
            $pos = $this->collWikiVersions->search($wikiVersion);
            $this->collWikiVersions->remove($pos);
            if (null === $this->wikiVersionsScheduledForDeletion) {
                $this->wikiVersionsScheduledForDeletion = clone $this->collWikiVersions;
                $this->wikiVersionsScheduledForDeletion->clear();
            }
            $this->wikiVersionsScheduledForDeletion[]= clone $wikiVersion;
            $wikiVersion->setWiki(null);
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
        if (null !== $this->aUser) {
            $this->aUser->removeWiki($this);
        }
        $this->id = null;
        $this->title = null;
        $this->path = null;
        $this->image = null;
        $this->content = null;
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
            if ($this->collMapPointsRelatedByParentWikiId) {
                foreach ($this->collMapPointsRelatedByParentWikiId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMapPointsRelatedByTargetWikiId) {
                foreach ($this->collMapPointsRelatedByTargetWikiId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWikiVersions) {
                foreach ($this->collWikiVersions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMapPointsRelatedByParentWikiId = null;
        $this->collMapPointsRelatedByTargetWikiId = null;
        $this->collWikiVersions = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(WikiTableMap::DEFAULT_STRING_FORMAT);
    }

    // versionable behavior

    /**
     * Enforce a new Version of this object upon next save.
     *
     * @return $this|\BagOfIdeas\Models\Wiki\Wiki
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

        if (ChildWikiQuery::isVersioningEnabled() && ($this->isNew() || $this->isModified()) || $this->isDeleted()) {
            return true;
        }
        if ($this->collMapPointsRelatedByParentWikiId) {

            // to avoid infinite loops, emulate in save
            $this->alreadyInSave = true;

            foreach ($this->getMapPointsRelatedByParentWikiId(null, $con) as $relatedObject) {

                if ($relatedObject->isVersioningNecessary($con)) {

                    $this->alreadyInSave = false;
                    return true;
                }
            }
            $this->alreadyInSave = false;
        }

        if ($this->collMapPointsRelatedByTargetWikiId) {

            // to avoid infinite loops, emulate in save
            $this->alreadyInSave = true;

            foreach ($this->getMapPointsRelatedByTargetWikiId(null, $con) as $relatedObject) {

                if ($relatedObject->isVersioningNecessary($con)) {

                    $this->alreadyInSave = false;
                    return true;
                }
            }
            $this->alreadyInSave = false;
        }


        return false;
    }

    /**
     * Creates a version of the current object and saves it.
     *
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     *
     * @return  ChildWikiVersion A version object
     */
    public function addVersion(ConnectionInterface $con = null)
    {
        $this->enforceVersion = false;

        $version = new ChildWikiVersion();
        $version->setId($this->getId());
        $version->setTitle($this->getTitle());
        $version->setPath($this->getPath());
        $version->setImage($this->getImage());
        $version->setContent($this->getContent());
        $version->setUserId($this->getUserId());
        $version->setCreatedAt($this->getCreatedAt());
        $version->setUpdatedAt($this->getUpdatedAt());
        $version->setVersion($this->getVersion());
        $version->setWiki($this);
        $object = $this->getMapPointsRelatedByParentWikiId(null, $con);


        if ($object && $relateds = $object->toKeyValue('Id', 'Version')) {
            $version->setMapPointIds(array_keys($relateds));
            $version->setMapPointVersions(array_values($relateds));
        }

        $object = $this->getMapPointsRelatedByTargetWikiId(null, $con);


        if ($object && $relateds = $object->toKeyValue('Id', 'Version')) {
            $version->setMapPointIds(array_keys($relateds));
            $version->setMapPointVersions(array_values($relateds));
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
     * @return  $this|ChildWiki The current object (for fluent API support)
     */
    public function toVersion($versionNumber, ConnectionInterface $con = null)
    {
        $version = $this->getOneVersion($versionNumber, $con);
        if (!$version) {
            throw new PropelException(sprintf('No ChildWiki object found with version %d', $version));
        }
        $this->populateFromVersion($version, $con);

        return $this;
    }

    /**
     * Sets the properties of the current object to the value they had at a specific version
     *
     * @param ChildWikiVersion $version The version object to use
     * @param ConnectionInterface   $con the connection to use
     * @param array                 $loadedObjects objects that been loaded in a chain of populateFromVersion calls on referrer or fk objects.
     *
     * @return $this|ChildWiki The current object (for fluent API support)
     */
    public function populateFromVersion($version, $con = null, &$loadedObjects = array())
    {
        $loadedObjects['ChildWiki'][$version->getId()][$version->getVersion()] = $this;
        $this->setId($version->getId());
        $this->setTitle($version->getTitle());
        $this->setPath($version->getPath());
        $this->setImage($version->getImage());
        $this->setContent($version->getContent());
        $this->setUserId($version->getUserId());
        $this->setCreatedAt($version->getCreatedAt());
        $this->setUpdatedAt($version->getUpdatedAt());
        $this->setVersion($version->getVersion());
        if ($fkValues = $version->getMapPointIds()) {
            $this->clearMapPointsRelatedByParentWikiId();
            $fkVersions = $version->getMapPointVersions();
            $query = MapPointVersionQuery::create();
            foreach ($fkValues as $key => $value) {
                $c1 = $query->getNewCriterion(MapPointVersionTableMap::COL_ID, $value);
                $c2 = $query->getNewCriterion(MapPointVersionTableMap::COL_VERSION, $fkVersions[$key]);
                $c1->addAnd($c2);
                $query->addOr($c1);
            }
            foreach ($query->find($con) as $relatedVersion) {
                if (isset($loadedObjects['MapPoint']) && isset($loadedObjects['MapPoint'][$relatedVersion->getId()]) && isset($loadedObjects['MapPoint'][$relatedVersion->getId()][$relatedVersion->getVersion()])) {
                    $related = $loadedObjects['MapPoint'][$relatedVersion->getId()][$relatedVersion->getVersion()];
                } else {
                    $related = new MapPoint();
                    $related->populateFromVersion($relatedVersion, $con, $loadedObjects);
                    $related->setNew(false);
                }
                $this->addMapPointRelatedByParentWikiId($related);
                $this->collMapPointsRelatedByParentWikiIdPartial = false;
            }
        }
        if ($fkValues = $version->getMapPointIds()) {
            $this->clearMapPointRelatedByTargetWikiId();
            $fkVersions = $version->getMapPointVersions();
            $query = MapPointVersionQuery::create();
            foreach ($fkValues as $key => $value) {
                $c1 = $query->getNewCriterion(MapPointVersionTableMap::COL_ID, $value);
                $c2 = $query->getNewCriterion(MapPointVersionTableMap::COL_VERSION, $fkVersions[$key]);
                $c1->addAnd($c2);
                $query->addOr($c1);
            }
            foreach ($query->find($con) as $relatedVersion) {
                if (isset($loadedObjects['MapPoint']) && isset($loadedObjects['MapPoint'][$relatedVersion->getId()]) && isset($loadedObjects['MapPoint'][$relatedVersion->getId()][$relatedVersion->getVersion()])) {
                    $related = $loadedObjects['MapPoint'][$relatedVersion->getId()][$relatedVersion->getVersion()];
                } else {
                    $related = new MapPoint();
                    $related->populateFromVersion($relatedVersion, $con, $loadedObjects);
                    $related->setNew(false);
                }
                $this->addMapPointRelatedByTargetWikiId($related);
                $this->collMapPointRelatedByTargetWikiIdPartial = false;
            }
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
        $v = ChildWikiVersionQuery::create()
            ->filterByWiki($this)
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
     * @return  ChildWikiVersion A version object
     */
    public function getOneVersion($versionNumber, ConnectionInterface $con = null)
    {
        return ChildWikiVersionQuery::create()
            ->filterByWiki($this)
            ->filterByVersion($versionNumber)
            ->findOne($con);
    }

    /**
     * Gets all the versions of this object, in incremental order
     *
     * @param   ConnectionInterface $con The ConnectionInterface connection to use.
     *
     * @return  ObjectCollection|ChildWikiVersion[] A list of ChildWikiVersion objects
     */
    public function getAllVersions(ConnectionInterface $con = null)
    {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(WikiVersionTableMap::COL_VERSION);

        return $this->getWikiVersions($criteria, $con);
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
     * @return PropelCollection|\BagOfIdeas\Models\Wiki\WikiVersion[] List of \BagOfIdeas\Models\Wiki\WikiVersion objects
     */
    public function getLastVersions($number = 10, $criteria = null, ConnectionInterface $con = null)
    {
        $criteria = ChildWikiVersionQuery::create(null, $criteria);
        $criteria->addDescendingOrderByColumn(WikiVersionTableMap::COL_VERSION);
        $criteria->limit($number);

        return $this->getWikiVersions($criteria, $con);
    }
    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildWiki The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[WikiTableMap::COL_UPDATED_AT] = true;

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
