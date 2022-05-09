<?php

namespace Checkout\Tests\Instruments\Four;

use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Four\AccountHolderType;
use Checkout\Instruments\Four\Create\CreateBankAccountInstrumentRequest;
use Checkout\Instruments\Four\Get\BankAccountFieldQuery;
use Checkout\Instruments\Four\Get\PaymentNetwork;
use Checkout\Instruments\Four\InstrumentsClient;
use Checkout\Instruments\Four\Update\UpdateCardInstrumentRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class InstrumentsClientTest extends UnitTestFixture
{
    /**
     * @var InstrumentsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$four);
        $this->client = new InstrumentsClient($this->apiClient, $this->configuration);
    }


    /**
     * @test
     */
    public function shouldCreateInstrument()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->create(new CreateBankAccountInstrumentRequest());
        $this->assertNotNull($response);
    }


    /**
     * @test
     */
    public function shouldGetInstrument()
    {
        $this->apiClient->method("get")
            ->willReturn("foo");


        $response = $this->client->get("instrument_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldUpdateInstrument()
    {
        $this->apiClient->method("patch")
            ->willReturn("foo");


        $response = $this->client->update("instrument_id", new UpdateCardInstrumentRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldDeleteInstruments()
    {
        $this->apiClient->method("delete")
            ->willReturn("foo");

        $response = $this->client->delete("instrument_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldGetBankAccountFieldFormatting()
    {
        $this->apiClient->method("query")
            ->willReturn("foo");

        $request = new BankAccountFieldQuery();
        $request->payment_network = PaymentNetwork::$local;
        $request->account_holder_type = AccountHolderType::$individual;

        $response = $this->client->getBankAccountFieldFormatting(Country::$GB, Currency::$GBP, $request);
        $this->assertNotNull($response);
    }
}
