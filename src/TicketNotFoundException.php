<?php

namespace TicketSwap\Assessment;

final class TicketNotFoundException extends \Exception
{
    public static function withTicketId(TicketId $ticketId) : self
    {
        return new self(
            sprintf(
                'Unable to find ticket with given ID (%s)',
                (string) $ticketId
            )
        );
    }
}