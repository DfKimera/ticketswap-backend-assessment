<?php

namespace TicketSwap\Assessment;

final class ListingId
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __toString() : string
    {
        return $this->id;
    }

    public function equals(ListingId $listingId) : bool
    {
    	return $this->id === $listingId->id;
    }
}