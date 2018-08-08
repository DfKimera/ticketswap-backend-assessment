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
        $this->tickets = $tickets;
        $this->price = $price;
    }

    public function getId() : ListingId
    {
        return $this->id;
    }

    public function getSeller() : Seller
    {
        return $this->seller;
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