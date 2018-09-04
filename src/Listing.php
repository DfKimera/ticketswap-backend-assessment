<?php

namespace TicketSwap\Assessment;

use Money\Money;

final class Listing
{
    /**
     * @var ListingId
     */
    private $id;

    /**
     * @var Seller
     */
    private $seller;

    /**
     * @var array
     */
    private $tickets;

    /**
     * @var Money
     */
    private $price;

    public function __construct(ListingId $id, Seller $seller, array $tickets, Money $price)
    {
        $this->id = $id;
        $this->seller = $seller;
        $this->price = $price;

        $this->setTickets($tickets);
    }

    public function getId() : ListingId
    {
        return $this->id;
    }

    public function getSeller() : Seller
    {
        return $this->seller;
    }

    protected function setTickets(array $tickets)
    {
    	$this->tickets = collect($tickets)
		    ->unique(function (Ticket $ticket) {
		    	return strval($ticket->getBarcode());
		    })
		    ->toArray();
    }

    public function getTickets(?bool $forSale = null) : array
    {
        if (true === $forSale) {
            $forSaleTickets = [];
            foreach ($this->tickets as $ticket) {
                if (!$ticket->isBought()) {
                    $forSaleTickets[] = $ticket;
                }
            }

            return $forSaleTickets;
        } else if (false === $forSale) {
            $notForSaleTickets = [];
            foreach ($this->tickets as $ticket) {
                if ($ticket->isBought()) {
                    $notForSaleTickets[] = $ticket;
                }
            }

            return $notForSaleTickets;
        } else {
            return $this->tickets;
        }
    }

    public function getPrice() : Money
    {
        return $this->price;
    }
}