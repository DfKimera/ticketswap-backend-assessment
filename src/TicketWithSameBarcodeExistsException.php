<?php

namespace TicketSwap\Assessment;

final class TicketWithSameBarcodeExistsException extends \Exception
{
	public static function withBarcode(Barcode $barcode) : self
	{
		return new self(
			sprintf(
				'A ticket already exists in the marketplace with the same barcode (%s)',
				(string) $barcode
			)
		);
	}
}