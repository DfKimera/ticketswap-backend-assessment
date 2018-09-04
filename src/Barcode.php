<?php

namespace TicketSwap\Assessment;

final class Barcode
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    public function __construct(string $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function equals(Barcode $barcode) : bool
    {
    	return $this->type === $barcode->type && $this->value === $barcode->value;
    }

    public function __toString() : string
    {
        return sprintf('%s:%s', $this->type, $this->value);
    }
}