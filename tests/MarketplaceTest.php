<?php

namespace TicketSwap\Assessment\tests;

use PHPUnit\Framework\TestCase;
use Money\Currency;
use Money\Money;
use TicketSwap\Assessment\Barcode;
use TicketSwap\Assessment\Buyer;
use TicketSwap\Assessment\Listing;
use TicketSwap\Assessment\ListingId;
use TicketSwap\Assessment\Marketplace;
use TicketSwap\Assessment\Seller;
use TicketSwap\Assessment\Ticket;
use TicketSwap\Assessment\TicketAlreadySoldException;
use TicketSwap\Assessment\TicketId;
use TicketSwap\Assessment\TicketNotFoundException;
use TicketSwap\Assessment\TicketWithSameBarcodeExistsException;

class MarketplaceTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_list_all_the_tickets_for_sale()
    {
        $marketplace = new Marketplace(
            [
                new Listing(
                    new ListingId('D59FDCCC-7713-45EE-A050-8A553A0F1169'),
                    new Seller('Pascal'),
                    [
                        new Ticket(
                            new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B'),
                            new Barcode('EAN-13', '38974312923')
                        ),
                    ],
                    new Money(4950, new Currency('EUR'))
                ),
            ]
        );

        $listingsForSale = $marketplace->getListingsForSale();

        $this->assertCount(1, $listingsForSale);
    }

    /**
     * @test
     */
    public function it_should_be_possible_to_buy_a_ticket()
    {
        $marketplace = new Marketplace(
            [
                new Listing(
                    new ListingId('D59FDCCC-7713-45EE-A050-8A553A0F1169'),
                    new Seller('Pascal'),
                    [
                        new Ticket(
                            new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B'),
                            new Barcode('EAN-13', '38974312923')
                        ),
                    ],
                    new Money(4950, new Currency('EUR'))
                ),
            ]
        );

        $boughtTicket = $marketplace->buyTicket(
            new Buyer('Sarah'),
            new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B')
        );

        $this->assertNotNull($boughtTicket);
        $this->assertSame('EAN-13:38974312923', (string) $boughtTicket->getBarcode());
    }

	/**
	 * @test
	 */
	public function it_should_not_be_possible_to_buy_a_ticket_that_does_not_exist()
	{
		$marketplace = new Marketplace(
			[
				new Listing(
					new ListingId('D59FDCCC-7713-45EE-A050-8A553A0F1169'),
					new Seller('Pascal'),
					[
						new Ticket(
							new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B'),
							new Barcode('EAN-13', '38974312923')
						),
					],
					new Money(4950, new Currency('EUR'))
				),
			]
		);

		$this->expectException(TicketNotFoundException::class);

		$buyAttempt = $marketplace->buyTicket(
			new Buyer('Sarah'),
			new TicketId('F265A462-68F4-4C44-8D17-D6C2315F0518')
		);

	}

    /**
     * @test
     */
    public function it_should_not_be_possible_to_buy_the_same_ticket_twice()
    {
        $marketplace = new Marketplace(
            [
                new Listing(
                    new ListingId('D59FDCCC-7713-45EE-A050-8A553A0F1169'),
                    new Seller('Pascal'),
                    [
                        new Ticket(
                            new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B'),
                            new Barcode('EAN-13', '38974312923')
                        ),
                    ],
                    new Money(4950, new Currency('EUR'))
                ),
            ]
        );

        $boughtTicket = $marketplace->buyTicket(
            new Buyer('Sarah'),
            new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B')
        );

	    $this->assertNotNull($boughtTicket);
	    $this->assertSame('EAN-13:38974312923', (string) $boughtTicket->getBarcode());

	    $this->expectException(TicketAlreadySoldException::class);

	    $secondAttempt = $marketplace->buyTicket(
		    new Buyer('John'),
		    new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B')
	    );
    }

    /**
     * @test
     */
    public function it_should_be_possible_to_put_a_listing_for_sale()
    {
        $marketplace = new Marketplace(
            [
                new Listing(
                    new ListingId('D59FDCCC-7713-45EE-A050-8A553A0F1169'),
                    new Seller('Pascal'),
                    [
                        new Ticket(
                            new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B'),
                            new Barcode('EAN-13', '38974312923')
                        ),
                    ],
                    new Money(4950, new Currency('EUR'))
                ),
            ]
        );

        $marketplace->setListingForSale(
            new Listing(
                new ListingId('26A7E5C4-3F59-4B3C-B5EB-6F2718BC31AD'),
                new Seller('Tom'),
                [
                    new Ticket(
                        new TicketId('45B96761-E533-4925-859F-3CA62182848E'),
                        new Barcode('EAN-13', '893759834')
                    ),
                ],
                new Money(4950, new Currency('EUR'))
            )
        );

        $listingsForSale = $marketplace->getListingsForSale();

        $this->assertCount(2, $listingsForSale);
    }

    /**
     * @test
     */
    public function it_should_not_be_possible_to_sell_a_ticket_with_a_barcode_that_is_already_for_sale()
    {
	    $marketplace = new Marketplace(
		    [
			    new Listing(
				    new ListingId('D59FDCCC-7713-45EE-A050-8A553A0F1169'),
				    new Seller('Pascal'),
				    [
					    new Ticket(
						    new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B'),
						    new Barcode('EAN-13', '38974312923')
					    ),
				    ],
				    new Money(4950, new Currency('EUR'))
			    ),
		    ]
	    );

	    $this->expectException(TicketWithSameBarcodeExistsException::class);

	    $marketplace->setListingForSale(
		    new Listing(
			    new ListingId('26A7E5C4-3F59-4B3C-B5EB-6F2718BC31AD'),
			    new Seller('Tom'),
			    [
				    new Ticket(
					    new TicketId('45B96761-E533-4925-859F-3CA62182848E'),
					    new Barcode('EAN-13', '38974312923')
				    ),
			    ],
			    new Money(4950, new Currency('EUR'))
		    )
	    );
    }

    /**
     * @test
     */
    public function it_should_be_possible_for_a_buyer_of_a_ticket_to_sell_it_again()
    {

    	$ticket = new Ticket(
		    new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B'),
		    new Barcode('EAN-13', '38974312923')
	    );

	    $marketplace = new Marketplace(
		    [
			    new Listing(
				    new ListingId('D59FDCCC-7713-45EE-A050-8A553A0F1169'),
				    new Seller('Pascal'),
				    [$ticket],
				    new Money(4950, new Currency('EUR'))
			    ),
		    ]
	    );

	    $firstPurchase = $marketplace->buyTicket(
		    new Buyer('Sarah'),
		    new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B')
	    );

	    $this->assertNotNull($firstPurchase);
	    $this->assertSame('EAN-13:38974312923', (string) $firstPurchase->getBarcode());

	    $marketplace->setListingForSale(
		    new Listing(
			    new ListingId('26A7E5C4-3F59-4B3C-B5EB-6F2718BC31AD'),
			    new Seller('Sarah'),
			    [$ticket],
			    new Money(4950, new Currency('EUR'))
		    )
	    );

	    $listingsForSale = $marketplace->getListingsForSale();

	    $this->assertCount(2, $listingsForSale);

	    $secondPurchase = $marketplace->buyTicket(
		    new Buyer('Tom'),
		    new TicketId('6293BB44-2F5F-4E2A-ACA8-8CDF01AF401B')
	    );

	    $this->assertNotNull($secondPurchase);
	    $this->assertSame('EAN-13:38974312923', (string) $secondPurchase->getBarcode());

	    $marketplace->setListingForSale(
		    new Listing(
			    new ListingId('F67A208C-8FE4-4711-B165-35601FFECB7E'),
			    new Seller('Tom'),
			    [$ticket],
			    new Money(4950, new Currency('EUR'))
		    )
	    );

    }
}
