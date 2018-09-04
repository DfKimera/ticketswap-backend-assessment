<?php

namespace TicketSwap\Assessment;

final class Admin
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

    public function getName() : string
    {
    	return $this->name;
    }
}