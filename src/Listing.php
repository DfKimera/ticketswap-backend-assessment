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

	/**
	 * @var Admin
	 */
    private $verifier;

    public function __construct(ListingId $id, Seller $seller, array $tickets, Money $price, ?Admin $verifier = null)
    {
        $this->id = $id;
        $this->seller = $seller;
        $this->price = $price;
        $this->verifier = $verifier;

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

    public function isVerified() : bool
    {
    	return $this->verifier !== null;
    }

	public function setVerifier(Admin $admin) : self
	{
		$this->verifier = $admin;

		return $this;
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
    	if($forSale === null) {
    		return $this->tickets;
	    }

	    return collect($this->tickets)
		    ->filter(function (Ticket $ticket) use ($forSale) {
		    	return $ticket->isBought() !== $forSale;
		    })
		    ->values()
		    ->toArray();
    }

    public function getPrice() : Money
    {
        return $this->price;
    }
}