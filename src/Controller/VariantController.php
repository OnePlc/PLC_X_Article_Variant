<?php
/**
 * VariantController.php - Main Controller
 *
 * Main Controller for Article Variant Plugin
 *
 * @category Controller
 * @package Article\Variant
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

declare(strict_types=1);

namespace OnePlace\Article\Variant\Controller;

use Application\Controller\CoreEntityController;
use Application\Model\CoreEntityModel;
use OnePlace\Article\Variant\Model\VariantTable;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\AdapterInterface;

class VariantController extends CoreEntityController {
    /**
     * Article Table Object
     *
     * @since 1.0.0
     */
    protected $oTableGateway;

    /**
     * ArticleController constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @param ArticleTable $oTableGateway
     * @since 1.0.0
     */
    public function __construct(AdapterInterface $oDbAdapter,VariantTable $oTableGateway,$oServiceManager) {
        $this->oTableGateway = $oTableGateway;
        $this->sSingleForm = 'articlevariant-single';
        parent::__construct($oDbAdapter,$oTableGateway,$oServiceManager);

        if($oTableGateway) {
            # Attach TableGateway to Entity Models
            if(!isset(CoreEntityModel::$aEntityTables[$this->sSingleForm])) {
                CoreEntityModel::$aEntityTables[$this->sSingleForm] = $oTableGateway;
            }
        }
    }

    public function attachVariantForm($oItem = false) {
        $oForm = CoreEntityController::$aCoreTables['core-form']->select(['form_key'=>'articlevariant-single']);

        $aFields = [];
        $aUserFields = CoreEntityController::$oSession->oUser->getMyFormFields();
        if(array_key_exists('articlevariant-single',$aUserFields)) {
            $aFieldsTmp = $aUserFields['articlevariant-single'];
            if(count($aFieldsTmp) > 0) {
                # add all contact-base fields
                foreach($aFieldsTmp as $oField) {
                    if($oField->tab == 'variant-base') {
                        $aFields[] = $oField;
                    }
                }
            }
        }

        $aFieldsByTab = ['variant-base'=>$aFields];
        # Try to get adress table
        try {
            $oVariantTbl = CoreEntityController::$oServiceManager->get(VariantTable::class);
        } catch(\RuntimeException $e) {
            echo '<div class="alert alert-danger"><b>Error:</b> Could not load address table</div>';
            return [];
        }

        if(!isset($oVariantTbl)) {
            return [];
        }

        $aVariants = [];
        $oPrimaryVariant = false;
        if($oItem) {
            # load article addresses
            $oVariants = $oVariantTbl->fetchAll(false, ['article_idfs' => $oItem->getID()]);
            # get primary address
            if (count($oVariants) > 0) {
                foreach ($oVariants as $oAddr) {
                    $aVariants[] = $oAddr;
                }
            }
        }

        # Pass Data to View - which will pass it to our partial
        return [
            # must be named aPartialExtraData
            'aPartialExtraData' => [
                # must be name of your partial
                'article_variant'=> [
                    'oVariants'=>$aVariants,
                    'oForm'=>$oForm,
                    'aFormFields'=>$aFieldsByTab,
                ]
            ]
        ];
    }

    public function attachVariantToArticle($oItem,$aRawData) {
        $oItem->article_idfs = $aRawData['ref_idfs'];

        return $oItem;
    }

    public function attachVariantToArticleAPI($oItem) {
        # Try to get adress table
        try {
            $oVariantTbl = CoreEntityController::$oServiceManager->get(VariantTable::class);
        } catch(\RuntimeException $e) {
            return [];
        }
        $oVariants = $oVariantTbl->fetchAll(false, ['article_idfs' => $oItem->getID()]);
        $aResult = ['variants' => []];
        if (count($oVariants) > 0) {
            foreach ($oVariants as $oVar) {
                $aResult['variants'][] = (object)[
                    'id' => $oVar->getID(),
                    'label' => $oVar->getLabel(),
                    'price' => $oVar->price,
                ];
            }
        }

        return $aResult;
    }

    public function addAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * article-add-before (before show add form)
         * article-add-before-save (before save)
         * article-add-after-save (after save)
         */
        $iArticleID = $this->params()->fromRoute('id', 0);

        return $this->generateAddView('articlevariant','articlevariant-single','article','view',$iArticleID,['iArticleID'=>$iArticleID]);
    }
}
