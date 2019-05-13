<?php

declare(strict_types=1);

namespace Shopsys\Releaser\ReleaseWorker\AfterRelease;

use Nette\Utils\FileSystem;
use PharIo\Version\Version;
use Shopsys\Releaser\FileManipulator\FrameworkVersionFileManipulator;
use Shopsys\Releaser\ReleaseWorker\AbstractShopsysReleaseWorker;
use Shopsys\Releaser\Stage;
use Symplify\PackageBuilder\FileSystem\SmartFileInfo;

final class SetDevFrameworkKernelVersionReleaseWorker extends AbstractShopsysReleaseWorker
{
    /**
     * @var \Shopsys\Releaser\FileManipulator\FrameworkVersionFileManipulator
     */
    private $frameworkVersionFileManipulator;

    /**
     * @param \Shopsys\Releaser\FileManipulator\FrameworkVersionFileManipulator $frameworkVersionFileManipulator
     */
    public function __construct(FrameworkVersionFileManipulator $frameworkVersionFileManipulator)
    {
        $this->frameworkVersionFileManipulator = $frameworkVersionFileManipulator;
    }

    /**
     * @param \PharIo\Version\Version $version
     * @return string
     */
    public function getDescription(Version $version): string
    {
        $developmentVersion = $this->getDevelopmentVersion($version);
        return sprintf('Set Shopsys Framework kernel version to "%s"', $developmentVersion->getVersionString());
    }

    /**
     * Higher first
     * @return int
     */
    public function getPriority(): int
    {
        return 170;
    }

    /**
     * @param \PharIo\Version\Version $version
     */
    public function work(Version $version): void
    {
        $developmentVersion = $this->getDevelopmentVersion($version);
        $this->updateFrameworkKernelVersion($developmentVersion);
        $this->commit(sprintf(
            'shopsys kernel version is now updated to version "%s"',
            $developmentVersion->getVersionString()
        ));

        $this->symfonyStyle->note('You need to push the master branch manually, however, you have to wait until the previous (tagged) master build is finished on Heimdall. Otherwise, master-project-base would have never been built from the source codes where there are dependencies on the tagged versions of shopsys packages.');
        $this->confirm('Confirm you have waited long enough and then pushed the master branch.');
    }

    /**
     * @return string
     */
    public function getStage(): string
    {
        return Stage::AFTER_RELEASE;
    }

    /**
     * @param \PharIo\Version\Version $version
     */
    private function updateFrameworkKernelVersion(Version $version): void
    {
        $upgradeFilePath = getcwd() . FrameworkVersionFileManipulator::FRAMEWORK_VERSION_FILE_PATH;
        $upgradeFileInfo = new SmartFileInfo($upgradeFilePath);

        $newUpgradeContent = $this->frameworkVersionFileManipulator->updateFrameworkKernelVersion($upgradeFileInfo, $version);

        FileSystem::write($upgradeFilePath, $newUpgradeContent);
    }

    /**
     * Return new development version (e.g. from 7.1.0 to 7.2.0-dev)
     * @param \PharIo\Version\Version $version
     * @return \PharIo\Version\Version
     */
    private function getDevelopmentVersion(Version $version): Version
    {
        $newVersionString = $version->getMajor()->getValue() . '.' . ($version->getMinor()->getValue() + 1) . '.0-dev';
        return new Version($newVersionString);
    }
}
