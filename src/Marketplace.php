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

    public function hasActiveListingWithBarcode(Barcode $barcode)
    {

	    // TODO: this belongs in a repository

    	return collect($this->listingsForSale)
		    ->map(function (Listing $listing) {
			    return $listing->getTickets(true);
		    })
		    ->collapse()
		    ->contains(function (Ticket $ticket) use ($barcode) {
		    	return $ticket->getBarcode()->equals($barcode);
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
    	$marketplace = $this;

    	// Validates listing's tickets before setting them for sale
    	collect($listing->getTickets())
		    ->each(function (Ticket $ticket) use ($marketplace, $listing) {

		    	// Can only resell already sold ticket if current seller is previous buyer
		    	if($ticket->isBought() && !$listing->getSeller()->isPreviousBuyer($ticket->getBuyer())) {
				    throw TicketAlreadySoldException::withTicket($ticket);
			    }

			    // Cannot put a ticket for sale with a barcode that already exists in the marketplace
			    if($marketplace->hasActiveListingWithBarcode($ticket->getBarcode())) {
				    throw TicketWithSameBarcodeExistsException::withBarcode($ticket->getBarcode());
			    }

			    $ticket->resetBuyer();

		    });

    	array_push($this->listingsForSale, $listing);
    }
}