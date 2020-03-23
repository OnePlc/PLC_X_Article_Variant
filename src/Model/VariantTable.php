<?php
/**
 * VariantTable.php - Variant Table
 *
 * Table Model for Variant Variant
 *
 * @category Model
 * @package Article\Variant
 * @author Verein onePlace
 * @copyright (C) 2020 Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Article\Variant\Model;

use Application\Controller\CoreController;
use Application\Model\CoreEntityTable;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;

class VariantTable extends CoreEntityTable {

    /**
     * VariantTable constructor.
     *
     * @param TableGateway $tableGateway
     * @since 1.0.0
     */
    public function __construct(TableGateway $tableGateway) {
        parent::__construct($tableGateway);

        # Set Single Form Name
        $this->sSingleForm = 'articlevariant-single';
    }

    /**
     * Get Variant Entity
     *
     * @param int $id
     * @param string $sKey
     * @return mixed
     * @since 1.0.0
     */
    public function getSingle($id,$sKey = 'Variant_ID') {
        # Use core function
        return $this->getSingleEntity($id,$sKey);
    }

    /**
     * Save Variant Entity
     *
     * @param Variant $oVariant
     * @return int Variant ID
     * @since 1.0.0
     */
    public function saveSingle(Variant $oVariant) {
        $aDefaultData = [
            'label' => $oVariant->label,
        ];

        return $this->saveSingleEntity($oVariant,'Variant_ID',$aDefaultData);
    }

    /**
     * Generate new single Entity
     *
     * @return Variant
     * @since 1.0.0
     */
    public function generateNew() {
        return new Variant($this->oTableGateway->getAdapter());
    }

    /**
     * Remove Variant
     *
     * @param $iVariantID
     * @return mixed
     * @since 1.0.4
     */
    public function removeSingle($iVariantID) {
        $iVariantID = (int)$iVariantID;
        if($iVariantID > 0) {
            $oVar = $this->getSingle($iVariantID);
            $this->oTableGateway->delete(['Variant_ID = '.$iVariantID]);

            $oMetricTbl = new TableGateway('core_metric', CoreController::$oDbAdapter);
            $oMetricTbl->insert([
                'user_idfs' => CoreController::$oSession->oUser->getID(),
                'action' => 'delete',
                'type' => 'article',
                'date' => date('Y-m-d H:i:s',time()),
                'comment' => 'Variant removed - label: '.$oVar->label.', price: '.$oVar->price,
            ]);

            return true;
        }

        return false;
    }
}