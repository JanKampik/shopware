<?php
/**
 * Shopware 4
 * Copyright © shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Bundle\StoreFrontBundle\Gateway\DBAL;

use Doctrine\DBAL\Query\QueryBuilder;
use Shopware\Components\Model\ModelManager;
use Shopware\Bundle\StoreFrontBundle\Struct;
use Shopware\Bundle\StoreFrontBundle\Gateway;

/**
 * @package Shopware\Bundle\StoreFrontBundle\Gateway\DBAL
 */
class FieldHelper
{
    /**
     * Contains the selection for the s_articles_attributes table.
     * This table contains dynamically columns.
     *
     * @var array
     */
    private $attributeFields = array();

    /**
     * @var ModelManager
     */
    private $entityManager;

    /**
     * @param ModelManager $entityManager
     */
    function __construct(ModelManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Helper function which generates an array with table column selections
     * for the passed table.
     *
     * @param $table
     * @param $alias
     * @return array
     */
    public function getTableFields($table, $alias)
    {
        $key = $table . '_' . $alias;

        if ($this->attributeFields[$key] !== null) {
            return $this->attributeFields[$key];
        }

        $schemaManager = $this->entityManager->getConnection()->getSchemaManager();

        $tableColumns = $schemaManager->listTableColumns($table);
        $columns = array();

        foreach ($tableColumns as $column) {
            $columns[] = $alias . '.' . $column->getName() . ' as __' . $alias . '_' . $column->getName();
        }

        $this->attributeFields[$key] = $columns;

        return $this->attributeFields[$key];
    }


    /**
     * Defines which s_articles fields should be selected.
     * @return array
     */
    public function getArticleFields()
    {
        $fields = array(
            'product.id as __product_id',
            'product.supplierID as __product_supplierID',
            'product.name as __product_name',
            'product.description as __product_description',
            'product.description_long as __product_description_long',
            'product.shippingtime as __product_shippingtime',
            'product.datum as __product_datum',
            'product.active as __product_active',
            'product.taxID as __product_taxID',
            'product.pseudosales as __product_pseudosales',
            'product.topseller as __product_topseller',
            'product.metaTitle as __product_metaTitle',
            'product.keywords as __product_keywords',
            'product.changetime as __product_changetime',
            'product.pricegroupID as __product_pricegroupID',
            'product.pricegroupActive as __product_pricegroupActive',
            'product.filtergroupID as __product_filtergroupID',
            'product.laststock as __product_laststock',
            'product.crossbundlelook as __product_crossbundlelook',
            'product.notification as __product_notification',
            'product.template as __product_template',
            'product.mode as __product_mode',
            'product.main_detail_id as __product_main_detail_id',
            'product.available_from as __product_available_from',
            'product.available_to as __product_available_to',
            'product.configurator_set_id as __product_configurator_set_id',
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_articles_attributes', 'productAttribute')
        );

        return $fields;

    }

    public function getTopSellerFields()
    {
        return array(
            'topSeller.sales as __topSeller_sales'
        );
    }

    /**
     * Defines which s_articles_details fields should be selected.
     * @return array
     */
    public function getVariantFields()
    {
        return array(
            'variant.id as __variant_id',
            'variant.ordernumber as __variant_ordernumber',
            'variant.suppliernumber as __variant_suppliernumber',
            'variant.kind as __variant_kind',
            'variant.additionaltext as __variant_additionaltext',
            'variant.impressions as __variant_impressions',
            'variant.sales as __variant_sales',
            'variant.active as __variant_active',
            'variant.instock as __variant_instock',
            'variant.stockmin as __variant_stockmin',
            'variant.weight as __variant_weight',
            'variant.position as __variant_position',
            'variant.width as __variant_width',
            'variant.height as __variant_height',
            'variant.length as __variant_length',
            'variant.ean as __variant_ean',
            'variant.unitID as __variant_unitID',
            'variant.releasedate as __variant_releasedate',
            'variant.shippingfree as __variant_shippingfree',
            'variant.shippingtime as __variant_shippingtime',
        );
    }

    public function getEsdFields()
    {
        $fields = array (
            'esd.id as __esd_id',
            'esd.articleID as __esd_articleID',
            'esd.articledetailsID as __esd_articledetailsID',
            'esd.file as __esd_file',
            'esd.serials as __esd_serials',
            'esd.notification as __esd_notification',
            'esd.maxdownloads as __esd_maxdownloads',
            'esd.datum as __esd_datum',
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_articles_esd_attributes', 'esdAttribute')
        );

        return $fields;
    }

    /**
     * Defines which s_core_tax fields should be selected
     * @return array
     */
    public function getTaxFields()
    {
        return array(
            'tax.id as __tax_id',
            'tax.tax as __tax_tax',
            'tax.description as __tax_description'
        );
    }

    /**
     * Defines which s_core_pricegroups fields should be selected
     * @return array
     */
    public function getPriceGroupFields()
    {
        return array(
            'priceGroup.id as __priceGroup_id',
            'priceGroup.description as __priceGroup_description'
        );
    }

    /**
     * Defines which s_articles_suppliers fields should be selected
     * @return array
     */
    public function getManufacturerFields()
    {
        $fields = array(
            'manufacturer.id as __manufacturer_id',
            'manufacturer.name as __manufacturer_name',
            'manufacturer.img as __manufacturer_img',
            'manufacturer.link as __manufacturer_link',
            'manufacturer.description as __manufacturer_description',
            'manufacturer.meta_title as __manufacturer_meta_title',
            'manufacturer.meta_description as __manufacturer_meta_description',
            'manufacturer.meta_keywords as __manufacturer_meta_keywords'
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_articles_supplier_attributes', 'manufacturerAttribute')
        );

        return $fields;
    }

    public function getCategoryFields()
    {
        $fields = array(
            'category.id as __category_id',
            'category.path as __category_path',
            'category.description as __category_description',
            'category.metakeywords as __category_metakeywords',
            'category.metadescription as __category_metadescription',
            'category.cmsheadline as __category_cmsheadline',
            'category.cmstext as __category_cmstext',
            'category.template as __category_template',
            'category.noviewselect as __category_noviewselect',
            'category.blog as __category_blog',
            'category.showfiltergroups as __category_showfiltergroups',
            'category.external as __category_external',
            'category.hidefilter as __category_hidefilter',
            'category.hidetop as __category_hidetop',
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_categories_attributes', 'categoryAttribute')
        );

        return $fields;
    }


    public function getPriceFields()
    {
        $fields = array(
            'price.id as __price_id',
            'price.pricegroup as __price_pricegroup',
            'price.from as __price_from',
            'price.to as __price_to',
            'price.articleID as __price_articleID',
            'price.articledetailsID as __price_articledetailsID',
            'price.price as __price_price',
            'price.pseudoprice as __price_pseudoprice',
            'price.baseprice as __price_baseprice',
            'price.percent as __price_percent',
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_articles_prices_attributes', 'priceAttribute')
        );

        return $fields;
    }

    public function getUnitFields()
    {
        return array(
            'unit.id as __unit_id',
            'unit.description as __unit_description',
            'unit.unit as __unit_unit',
            'variant.packunit as __unit_packunit',
            'variant.purchaseunit as __unit_purchaseunit',
            'variant.referenceunit as __unit_referenceunit',
            'variant.purchasesteps as __unit_purchasesteps',
            'variant.minpurchase as __unit_minpurchase',
            'variant.maxpurchase as __unit_maxpurchase'
        );
    }

    public function getConfiguratorSetFields()
    {
        return array(
            'configuratorSet.id as __configuratorSet_id',
            'configuratorSet.name as __configuratorSet_name',
            'configuratorSet.type as __configuratorSet_type'
        );
    }

    public function getConfiguratorGroupFields()
    {
        return array(
            'configuratorGroup.id as __configuratorGroup_id',
            'configuratorGroup.name as __configuratorGroup_name',
            'configuratorGroup.description as __configuratorGroup_description',
            'configuratorGroup.position as __configuratorGroup_position'
        );
    }

    public function getConfiguratorOptionFields()
    {
        return array(
            'configuratorOption.id as __configuratorOption_id',
            'configuratorOption.name as __configuratorOption_name',
            'configuratorOption.position as __configuratorOption_position'
        );
    }


    public function getAreaFields()
    {
        return array(
            'countryArea.id as __countryArea_id',
            'countryArea.name as __countryArea_name',
            'countryArea.active as __countryArea_active',
        );
    }

    public function getCountryFields()
    {
        $fields = array(
            'country.id as __country_id',
            'country.countryname as __country_countryname',
            'country.countryiso as __country_countryiso',
            'country.areaID as __country_areaID',
            'country.countryen as __country_countryen',
            'country.position as __country_position',
            'country.notice as __country_notice',
            'country.shippingfree as __country_shippingfree',
            'country.taxfree as __country_taxfree',
            'country.taxfree_ustid as __country_taxfree_ustid',
            'country.taxfree_ustid_checked as __country_taxfree_ustid_checked',
            'country.active as __country_active',
            'country.iso3 as __country_iso3',
            'country.display_state_in_registration as __country_display_state_in_registration',
            'country.force_state_in_registration as __country_force_state_in_registration',
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_core_countries_attributes', 'countryAttribute')
        );

        return $fields;
    }

    public function getStateFields()
    {
        $fields = array(
            'countryState.id as __countryState_id',
            'countryState.countryID as __countryState_countryID',
            'countryState.name as __countryState_name',
            'countryState.shortcode as __countryState_shortcode',
            'countryState.position as __countryState_position',
            'countryState.active as __countryState_active',
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_core_countries_states_attributes', 'countryStateAttribute')
        );
        return $fields;
    }


    public function getCustomerGroupFields()
    {
        $fields = array(
            'customerGroup.id as __customerGroup_id',
            'customerGroup.groupkey as __customerGroup_groupkey',
            'customerGroup.description as __customerGroup_description',
            'customerGroup.tax as __customerGroup_tax',
            'customerGroup.taxinput as __customerGroup_taxinput',
            'customerGroup.mode as __customerGroup_mode',
            'customerGroup.discount as __customerGroup_discount',
            'customerGroup.minimumorder as __customerGroup_minimumorder',
            'customerGroup.minimumordersurcharge as __customerGroup_minimumordersurcharge',
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_core_customergroups_attributes', 'customerGroupAttribute')
        );

        return $fields;
    }


    public function getDownloadFields()
    {
        $fields = array(
            'download.id as __download_id',
            'download.articleID as __download_articleID',
            'download.description as __download_description',
            'download.filename as __download_filename',
            'download.size as __download_size',
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_articles_downloads_attributes', 'downloadAttribute')
        );

        return $fields;
    }


    public function getLinkFields()
    {
        $fields = array(
            'link.id as __link_id',
            'link.articleID as __link_articleID',
            'link.description as __link_description',
            'link.link as __link_link',
            'link.target as __link_target',
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_articles_information_attributes', 'linkAttribute')
        );

        return $fields;
    }

    public function getImageFields()
    {
        $fields = array(
            'image.id as __image_id',
            'image.articleID as __image_articleID',
            'image.img as __image_img',
            'image.main as __image_main',
            'image.description as __image_description',
            'image.position as __image_position',
            'image.width as __image_width',
            'image.height as __image_height',
            'image.extension as __image_extension',
            'image.parent_id as __image_parent_id',
            'image.media_id as __image_media_id'
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_articles_img_attributes', 'imageAttribute')
        );
        return $fields;
    }

    /**
     * Returns an array with all required media fields for a full media selection.
     * Requires that the s_media table is included with table alias 'media'
     *
     * @return array
     */
    public function getMediaFields()
    {
        $fields = array(
            'media.id as __media_id',
            'media.albumID as __media_albumID',
            'media.name as __media_name',
            'media.description as __media_description',
            'media.path as __media_path',
            'media.type as __media_type',
            'media.extension as __media_extension',
            'media.file_size as __media_file_size',
            'media.userID as __media_userID',
            'media.created as __media_created'
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_media_attributes', 'mediaAttribute')
        );

        return $fields;
    }

    /**
     * Returns an array with all required media settings fields.
     * This fields are required to generate thumbnails of each media.
     *
     * Requires that the s_media_album_settings table is included with alias "settings"
     *
     * @return array
     */
    public function getMediaSettingFields()
    {
        return array(
            'mediaSettings.id as __mediaSettings_id',
            'mediaSettings.create_thumbnails as __mediaSettings_create_thumbnails',
            'mediaSettings.thumbnail_size as __mediaSettings_thumbnail_size',
            'mediaSettings.icon as __mediaSettings_icon',
        );
    }

    public function getPriceGroupDiscountFields()
    {
        return array(
            'priceGroupDiscount.groupID as __priceGroupDiscount_groupID',
            'priceGroupDiscount.discount as __priceGroupDiscount_discount',
            'priceGroupDiscount.discountstart as __priceGroupDiscount_discountstart',
        );
    }


    public function getPropertySetFields()
    {
        $fields = array(
            'propertySet.id as __propertySet_id',
            'propertySet.name as __propertySet_name',
            'propertySet.position as __propertySet_position',
            'propertySet.comparable as __propertySet_comparable',
            'propertySet.sortmode as __propertySet_sortmode',
        );

        $fields = array_merge(
            $fields,
            $this->getTableFields('s_filter_attributes', 'propertySetAttribute')
        );

        return $fields;
    }

    public function getPropertyGroupFields()
    {
        return array(
            'propertyGroup.id as __propertyGroup_id',
            'propertyGroup.name as __propertyGroup_name',
            'propertyGroup.filterable as __propertyGroup_filterable',
            'propertyGroup.default as __propertyGroup_default',
        );
    }

    public function getPropertyOptionFields()
    {
        return array(
            'propertyOption.id as __propertyOption_id',
            'propertyOption.optionID as __propertyOption_optionID',
            'propertyOption.value as __propertyOption_value',
            'propertyOption.position as __propertyOption_position',
            'propertyOption.value_numeric as __propertyOption_value_numeric',
        );
    }

    public function getTaxRuleFields()
    {
        return array(
            'taxRule.groupID as __taxRule_groupID',
            'taxRule.tax as __taxRule_tax',
            'taxRule.name as __taxRule_name',
        );
    }


    public function getVoteFields()
    {
        return array(
            'vote.id as __vote_id',
            'vote.articleID as __vote_articleID',
            'vote.name as __vote_name',
            'vote.headline as __vote_headline',
            'vote.comment as __vote_comment',
            'vote.points as __vote_points',
            'vote.datum as __vote_datum',
            'vote.active as __vote_active',
            'vote.email as __vote_email',
            'vote.answer as __vote_answer',
            'vote.answer_date as __vote_answer_date',
        );
    }

    public function addPropertySetTranslation(QueryBuilder $query)
    {
        $query->leftJoin(
            'propertySet',
            's_core_translations',
            'propertySetTranslation',
            'propertySetTranslation.objecttype = :setTranslation AND
             propertySetTranslation.objectkey = propertySet.id AND
             propertySetTranslation.objectlanguage = :language'
        );

        $query->leftJoin(
            'propertyGroup',
            's_core_translations',
            'propertyGroupTranslation',
            'propertyGroupTranslation.objecttype = :groupTranslation AND
             propertyGroupTranslation.objectkey = propertyGroup.id AND
             propertyGroupTranslation.objectlanguage = :language'
        );

        $query->leftJoin(
            'propertyOption',
            's_core_translations',
            'propertyOptionTranslation',
            'propertyOptionTranslation.objecttype = :optionTranslation AND
             propertyOptionTranslation.objectkey = propertyOption.id AND
             propertyOptionTranslation.objectlanguage = :language'
        );

        $query->setParameter(':setTranslation', 'propertygroup')
            ->setParameter(':groupTranslation', 'propertyoption')
            ->setParameter(':optionTranslation', 'propertyvalue')
        ;

        $query->addSelect(array(
            'propertySetTranslation.objectdata as __propertySet_translation',
            'propertyGroupTranslation.objectdata as __propertyGroup_translation',
            'propertyOptionTranslation.objectdata as __propertyOption_translation'
        ));
    }

    public function addImageTranslation(QueryBuilder $query)
    {
        $query->leftJoin(
            'image',
            's_core_translations',
            'imageTranslation',
            'imageTranslation.objecttype = :imageType AND
             imageTranslation.objectkey = image.id AND
             imageTranslation.objectlanguage = :language'
        );
        $query->addSelect(array(
            'imageTranslation.objectdata as __image_translation',
        ));

        $query->setParameter(':imageType', 'articleimage');
    }

    public function addConfiguratorTranslation(QueryBuilder $query)
    {
        $query->leftJoin(
            'configuratorGroup',
            's_core_translations',
            'configuratorGroupTranslation',
            'configuratorGroupTranslation.objecttype = :configuratorGroupType AND
             configuratorGroupTranslation.objectkey = configuratorGroup.id AND
             configuratorGroupTranslation.objectlanguage = :language'
        );

        $query->leftJoin(
            'configuratorOption',
            's_core_translations',
            'configuratorOptionTranslation',
            'configuratorOptionTranslation.objecttype = :configuratorOptionType AND
             configuratorOptionTranslation.objectkey = configuratorOption .id AND
             configuratorOptionTranslation.objectlanguage = :language'
        );

        $query->setParameter(':configuratorGroupType', 'configuratorgroup')
           ->setParameter(':configuratorOptionType', 'configuratoroption');

        $query->addSelect(array(
            'configuratorGroupTranslation.objectdata as __configuratorGroup_translation',
            'configuratorOptionTranslation.objectdata as __configuratorOption_translation'
        ));
    }

    public function addUnitTranslation(QueryBuilder $query)
    {
        $query->leftJoin(
            'variant',
            's_core_translations',
            'unitTranslation',
            'unitTranslation.objecttype = :unitType AND
             unitTranslation.objectkey = 1 AND
             unitTranslation.objectlanguage = :language'
        );

        $query->addSelect(array('unitTranslation.objectdata as __unit_translation'))
            ->setParameter(':unitType', 'config_units');
    }

    public function addVariantTranslation(QueryBuilder $query)
    {
        $query->leftJoin(
            'variant',
            's_core_translations',
            'variantTranslation',
            'variantTranslation.objecttype = :variantType AND
             variantTranslation.objectkey = variant.id AND
             variantTranslation.objectlanguage = :language'
        );

        $query->addSelect('variantTranslation.objectdata as __variant_translation')
            ->setParameter(':variantType', 'variant');
    }

    public function addCountryTranslation(QueryBuilder $query)
    {
        $query->leftJoin(
            'country',
            's_core_translations',
            'countryTranslation',
            'countryTranslation.objecttype = :countryType AND
             countryTranslation.objectkey = 1 AND
             countryTranslation.objectlanguage = :language'
        );
        $query->addSelect('countryTranslation.objectdata as __country_translation')
            ->setParameter(':countryType', 'config_countries');
    }

    public function addCountryStateTranslation(QueryBuilder $query)
    {
        $query->leftJoin(
            'countryState',
            's_core_translations',
            'stateTranslation',
            'stateTranslation.objecttype = :stateType AND
             stateTranslation.objectkey = 1 AND
             stateTranslation.objectlanguage = :language'
        );
        $query->addSelect('stateTranslation.objectdata as __countryState_translation')
            ->setParameter(':stateType', 'config_country_states')
        ;
    }

    public function addProductTranslation(QueryBuilder $query)
    {
        $query->leftJoin(
            'variant',
            's_core_translations',
            'productTranslation',
            'productTranslation.objecttype = :productType AND
             productTranslation.objectkey = variant.articleID AND
             productTranslation.objectlanguage = :language'
        );

        $query->addSelect(array('productTranslation.objectdata as __product_translation'))
            ->setParameter(':productType', 'article');

    }

    public function addManufacturerTranslation(QueryBuilder $query)
    {
        $query->leftJoin(
            'manufacturer',
            's_core_translations',
            'manufacturerTranslation',
            'manufacturerTranslation.objecttype = :manufacturerType AND
             manufacturerTranslation.objectkey = 1 AND
             manufacturerTranslation.objectlanguage = :language'
        );
        $query->addSelect(array('manufacturerTranslation.objectdata as __manufacturer_translation'))
            ->setParameter(':manufacturerType', 'supplier');
    }


}
