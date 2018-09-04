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

    public function findTicketById(TicketId $ticketId) : ?Ticket
    {
    	// TODO: this belongs in a repository

    	return collect($this->listingsForSale)
		    ->map(function (Listing $listing) {
			    return $listing->getTickets();
		    })
		    ->collapse()
		    ->first(function (Ticket $ticket) use ($ticketId) {
			    return $ticket->getId()->equals($ticketId);
		    });
    }

    public function buyTicket(Buyer $buyer, TicketId $ticketId) : Ticket
    {
        $ticketBeingSold = $this->findTicketById($ticketId);

        if(null === $ticketBeingSold) {
	        throw TicketNotFoundException::withTicketId($ticketId);
        }

        if($ticketBeingSold->isBought()) {
        	throw TicketAlreadySoldException::withTicket($ticketBeingSold);
        }

        return $ticketBeingSold->buyTicket($buyer);

    }

    public function setListingForSale(Listing $listing) : void
    {
    	array_push($this->listingsForSale, $listing);
    }
}