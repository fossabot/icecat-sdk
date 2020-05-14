<?php

declare(strict_types=1);
/**
 * ResultProduct
 *
 * @copyright Copyright © 2020 Rapid dive. All rights reserved.
 * @author    rapiddive1@gmai.com
 */

namespace Rapiddive\Icecat\Model;

use Rapiddive\Icecat\Exception\IcecatException;
use SimpleXMLElement;

/**
 * Class ResultProduct
 * @package Rapiddive\Icecat\Model
 */
class ResultProduct
{
    /**
     * @var SimpleXMLElement
     */
    private $XMLElement;

    /**
     * @var mixed
     */
    private $baseData;

    /**
     * ResultProduct constructor.
     * @param SimpleXMLElement|null $XMLElement
     */
    public function __construct(
        SimpleXMLElement $XMLElement = null
    ) {
        $this->XMLElement = $XMLElement;
        $this->setBaseProductData($XMLElement);
    }

    /**
     * @param SimpleXMLElement $xml
     */
    public function setBaseProductData(SimpleXMLElement $xml)
    {
        if (!$this->XMLElement) {
            $this->XMLElement = $xml;
        }
        $this->baseData = json_decode(json_encode($xml));
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getProductName()
    {
        return $this->getProductAttributes()->Name;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getProductAttributes()
    {
        return $this->getProduct()->{'@attributes'};
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getProduct()
    {
        return $this->getBaseData()->Product;
    }

    /**
     * @return $this
     * @throws IcecatException
     */
    public function getBaseData()
    {
        if ($this->isValidArticle($this->XMLElement)) {
            return $this->baseData;
        }
        return $this;
    }

    /**
     * @param SimpleXMLElement $response
     * @return bool
     * @throws IcecatException
     */
    public function isValidArticle(SimpleXMLElement $response)
    {
        if (isset($response->Product->attributes()->ErrorMessage)) {
            $code = isset($response->Product->attributes()->Code) ? (int)$response->Product->attributes()->Code : null;
            throw new IcecatException((string)$response->Product->attributes()->ErrorMessage,
                $code);
        }

        return true;
    }

    /**
     * @param string $attr
     * @return mixed
     * @throws IcecatException
     */
    public function __callProductAttr(string $attr)
    {
        return $this->getProductAttributes()->{$attr};
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getProductRelated()
    {
        return $this->getProduct()->ProductRelated;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getSupplierName()
    {
        return $this->getProduct()->Supplier->{'@attributes'}->Name;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getProductEANCode()
    {
        return $this->getProduct()->EANCode->{'@attributes'}->EAN;
    }

    /**
     * @param string $node
     * @return mixed
     * @throws IcecatException
     */
    public function __callProductSubNode(string $node)
    {
        return $this->getProduct()->{$node};
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getCategoryFeatureGroup()
    {
        return $this->getProduct()->CategoryFeatureGroup;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getCategory()
    {
        return $this->getProduct()->Category;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getFeatureLogo()
    {
        return $this->getProduct()->FeatureLogo;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getReleaseDate()
    {
        return $this->getProduct()->ReleaseDate->Date->{'@attributes'}->Value;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getEndOfLifeDate()
    {
        return $this->getProduct()->EndOfLifeDate->Date->{'@attributes'}->Value;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getReasonsToBuy()
    {
        return $this->getProduct()->ReasonsToBuy;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getBulletPoints()
    {
        return $this->getProduct()->BulletPoints;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getProductBundled()
    {
        return $this->getProduct()->ProductBundled;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws IcecatException
     */
    public function __call($name, $arguments)
    {
        return $this->getProduct()->{$name};
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getProductDescription()
    {
        return $this->getProduct()->ProductDescription;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getProductFamily()
    {
        return $this->getProduct()->ProductFamily;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getProductFeature()
    {
        return $this->getProduct()->ProductFeature;
    }

    /**
     * @return array
     * @throws IcecatException
     */
    public function getProductImages()
    {
        $image = '';
        $additionalImages = [];
        foreach ($this->getProductGallery() as $productImages) {
            foreach ($productImages as $productImage) {
                if ($productImage->{'@attributes'}->Original === '') {
                    continue;
                }
                if (isset($productImage->{'@attributes'}->IsMain)
                    && strtoupper($productImage->{'@attributes'}->IsMain) === 'Y'
                ) {
                    $image = $productImage->{'@attributes'}->Original;
                } else {
                    $additionalImages[] = $productImage->{'@attributes'}->Original;
                }
            }
        }
        return [$image, $additionalImages];
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getProductGallery()
    {
        return $this->getProduct()->ProductGallery;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function ProductMultimediaObject()
    {
        return $this->getProduct()->ProductMultimediaObject;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getShortSummaryDescription()
    {
        return $this->getSummaryDescription()->ShortSummaryDescription;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getSummaryDescription()
    {
        return $this->getProduct()->SummaryDescription;
    }

    /**
     * @return mixed
     * @throws IcecatException
     */
    public function getLongSummaryDescription()
    {
        return $this->getSummaryDescription()->LongSummaryDescription;
    }
}