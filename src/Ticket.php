<?php

namespace TicketSwap\Assessment;

final class Ticket
{
    /**
     * @var TicketId
     */
    private $id;

    /**
     * @var Barcode
     */
    private $barcode;

    /**
     * @var Buyer|null
     */
    private $buyer;

    public function __construct(TicketId $id, Barcode $barcode, ?Buyer $buyer = null)
    {
        $this->id = $id;
        $this->barcode = $barcode;
        $this->buyer = $buyer;
    }

    public function getId() : TicketId
    {
        return $this->id;
    }

    public function getBarcode() : Barcode
    {
        return $this->barcode;
    }

    public function getBuyer() : Buyer
    {
        return $this->buyer;
    }

    public function isBought() : bool
    {
        return $this->buyer !== null;
    }

    public function resetBuyer() : self
    {
    	$this->buyer = null;

    	return $this;
    }

    public function buyTicket(Buyer $buyer) : self
    {
        $this->buyer = $buyer;

        return $this;
    }
}