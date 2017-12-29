<?php
namespace GeneralImportExport\Export;

use MultipleFullfilmentCenters\Model\FulfilmentCenterProductsQuery;
use MultipleFullfilmentCenters\Model\FulfilmentCenterQuery;
use MultipleFullfilmentCenters\Model\Map\FulfilmentCenterTableMap;
use Thelia\ImportExport\Export\AbstractExport;

/**
 * Class GeizhalsProductStockExport
 *
 * @author Alexandra Stranici <alis.stranici@sepa.at>
 */
class GeizhalsProductStockExport extends AbstractExport
{

    const FILE_NAME = 'stock_geizhals';

    /**
     * Apply order and aliases on data
     *
     * @param array $data
     *            Raw data
     *            
     * @return array Ordered and aliased data
     */
    public function applyOrderAndAliases(array $data)
    {
        $processedData = [];
        
        // get all fulfilment centers to populate table header
        $fulfilmentCenters = FulfilmentCenterQuery::create()->select('name')->find();
        
        // get centers & stock for a specific product
        // send to ExportHandler all the centers & stock for a product in a single array
        $allCentersProduct = FulfilmentCenterProductsQuery::create()->addSelfSelectColumns()
            ->useFulfilmentCenterQuery()
            ->withColumn(FulfilmentCenterTableMap::NAME, 'CenterName')
            ->endUse()
            ->filterByProductId($data['fulfilment_center_products.PRODUCT_ID'])
            ->find();
        
        $processedData['Artikelnummer'] = $data['fulfilment_center_products.PRODUCT_ID'];
        
        // fill the stock for all centers with 0 value
        foreach ($fulfilmentCenters as $key => $fulfilmentCenterName) {
            $processedData[$fulfilmentCenterName] = '0';
        }
        
        // fill the stock with the real values
        foreach ($allCentersProduct as $key => $value) {
            $processedData[$value->getVirtualColumn('CenterName')] = $value->getProductStock();
        }
        
        return $processedData;
    }

    protected function getData()
    {
        $query = FulfilmentCenterProductsQuery::create()->groupByProductId();
        
        return $query;
    }
}
