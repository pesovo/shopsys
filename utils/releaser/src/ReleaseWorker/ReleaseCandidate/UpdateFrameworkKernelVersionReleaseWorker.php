<?php

declare(strict_types=1);

namespace Shopsys\Releaser\ReleaseWorker\ReleaseCandidate;

use Nette\Utils\FileSystem;
use PharIo\Version\Version;
use Shopsys\Releaser\FileManipulator\FrameworkVersionFileManipulator;
use Shopsys\Releaser\ReleaseWorker\AbstractShopsysReleaseWorker;
use Shopsys\Releaser\Stage;
use Symplify\MonorepoBuilder\Release\Message;
use Symplify\PackageBuilder\FileSystem\SmartFileInfo;

final class UpdateFrameworkKernelVersionReleaseWorker extends AbstractShopsysReleaseWorker
{
    /**
     * @var \Shopsys\Releaser\FileManipulator\FrameworkVersionFileManipulator
     */
    private $frameworkVersionFileManipulator;

    /**
     * @param \Shopsys\Releaser\FileManipulator\FrameworkVersionFileManipulator $frameworkVersionFileManipulator
     */
    public function __construct(
        FrameworkVersionFileManipulator $frameworkVersionFileManipulator
    ) {
        $this->frameworkVersionFileManipulator = $frameworkVersionFileManipulator;
    }

    /**
     * @param \PharIo\Version\Version $version
     * @return string
     */
    public function getDescription(Version $version): string
    {
        return 'Update Shopsys Framework kernel version and commit it.';
    }

    /**
     * Higher first
     * @return int
     */
    public function getPriority(): int
    {
        return 845;
    }

    /**
     * @param \PharIo\Version\Version $version
     */
    public function work(Version $version): void
    {
        $this->updateFrameworkKernelVersion($version);

        $this->commit(sprintf(
            'shopsys kernel version is now updated to version "%s"',
            $version->getVersionString()
        ));

        $this->symfonyStyle->success(Message::SUCCESS);
    }

    /**
     * @return string
     */
    public function getStage(): string
    {
        return Stage::RELEASE_CANDIDATE;
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
}
