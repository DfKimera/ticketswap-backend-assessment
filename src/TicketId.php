<?php

namespace TicketSwap\Assessment;

final class TicketId
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

    public function equals(TicketId $otherId) : bool
    {
        return $this->id === $otherId->id;
    }
}