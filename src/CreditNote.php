<?php

namespace NumNum\UBL;

use Sabre\Xml\XmlSerializable;

class CreditNote extends Invoice implements XmlSerializable
{
    public $xmlTagName = 'CreditNote';
    protected $invoiceTypeCode = InvoiceTypeCode::CREDIT_NOTE;

    /**
     * @return CreditNoteLine[]
     */
    public function getCreditNoteLines(): ?array
    {
        return $this->invoiceLines;
    }

    /**
     * @param CreditNoteLine[] $creditNoteLines
     * @return static
     */
    public function setCreditNoteLines(array $creditNoteLines)
    {
        $this->invoiceLines = $creditNoteLines;
        return $this;
    }
}
