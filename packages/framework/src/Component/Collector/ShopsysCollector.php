<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Component\Collector;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\ShopsysFrameworkBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

final class ShopsysCollector extends DataCollector
{
    /**
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     */
    public function __construct(
        Domain $domain
    ) {
        $this->data = [
            'version' => ShopsysFrameworkBundle::VERSION,
            'domains' => $domain->getAll(),
            'currentDomainId' => $domain->getId(),
            'currentDomainName' => $domain->getName(),
            'currentDomainLocale' => $domain->getLocale(),
        ];
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->data['version'];
    }

    /**
     * @return \Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig[]
     */
    public function getDomains(): array
    {
        return $this->data['domains'];
    }

    /**
     * @return int
     */
    public function getCurrentDomainId(): int
    {
        return $this->data['currentDomainId'];
    }

    /**
     * @return string
     */
    public function getCurrentDomainName(): string
    {
        return $this->data['currentDomainName'];
    }

    /**
     * @return string
     */
    public function getCurrentDomainLocale(): string
    {
        return $this->data['currentDomainLocale'];
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function reset(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'shopsys_core';
    }
}
