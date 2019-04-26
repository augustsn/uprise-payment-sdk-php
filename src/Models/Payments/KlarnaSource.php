<?php

/**
 * Checkout.com
 * Authorised and regulated as an electronic money institution
 * by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * PHP version 7
 *
 * @category  SDK
 * @package   Checkout.com
 * @author    Platforms Development Team <platforms@checkout.com>
 * @copyright 2010-2019 Checkout.com
 * @license   https://opensource.org/licenses/mit-license.html MIT License
 * @link      https://docs.checkout.com/
 */

namespace Checkout\Models\Payments;

/**
 * Payment method Klarna.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class KlarnaSource extends IdSource
{

    /**
     * Qualified name of the class.
     *
     * @var string
     */
    const QUALIFIED_NAME = __CLASS__;

    /**
     * Qualified namespace of the class.
     *
     * @var string
     */
    const QUALIFIED_NAMESPACE = __NAMESPACE__;

    /**
     * Name of the model.
     *
     * @var string
     */
    const MODEL_NAME = 'klarna';


    /**
     * Magic Methods
     */

    /**
     * Initialise payment
     *
     * @param      string   $token     Klarna authentication token, obtained by the merchant during client transaction authorization.
     * @param      Address  $billing   The billing
     * @param      integer   $tax       The tax
     * @param      array    $products  The products
     */
    public function __construct($token, Address $billing, $tax, array $products)
    {
        $this->type = static::MODEL_NAME;
        $this->authorization_token = $token;
        $this->billing_address = $billing;
        $this->tax_amount = $tax;
        $this->products = $products;
    }
}
