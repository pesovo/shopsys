<?php

namespace Shopsys\ShopBundle\Tests\Database\Model\Payment;

use Shopsys\ShopBundle\Model\Payment\Payment;
use Shopsys\ShopBundle\Model\Payment\PaymentData;
use Shopsys\ShopBundle\Model\Pricing\Vat\Vat;
use Shopsys\ShopBundle\Model\Pricing\Vat\VatData;
use Shopsys\ShopBundle\Model\Transport\Transport;
use Shopsys\ShopBundle\Model\Transport\TransportData;
use Shopsys\ShopBundle\Model\Transport\TransportFacade;
use Shopsys\ShopBundle\Tests\Test\DatabaseTestCase;

class PaymentTest extends DatabaseTestCase
{
    public function testRemoveTransportFromPaymentAfterDelete()
    {
        $em = $this->getEntityManager();

        $vat = new Vat(new VatData('vat', 21));
        $transport = new Transport(new TransportData([], $vat, [], [], false));
        $payment = new Payment(new PaymentData(['cs' => 'name'], $vat, [], [], false));
        $payment->addTransport($transport);

        $em->persist($vat);
        $em->persist($transport);
        $em->persist($payment);
        $em->flush();

        $transportFacade = $this->getContainer()->get(TransportFacade::class);
        /* @var $transportFacade \Shopsys\ShopBundle\Model\Transport\TransportFacade */
        $transportFacade->deleteById($transport->getId());

        $this->assertFalse($payment->getTransports()->contains($transport));
    }
}
