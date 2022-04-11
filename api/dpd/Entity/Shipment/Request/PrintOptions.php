<?php
namespace ShipCore\DPDDis\Entity\Shipment\Request;

use ShipCore\DataObject\DataObject;
use ShipCore\DataObject\Annotation\Accessible;

class PrintOptions extends DataObject
{
    /**
     * @Accessible()
     * @var string
     */
    protected $printerLanguage = 'PDF';
    
    /**
     * @Accessible()
     * @var string
     */
    protected $paperFormat;
}
