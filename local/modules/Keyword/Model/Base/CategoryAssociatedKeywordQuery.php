<?php

namespace Keyword\Model\Base;

use \Exception;
use \PDO;
use Keyword\Model\CategoryAssociatedKeyword as ChildCategoryAssociatedKeyword;
use Keyword\Model\CategoryAssociatedKeywordQuery as ChildCategoryAssociatedKeywordQuery;
use Keyword\Model\Map\CategoryAssociatedKeywordTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Thelia\Model\Category;

/**
 * Base class that represents a query for the 'category_associated_keyword' table.
 *
 *
 *
 * @method     ChildCategoryAssociatedKeywordQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method     ChildCategoryAssociatedKeywordQuery orderByKeywordId($order = Criteria::ASC) Order by the keyword_id column
 * @method     ChildCategoryAssociatedKeywordQuery orderByPosition($order = Criteria::ASC) Order by the position column
 *
 * @method     ChildCategoryAssociatedKeywordQuery groupByCategoryId() Group by the category_id column
 * @method     ChildCategoryAssociatedKeywordQuery groupByKeywordId() Group by the keyword_id column
 * @method     ChildCategoryAssociatedKeywordQuery groupByPosition() Group by the position column
 *
 * @method     ChildCategoryAssociatedKeywordQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCategoryAssociatedKeywordQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCategoryAssociatedKeywordQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCategoryAssociatedKeywordQuery leftJoinCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Category relation
 * @method     ChildCategoryAssociatedKeywordQuery rightJoinCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Category relation
 * @method     ChildCategoryAssociatedKeywordQuery innerJoinCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the Category relation
 *
 * @method     ChildCategoryAssociatedKeywordQuery leftJoinKeyword($relationAlias = null) Adds a LEFT JOIN clause to the query using the Keyword relation
 * @method     ChildCategoryAssociatedKeywordQuery rightJoinKeyword($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Keyword relation
 * @method     ChildCategoryAssociatedKeywordQuery innerJoinKeyword($relationAlias = null) Adds a INNER JOIN clause to the query using the Keyword relation
 *
 * @method     ChildCategoryAssociatedKeyword findOne(ConnectionInterface $con = null) Return the first ChildCategoryAssociatedKeyword matching the query
 * @method     ChildCategoryAssociatedKeyword findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCategoryAssociatedKeyword matching the query, or a new ChildCategoryAssociatedKeyword object populated from the query conditions when no match is found
 *
 * @method     ChildCategoryAssociatedKeyword findOneByCategoryId(int $category_id) Return the first ChildCategoryAssociatedKeyword filtered by the category_id column
 * @method     ChildCategoryAssociatedKeyword findOneByKeywordId(int $keyword_id) Return the first ChildCategoryAssociatedKeyword filtered by the keyword_id column
 * @method     ChildCategoryAssociatedKeyword findOneByPosition(int $position) Return the first ChildCategoryAssociatedKeyword filtered by the position column
 *
 * @method     array findByCategoryId(int $category_id) Return ChildCategoryAssociatedKeyword objects filtered by the category_id column
 * @method     array findByKeywordId(int $keyword_id) Return ChildCategoryAssociatedKeyword objects filtered by the keyword_id column
 * @method     array findByPosition(int $position) Return ChildCategoryAssociatedKeyword objects filtered by the position column
 *
 */
abstract class CategoryAssociatedKeywordQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Keyword\Model\Base\CategoryAssociatedKeywordQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\Keyword\\Model\\CategoryAssociatedKeyword', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCategoryAssociatedKeywordQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCategoryAssociatedKeywordQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \Keyword\Model\CategoryAssociatedKeywordQuery) {
            return $criteria;
        }
        $query = new \Keyword\Model\CategoryAssociatedKeywordQuery();
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
     * @param array[$category_id, $keyword_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCategoryAssociatedKeyword|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CategoryAssociatedKeywordTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CategoryAssociatedKeywordTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildCategoryAssociatedKeyword A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT CATEGORY_ID, KEYWORD_ID, POSITION FROM category_associated_keyword WHERE CATEGORY_ID = :p0 AND KEYWORD_ID = :p1';
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
            $obj = new ChildCategoryAssociatedKeyword();
            $obj->hydrate($row);
            CategoryAssociatedKeywordTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildCategoryAssociatedKeyword|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
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
    public function findPks($keys, $con = null)
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
     * @return ChildCategoryAssociatedKeywordQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(CategoryAssociatedKeywordTableMap::CATEGORY_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(CategoryAssociatedKeywordTableMap::KEYWORD_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildCategoryAssociatedKeywordQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(CategoryAssociatedKeywordTableMap::CATEGORY_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(CategoryAssociatedKeywordTableMap::KEYWORD_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCategoryId(1234); // WHERE category_id = 1234
     * $query->filterByCategoryId(array(12, 34)); // WHERE category_id IN (12, 34)
     * $query->filterByCategoryId(array('min' => 12)); // WHERE category_id > 12
     * </code>
     *
     * @see       filterByCategory()
     *
     * @param     mixed $categoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCategoryAssociatedKeywordQuery The current query, for fluid interface
     */
    public function filterByCategoryId($categoryId = null, $comparison = null)
    {
        if (is_array($categoryId)) {
            $useMinMax = false;
            if (isset($categoryId['min'])) {
                $this->addUsingAlias(CategoryAssociatedKeywordTableMap::CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(CategoryAssociatedKeywordTableMap::CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryAssociatedKeywordTableMap::CATEGORY_ID, $categoryId, $comparison);
    }

    /**
     * Filter the query on the keyword_id column
     *
     * Example usage:
     * <code>
     * $query->filterByKeywordId(1234); // WHERE keyword_id = 1234
     * $query->filterByKeywordId(array(12, 34)); // WHERE keyword_id IN (12, 34)
     * $query->filterByKeywordId(array('min' => 12)); // WHERE keyword_id > 12
     * </code>
     *
     * @see       filterByKeyword()
     *
     * @param     mixed $keywordId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCategoryAssociatedKeywordQuery The current query, for fluid interface
     */
    public function filterByKeywordId($keywordId = null, $comparison = null)
    {
        if (is_array($keywordId)) {
            $useMinMax = false;
            if (isset($keywordId['min'])) {
                $this->addUsingAlias(CategoryAssociatedKeywordTableMap::KEYWORD_ID, $keywordId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($keywordId['max'])) {
                $this->addUsingAlias(CategoryAssociatedKeywordTableMap::KEYWORD_ID, $keywordId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryAssociatedKeywordTableMap::KEYWORD_ID, $keywordId, $comparison);
    }

    /**
     * Filter the query on the position column
     *
     * Example usage:
     * <code>
     * $query->filterByPosition(1234); // WHERE position = 1234
     * $query->filterByPosition(array(12, 34)); // WHERE position IN (12, 34)
     * $query->filterByPosition(array('min' => 12)); // WHERE position > 12
     * </code>
     *
     * @param     mixed $position The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCategoryAssociatedKeywordQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(CategoryAssociatedKeywordTableMap::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(CategoryAssociatedKeywordTableMap::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryAssociatedKeywordTableMap::POSITION, $position, $comparison);
    }

    /**
     * Filter the query by a related \Thelia\Model\Category object
     *
     * @param \Thelia\Model\Category|ObjectCollection $category The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCategoryAssociatedKeywordQuery The current query, for fluid interface
     */
    public function filterByCategory($category, $comparison = null)
    {
        if ($category instanceof \Thelia\Model\Category) {
            return $this
                ->addUsingAlias(CategoryAssociatedKeywordTableMap::CATEGORY_ID, $category->getId(), $comparison);
        } elseif ($category instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CategoryAssociatedKeywordTableMap::CATEGORY_ID, $category->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCategory() only accepts arguments of type \Thelia\Model\Category or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Category relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCategoryAssociatedKeywordQuery The current query, for fluid interface
     */
    public function joinCategory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Category');

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
            $this->addJoinObject($join, 'Category');
        }

        return $this;
    }

    /**
     * Use the Category relation Category object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\CategoryQuery A secondary query class using the current class as primary query
     */
    public function useCategoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Category', '\Thelia\Model\CategoryQuery');
    }

    /**
     * Filter the query by a related \Keyword\Model\Keyword object
     *
     * @param \Keyword\Model\Keyword|ObjectCollection $keyword The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCategoryAssociatedKeywordQuery The current query, for fluid interface
     */
    public function filterByKeyword($keyword, $comparison = null)
    {
        if ($keyword instanceof \Keyword\Model\Keyword) {
            return $this
                ->addUsingAlias(CategoryAssociatedKeywordTableMap::KEYWORD_ID, $keyword->getId(), $comparison);
        } elseif ($keyword instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CategoryAssociatedKeywordTableMap::KEYWORD_ID, $keyword->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByKeyword() only accepts arguments of type \Keyword\Model\Keyword or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Keyword relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCategoryAssociatedKeywordQuery The current query, for fluid interface
     */
    public function joinKeyword($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Keyword');

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
            $this->addJoinObject($join, 'Keyword');
        }

        return $this;
    }

    /**
     * Use the Keyword relation Keyword object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Keyword\Model\KeywordQuery A secondary query class using the current class as primary query
     */
    public function useKeywordQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinKeyword($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Keyword', '\Keyword\Model\KeywordQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCategoryAssociatedKeyword $categoryAssociatedKeyword Object to remove from the list of results
     *
     * @return ChildCategoryAssociatedKeywordQuery The current query, for fluid interface
     */
    public function prune($categoryAssociatedKeyword = null)
    {
        if ($categoryAssociatedKeyword) {
            $this->addCond('pruneCond0', $this->getAliasedColName(CategoryAssociatedKeywordTableMap::CATEGORY_ID), $categoryAssociatedKeyword->getCategoryId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(CategoryAssociatedKeywordTableMap::KEYWORD_ID), $categoryAssociatedKeyword->getKeywordId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the category_associated_keyword table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CategoryAssociatedKeywordTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CategoryAssociatedKeywordTableMap::clearInstancePool();
            CategoryAssociatedKeywordTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildCategoryAssociatedKeyword or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildCategoryAssociatedKeyword object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CategoryAssociatedKeywordTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CategoryAssociatedKeywordTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        CategoryAssociatedKeywordTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CategoryAssociatedKeywordTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // CategoryAssociatedKeywordQuery
