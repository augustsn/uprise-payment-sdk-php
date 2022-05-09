<?php

namespace Checkout\Tests\Instruments;

use Checkout\CheckoutApiException;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\InstrumentType;
use Checkout\Common\Phone;
use Checkout\Instruments\CreateInstrumentRequest;
use Checkout\Instruments\InstrumentAccountHolder;
use Checkout\Instruments\InstrumentCustomerRequest;
use Checkout\Instruments\UpdateInstrumentRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Tokens\CardTokenRequest;

class InstrumentsIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     */
    public function shouldCreateAndGetInstrument()
    {

        $instrument = $this->createInstrument();
        $this->assertResponse(
            $instrument,
            "id",
            "type",
            "expiry_month",
            "expiry_year",
            "scheme",
            "last4",
            "bin",
            //"card_type",
            //"card_category",
            //"issuer",
            //"issuer_country",
            //"product_id",
            //"product_type",
            "fingerprint",
            "customer.id",
            "customer.email",
            "customer.name"
        );

        // get
        $this->assertResponse(
            $this->defaultApi->getInstrumentsClient()->get($instrument["id"]),
            "id",
            "type",
            "expiry_month",
            "expiry_year",
            //"scheme",
            "last4",
            "bin",
            //"card_type",
            //"card_category",
            //"issuer",
            //"issuer_country",
            //"product_id",
            //"product_type",
            "fingerprint",
            "customer.id",
            "customer.email",
            "customer.name"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateAndDeleteInstrument()
    {

        $instrument = $this->createInstrument();

        $updateInstrumentRequest = new UpdateInstrumentRequest();
        $updateInstrumentRequest->name = "testing";

        // update
        $updateResponse = $this->defaultApi->getInstrumentsClient()->update($instrument["id"], $updateInstrumentRequest);
        $this->assertResponse(
            $updateResponse,
            "type",
            "fingerprint",
            "http_metadata"
        );
        self::assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());

        // delete
        $deleteResponse = $this->defaultApi->getInstrumentsClient()->delete($instrument["id"]);
        self::assertArrayHasKey("http_metadata", $deleteResponse);
        self::assertEquals(204, $deleteResponse["http_metadata"]->getStatusCode());
        try {
            $this->defaultApi->getInstrumentsClient()->delete($instrument["id"]);
            $this->fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            $this->assertEquals(self::MESSAGE_404, $e->getMessage());
        }
    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    private function createInstrument()
    {
        $address = new Address();
        $address->address_line1 = "123 Street";
        $address->address_line2 = "Hollywood Avenue";
        $address->city = "Los Angeles";
        $address->state = "CA";
        $address->zip = "91001";
        $address->country = Country::$US;

        $phone = new Phone();
        $phone->country_code = "1";
        $phone->number = "999555222";

        $instrumentAccountHolder = new InstrumentAccountHolder();
        $instrumentAccountHolder->billing_address = $address;
        $instrumentAccountHolder->phone = $phone;

        $customer = new InstrumentCustomerRequest();
        $customer->email = "instrumentcustomer@checkout.com";
        $customer->name = "Instrument Customer";
        $customer->phone = $phone;
        $customer->default = true;

        $token = $this->createToken()["token"];

        $createInstrumentRequest = new CreateInstrumentRequest();
        $createInstrumentRequest->type = InstrumentType::$token;
        $createInstrumentRequest->token = $token;
        $createInstrumentRequest->account_holder = $instrumentAccountHolder;
        $createInstrumentRequest->customer = $customer;

        return $this->defaultApi->getInstrumentsClient()->create($createInstrumentRequest);
    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    private function createToken()
    {
        $phone = new Phone();
        $phone->country_code = "44";
        $phone->number = "020 222333";

        $cardTokenRequest = new CardTokenRequest();
        $cardTokenRequest->name = "Mr. Test";
        $cardTokenRequest->number = "4242424242424242";
        $cardTokenRequest->expiry_year = 2025;
        $cardTokenRequest->expiry_month = 6;
        $cardTokenRequest->cvv = "100";
        $cardTokenRequest->billing_address = $this->getAddress();
        $cardTokenRequest->phone = $phone;

        return $this->defaultApi->getTokensClient()->requestCardToken($cardTokenRequest);
    }
}
