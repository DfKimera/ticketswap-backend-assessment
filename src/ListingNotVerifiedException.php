<?php

namespace TicketSwap\Assessment;

final class ListingNotVerifiedException extends \Exception
{
    public static function withListing(Listing $listing) : self
    {
        return new self(
            sprintf(
                'Listing (%s) is not yet verified by an admin',
                (string) $listing->getId()
            )
        );
    }
}