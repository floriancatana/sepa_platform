<?php

namespace Comment\Model\Map;

use Comment\Model\Comment;
use Comment\Model\CommentQuery;
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
 * This class defines the structure of the 'comment' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CommentTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Comment.Model.Map.CommentTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'comment';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Comment\\Model\\Comment';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Comment.Model.Comment';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 15;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 15;

    /**
     * the column name for the ID field
     */
    const ID = 'comment.ID';

    /**
     * the column name for the USERNAME field
     */
    const USERNAME = 'comment.USERNAME';

    /**
     * the column name for the CUSTOMER_ID field
     */
    const CUSTOMER_ID = 'comment.CUSTOMER_ID';

    /**
     * the column name for the REF field
     */
    const REF = 'comment.REF';

    /**
     * the column name for the REF_ID field
     */
    const REF_ID = 'comment.REF_ID';

    /**
     * the column name for the EMAIL field
     */
    const EMAIL = 'comment.EMAIL';

    /**
     * the column name for the TITLE field
     */
    const TITLE = 'comment.TITLE';

    /**
     * the column name for the CONTENT field
     */
    const CONTENT = 'comment.CONTENT';

    /**
     * the column name for the RATING field
     */
    const RATING = 'comment.RATING';

    /**
     * the column name for the STATUS field
     */
    const STATUS = 'comment.STATUS';

    /**
     * the column name for the VERIFIED field
     */
    const VERIFIED = 'comment.VERIFIED';

    /**
     * the column name for the ABUSE field
     */
    const ABUSE = 'comment.ABUSE';

    /**
     * the column name for the LOCALE field
     */
    const LOCALE = 'comment.LOCALE';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'comment.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'comment.UPDATED_AT';

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
        self::TYPE_PHPNAME       => array('Id', 'Username', 'CustomerId', 'Ref', 'RefId', 'Email', 'Title', 'Content', 'Rating', 'Status', 'Verified', 'Abuse', 'Locale', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'username', 'customerId', 'ref', 'refId', 'email', 'title', 'content', 'rating', 'status', 'verified', 'abuse', 'locale', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(CommentTableMap::ID, CommentTableMap::USERNAME, CommentTableMap::CUSTOMER_ID, CommentTableMap::REF, CommentTableMap::REF_ID, CommentTableMap::EMAIL, CommentTableMap::TITLE, CommentTableMap::CONTENT, CommentTableMap::RATING, CommentTableMap::STATUS, CommentTableMap::VERIFIED, CommentTableMap::ABUSE, CommentTableMap::LOCALE, CommentTableMap::CREATED_AT, CommentTableMap::UPDATED_AT, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'USERNAME', 'CUSTOMER_ID', 'REF', 'REF_ID', 'EMAIL', 'TITLE', 'CONTENT', 'RATING', 'STATUS', 'VERIFIED', 'ABUSE', 'LOCALE', 'CREATED_AT', 'UPDATED_AT', ),
        self::TYPE_FIELDNAME     => array('id', 'username', 'customer_id', 'ref', 'ref_id', 'email', 'title', 'content', 'rating', 'status', 'verified', 'abuse', 'locale', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Username' => 1, 'CustomerId' => 2, 'Ref' => 3, 'RefId' => 4, 'Email' => 5, 'Title' => 6, 'Content' => 7, 'Rating' => 8, 'Status' => 9, 'Verified' => 10, 'Abuse' => 11, 'Locale' => 12, 'CreatedAt' => 13, 'UpdatedAt' => 14, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'username' => 1, 'customerId' => 2, 'ref' => 3, 'refId' => 4, 'email' => 5, 'title' => 6, 'content' => 7, 'rating' => 8, 'status' => 9, 'verified' => 10, 'abuse' => 11, 'locale' => 12, 'createdAt' => 13, 'updatedAt' => 14, ),
        self::TYPE_COLNAME       => array(CommentTableMap::ID => 0, CommentTableMap::USERNAME => 1, CommentTableMap::CUSTOMER_ID => 2, CommentTableMap::REF => 3, CommentTableMap::REF_ID => 4, CommentTableMap::EMAIL => 5, CommentTableMap::TITLE => 6, CommentTableMap::CONTENT => 7, CommentTableMap::RATING => 8, CommentTableMap::STATUS => 9, CommentTableMap::VERIFIED => 10, CommentTableMap::ABUSE => 11, CommentTableMap::LOCALE => 12, CommentTableMap::CREATED_AT => 13, CommentTableMap::UPDATED_AT => 14, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'USERNAME' => 1, 'CUSTOMER_ID' => 2, 'REF' => 3, 'REF_ID' => 4, 'EMAIL' => 5, 'TITLE' => 6, 'CONTENT' => 7, 'RATING' => 8, 'STATUS' => 9, 'VERIFIED' => 10, 'ABUSE' => 11, 'LOCALE' => 12, 'CREATED_AT' => 13, 'UPDATED_AT' => 14, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'username' => 1, 'customer_id' => 2, 'ref' => 3, 'ref_id' => 4, 'email' => 5, 'title' => 6, 'content' => 7, 'rating' => 8, 'status' => 9, 'verified' => 10, 'abuse' => 11, 'locale' => 12, 'created_at' => 13, 'updated_at' => 14, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
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
        $this->setName('comment');
        $this->setPhpName('Comment');
        $this->setClassName('\\Comment\\Model\\Comment');
        $this->setPackage('Comment.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('USERNAME', 'Username', 'VARCHAR', false, 255, null);
        $this->addForeignKey('CUSTOMER_ID', 'CustomerId', 'INTEGER', 'customer', 'ID', false, null, null);
        $this->addColumn('REF', 'Ref', 'VARCHAR', false, 255, null);
        $this->addColumn('REF_ID', 'RefId', 'INTEGER', false, null, null);
        $this->addColumn('EMAIL', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', false, 255, null);
        $this->addColumn('CONTENT', 'Content', 'CLOB', false, null, null);
        $this->addColumn('RATING', 'Rating', 'TINYINT', false, null, null);
        $this->addColumn('STATUS', 'Status', 'TINYINT', false, null, 0);
        $this->addColumn('VERIFIED', 'Verified', 'TINYINT', false, null, null);
        $this->addColumn('ABUSE', 'Abuse', 'INTEGER', false, null, null);
        $this->addColumn('LOCALE', 'Locale', 'VARCHAR', false, 10, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Customer', '\\Comment\\Model\\Thelia\\Model\\Customer', RelationMap::MANY_TO_ONE, array('customer_id' => 'id', ), 'CASCADE', 'RESTRICT');
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
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
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
        return $withPrefix ? CommentTableMap::CLASS_DEFAULT : CommentTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (Comment object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CommentTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CommentTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CommentTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CommentTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CommentTableMap::addInstanceToPool($obj, $key);
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
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = CommentTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CommentTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CommentTableMap::addInstanceToPool($obj, $key);
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
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(CommentTableMap::ID);
            $criteria->addSelectColumn(CommentTableMap::USERNAME);
            $criteria->addSelectColumn(CommentTableMap::CUSTOMER_ID);
            $criteria->addSelectColumn(CommentTableMap::REF);
            $criteria->addSelectColumn(CommentTableMap::REF_ID);
            $criteria->addSelectColumn(CommentTableMap::EMAIL);
            $criteria->addSelectColumn(CommentTableMap::TITLE);
            $criteria->addSelectColumn(CommentTableMap::CONTENT);
            $criteria->addSelectColumn(CommentTableMap::RATING);
            $criteria->addSelectColumn(CommentTableMap::STATUS);
            $criteria->addSelectColumn(CommentTableMap::VERIFIED);
            $criteria->addSelectColumn(CommentTableMap::ABUSE);
            $criteria->addSelectColumn(CommentTableMap::LOCALE);
            $criteria->addSelectColumn(CommentTableMap::CREATED_AT);
            $criteria->addSelectColumn(CommentTableMap::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.USERNAME');
            $criteria->addSelectColumn($alias . '.CUSTOMER_ID');
            $criteria->addSelectColumn($alias . '.REF');
            $criteria->addSelectColumn($alias . '.REF_ID');
            $criteria->addSelectColumn($alias . '.EMAIL');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.CONTENT');
            $criteria->addSelectColumn($alias . '.RATING');
            $criteria->addSelectColumn($alias . '.STATUS');
            $criteria->addSelectColumn($alias . '.VERIFIED');
            $criteria->addSelectColumn($alias . '.ABUSE');
            $criteria->addSelectColumn($alias . '.LOCALE');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(CommentTableMap::DATABASE_NAME)->getTable(CommentTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(CommentTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(CommentTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new CommentTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a Comment or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Comment object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Comment\Model\Comment) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CommentTableMap::DATABASE_NAME);
            $criteria->add(CommentTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = CommentQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { CommentTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { CommentTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the comment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CommentQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Comment or Criteria object.
     *
     * @param mixed               $criteria Criteria or Comment object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Comment object
        }

        if ($criteria->containsKey(CommentTableMap::ID) && $criteria->keyContainsValue(CommentTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CommentTableMap::ID.')');
        }


        // Set the correct dbName
        $query = CommentQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // CommentTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CommentTableMap::buildTableMap();
