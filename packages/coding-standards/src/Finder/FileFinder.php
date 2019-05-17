<?php

declare(strict_types=1);

namespace Shopsys\CodingStandards\Finder;

use IteratorAggregate;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo as SymfonySplFileInfo;
use Symplify\EasyCodingStandard\Contract\Finder\CustomSourceProviderInterface;

final class FileFinder implements CustomSourceProviderInterface
{
    /**
     * @param string[] $source
     * @return \IteratorAggregate
     */
    public function find(array $source): IteratorAggregate
    {
        $directories = [];
        $files = [];
        foreach ($source as $singleSource) {
            if (is_file($singleSource)) {
                $fileInfo = new SplFileInfo($singleSource);
                $files[] = new SymfonySplFileInfo($singleSource, $fileInfo->getPath(), $fileInfo->getPathname());
            } else {
                $directories[] = $singleSource;
            }
        }

        $finder = Finder::create()->files()
            ->ignoreUnreadableDirs(true)
            ->notPath('app/')
            ->notPath('node_modules/')
            ->notPath('var/')
            ->notPath('vendor/')
            ->notPath('web/')
            ->name('#\.(twig|html(\.twig)?|php|md)$#')
            ->in($directories)
            ->append($files);

        return $finder;
    }
}
