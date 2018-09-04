<?php

namespace TicketSwap\Assessment;

final class Seller
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function __toString() : string
    {
        return $this->name;
    }

    public function isPreviousBuyer(Buyer $buyer)
    {
    	return $this->name === $buyer->getName();
    }
}