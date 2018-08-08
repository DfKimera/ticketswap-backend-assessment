<?php

namespace TicketSwap\Assessment;

final class Marketplace
{
    /**
     * @var array
     */
    private $listingsForSale;

    public function __construct(array $listingsForSale = [])
    {
        $this->listingsForSale = $listingsForSale;
    }

    public function getListingsForSale() : array
    {
        return $this->listingsForSale;
    }

    public function buyTicket(Buyer $buyer, TicketId $ticketId) : Ticket
    {
        foreach($this->listingsForSale as $listing) {
            foreach($listing->getTickets() as $ticket) {
                if ($ticket->getId()->equals($ticketId)) {
                   return $ticket->buyTicket($buyer); 
                }
            }
        }
    }

    public function setListingForSale(Listing $listing) : void
    {

    }
}