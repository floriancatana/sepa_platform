<?php

namespace CriteriaSearch\Model\Base;

use \Exception;
use \PDO;
use CriteriaSearch\Model\CriteriaSearchCategoryTaxRule as ChildCriteriaSearchCategoryTaxRule;
use CriteriaSearch\Model\CriteriaSearchCategoryTaxRuleQuery as ChildCriteriaSearchCategoryTaxRuleQuery;
use CriteriaSearch\Model\Map\CriteriaSearchCategoryTaxRuleTableMap;
use CriteriaSearch\Model\Thelia\Model\Category;
use CriteriaSearch\Model\Thelia\Model\TaxRule;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'criteria_search_category_tax_rule' table.
 *
 *
 *
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery orderByTaxRuleId($order = Criteria::ASC) Order by the tax_rule_id column
 *
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery groupById() Group by the id column
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery groupByCategoryId() Group by the category_id column
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery groupByTaxRuleId() Group by the tax_rule_id column
 *
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery leftJoinCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Category relation
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery rightJoinCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Category relation
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery innerJoinCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the Category relation
 *
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery leftJoinTaxRule($relationAlias = null) Adds a LEFT JOIN clause to the query using the TaxRule relation
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery rightJoinTaxRule($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TaxRule relation
 * @method     ChildCriteriaSearchCategoryTaxRuleQuery innerJoinTaxRule($relationAlias = null) Adds a INNER JOIN clause to the query using the TaxRule relation
 *
 * @method     ChildCriteriaSearchCategoryTaxRule findOne(ConnectionInterface $con = null) Return the first ChildCriteriaSearchCategoryTaxRule matching the query
 * @method     ChildCriteriaSearchCategoryTaxRule findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCriteriaSearchCategoryTaxRule matching the query, or a new ChildCriteriaSearchCategoryTaxRule object populated from the query conditions when no match is found
 *
 * @method     ChildCriteriaSearchCategoryTaxRule findOneById(int $id) Return the first ChildCriteriaSearchCategoryTaxRule filtered by the id column
 * @method     ChildCriteriaSearchCategoryTaxRule findOneByCategoryId(int $category_id) Return the first ChildCriteriaSearchCategoryTaxRule filtered by the category_id column
 * @method     ChildCriteriaSearchCategoryTaxRule findOneByTaxRuleId(int $tax_rule_id) Return the first ChildCriteriaSearchCategoryTaxRule filtered by the tax_rule_id column
 *
 * @method     array findById(int $id) Return ChildCriteriaSearchCategoryTaxRule objects filtered by the id column
 * @method     array findByCategoryId(int $category_id) Return ChildCriteriaSearchCategoryTaxRule objects filtered by the category_id column
 * @method     array findByTaxRuleId(int $tax_rule_id) Return ChildCriteriaSearchCategoryTaxRule objects filtered by the tax_rule_id column
 *
 */
abstract class CriteriaSearchCategoryTaxRuleQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \CriteriaSearch\Model\Base\CriteriaSearchCategoryTaxRuleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\CriteriaSearch\\Model\\CriteriaSearchCategoryTaxRule', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCriteriaSearchCategoryTaxRuleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCriteriaSearchCategoryTaxRuleQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \CriteriaSearch\Model\CriteriaSearchCategoryTaxRuleQuery) {
            return $criteria;
        }
        $query = new \CriteriaSearch\Model\CriteriaSearchCategoryTaxRuleQuery();
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
     * @return ChildCriteriaSearchCategoryTaxRule|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CriteriaSearchCategoryTaxRuleTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CriteriaSearchCategoryTaxRuleTableMap::DATABASE_NAME);
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
     * @return   ChildCriteriaSearchCategoryTaxRule A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, CATEGORY_ID, TAX_RULE_ID FROM criteria_search_category_tax_rule WHERE ID = :p0';
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
            $obj = new ChildCriteriaSearchCategoryTaxRule();
            $obj->hydrate($row);
            CriteriaSearchCategoryTaxRuleTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildCriteriaSearchCategoryTaxRule|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(12, 56, 832), $con);
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
     * @return ChildCriteriaSearchCategoryTaxRuleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildCriteriaSearchCategoryTaxRuleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildCriteriaSearchCategoryTaxRuleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::ID, $id, $comparison);
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
     * @return ChildCriteriaSearchCategoryTaxRuleQuery The current query, for fluid interface
     */
    public function filterByCategoryId($categoryId = null, $comparison = null)
    {
        if (is_array($categoryId)) {
            $useMinMax = false;
            if (isset($categoryId['min'])) {
                $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::CATEGORY_ID, $categoryId, $comparison);
    }

    /**
     * Filter the query on the tax_rule_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTaxRuleId(1234); // WHERE tax_rule_id = 1234
     * $query->filterByTaxRuleId(array(12, 34)); // WHERE tax_rule_id IN (12, 34)
     * $query->filterByTaxRuleId(array('min' => 12)); // WHERE tax_rule_id > 12
     * </code>
     *
     * @see       filterByTaxRule()
     *
     * @param     mixed $taxRuleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCriteriaSearchCategoryTaxRuleQuery The current query, for fluid interface
     */
    public function filterByTaxRuleId($taxRuleId = null, $comparison = null)
    {
        if (is_array($taxRuleId)) {
            $useMinMax = false;
            if (isset($taxRuleId['min'])) {
                $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::TAX_RULE_ID, $taxRuleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($taxRuleId['max'])) {
                $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::TAX_RULE_ID, $taxRuleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::TAX_RULE_ID, $taxRuleId, $comparison);
    }

    /**
     * Filter the query by a related \CriteriaSearch\Model\Thelia\Model\Category object
     *
     * @param \CriteriaSearch\Model\Thelia\Model\Category|ObjectCollection $category The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCriteriaSearchCategoryTaxRuleQuery The current query, for fluid interface
     */
    public function filterByCategory($category, $comparison = null)
    {
        if ($category instanceof \CriteriaSearch\Model\Thelia\Model\Category) {
            return $this
                ->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::CATEGORY_ID, $category->getId(), $comparison);
        } elseif ($category instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::CATEGORY_ID, $category->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCategory() only accepts arguments of type \CriteriaSearch\Model\Thelia\Model\Category or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Category relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCriteriaSearchCategoryTaxRuleQuery The current query, for fluid interface
     */
    public function joinCategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return   \CriteriaSearch\Model\Thelia\Model\CategoryQuery A secondary query class using the current class as primary query
     */
    public function useCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Category', '\CriteriaSearch\Model\Thelia\Model\CategoryQuery');
    }

    /**
     * Filter the query by a related \CriteriaSearch\Model\Thelia\Model\TaxRule object
     *
     * @param \CriteriaSearch\Model\Thelia\Model\TaxRule|ObjectCollection $taxRule The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCriteriaSearchCategoryTaxRuleQuery The current query, for fluid interface
     */
    public function filterByTaxRule($taxRule, $comparison = null)
    {
        if ($taxRule instanceof \CriteriaSearch\Model\Thelia\Model\TaxRule) {
            return $this
                ->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::TAX_RULE_ID, $taxRule->getId(), $comparison);
        } elseif ($taxRule instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::TAX_RULE_ID, $taxRule->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTaxRule() only accepts arguments of type \CriteriaSearch\Model\Thelia\Model\TaxRule or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TaxRule relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCriteriaSearchCategoryTaxRuleQuery The current query, for fluid interface
     */
    public function joinTaxRule($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TaxRule');

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
            $this->addJoinObject($join, 'TaxRule');
        }

        return $this;
    }

    /**
     * Use the TaxRule relation TaxRule object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CriteriaSearch\Model\Thelia\Model\TaxRuleQuery A secondary query class using the current class as primary query
     */
    public function useTaxRuleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTaxRule($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TaxRule', '\CriteriaSearch\Model\Thelia\Model\TaxRuleQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCriteriaSearchCategoryTaxRule $criteriaSearchCategoryTaxRule Object to remove from the list of results
     *
     * @return ChildCriteriaSearchCategoryTaxRuleQuery The current query, for fluid interface
     */
    public function prune($criteriaSearchCategoryTaxRule = null)
    {
        if ($criteriaSearchCategoryTaxRule) {
            $this->addUsingAlias(CriteriaSearchCategoryTaxRuleTableMap::ID, $criteriaSearchCategoryTaxRule->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the criteria_search_category_tax_rule table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CriteriaSearchCategoryTaxRuleTableMap::DATABASE_NAME);
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
            CriteriaSearchCategoryTaxRuleTableMap::clearInstancePool();
            CriteriaSearchCategoryTaxRuleTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildCriteriaSearchCategoryTaxRule or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildCriteriaSearchCategoryTaxRule object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CriteriaSearchCategoryTaxRuleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CriteriaSearchCategoryTaxRuleTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        CriteriaSearchCategoryTaxRuleTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CriteriaSearchCategoryTaxRuleTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // CriteriaSearchCategoryTaxRuleQuery
