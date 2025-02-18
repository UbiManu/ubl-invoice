<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class Item implements XmlSerializable, XmlDeserializable
{
    private $description;
    private $name;
    private $buyersItemIdentification;
    private $sellersItemIdentification;
    private $standardItemIdentification;
    private $standardItemIdentificationAttributes = [];
    private $classifiedTaxCategory;

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return static
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return static
     */
    public function setName(?string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSellersItemIdentification(): ?string
    {
        return $this->sellersItemIdentification;
    }

    /**
     * @param mixed $sellersItemIdentification
     * @return static
     */
    public function setSellersItemIdentification(?string $sellersItemIdentification)
    {
        $this->sellersItemIdentification = $sellersItemIdentification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStandardItemIdentification(): ?string
    {
        return $this->standardItemIdentification;
    }

    /**
     * @param mixed $standardItemIdentification
     * @return static
     */
    public function setStandardItemIdentification(?string $standardItemIdentification, $attributes = null)
    {
        $this->standardItemIdentification = $standardItemIdentification;
        if (isset($attributes)) {
            $this->standardItemIdentificationAttributes = $attributes;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuyersItemIdentification(): ?string
    {
        return $this->buyersItemIdentification;
    }

    /**
     * @param mixed $buyersItemIdentification
     * @return static
     */
    public function setBuyersItemIdentification(?string $buyersItemIdentification)
    {
        $this->buyersItemIdentification = $buyersItemIdentification;
        return $this;
    }

    /**
     * @return ClassifiedTaxCategory
     */
    public function getClassifiedTaxCategory(): ?ClassifiedTaxCategory
    {
        return $this->classifiedTaxCategory;
    }

    /**
     * @param ClassifiedTaxCategory $classifiedTaxCategory
     * @return static
     */
    public function setClassifiedTaxCategory(?ClassifiedTaxCategory $classifiedTaxCategory)
    {
        $this->classifiedTaxCategory = $classifiedTaxCategory;
        return $this;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        if (!empty($this->getDescription())) {
            $writer->write([
                Schema::CBC . 'Description' => $this->description
            ]);
        }

        $writer->write([
            Schema::CBC . 'Name' => $this->name
        ]);

        if (!empty($this->getBuyersItemIdentification())) {
            $writer->write([
                Schema::CAC . 'BuyersItemIdentification' => [
                    Schema::CBC . 'ID' => $this->buyersItemIdentification
                ],
            ]);
        }

        if (!empty($this->getSellersItemIdentification())) {
            $writer->write([
                Schema::CAC . 'SellersItemIdentification' => [
                    Schema::CBC . 'ID' => $this->sellersItemIdentification
                ],
            ]);
        }

        if (!empty($this->getStandardItemIdentification())) {
            $writer->write([
                Schema::CAC . 'StandardItemIdentification' => [
                    Schema::CBC . 'ID' => [
                        'value'      => $this->standardItemIdentification,
                        'attributes' => $this->standardItemIdentificationAttributes
                    ]
                ]
            ]);
        }

        if (!empty($this->getClassifiedTaxCategory())) {
            $writer->write([
                Schema::CAC . 'ClassifiedTaxCategory' => $this->getClassifiedTaxCategory()
            ]);
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $xml
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = mixedContent($reader);
        $collection = new ArrayCollection($mixedContent);

        $descriptionTag = ReaderHelper::getTag(Schema::CBC . 'Description', $collection);
        $nameTag = ReaderHelper::getTag(Schema::CBC . 'Name', $collection);
        $classifiedTaxCategoryTag = ReaderHelper::getTag(Schema::CAC . 'ClassifiedTaxCategory', $collection);

        $buyersItemIdentificationTag = ReaderHelper::getTag(Schema::CAC . 'BuyersItemIdentification', $collection);
        $buyersItemIdentificationIdTag = ReaderHelper::getTag(
            Schema::CBC . 'ID',
            new ArrayCollection($buyersItemIdentificationTag['value'] ?? [])
        );

        $sellersItemIdentificationTag = ReaderHelper::getTag(Schema::CAC . 'SellersItemIdentification', $collection);
        $sellersItemIdentificationIdTag = ReaderHelper::getTag(
            Schema::CBC . 'ID',
            new ArrayCollection($sellersItemIdentificationTag['value'] ?? [])
        );

        $standardItemIdentificationTag = ReaderHelper::getTag(Schema::CAC . 'StandardItemIdentification', $collection);
        $standardItemIdentificationIdTag = ReaderHelper::getTag(
            Schema::CBC . 'ID',
            new ArrayCollection($standardItemIdentificationTag['value'] ?? [])
        );

        return (new static())
            ->setDescription($descriptionTag['value'] ?? null)
            ->setName($nameTag['value'] ?? null)
            ->setBuyersItemIdentification($buyersItemIdentificationIdTag['value'] ?? null)
            ->setSellersItemIdentification($sellersItemIdentificationIdTag['value'] ?? null)
            ->setStandardItemIdentification($standardItemIdentificationIdTag['value'] ?? null, $standardItemIdentificationIdTag['attributes'] ?? null)
            ->setClassifiedTaxCategory($classifiedTaxCategoryTag['value'] ?? null)
        ;
    }
}
