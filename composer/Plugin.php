<?php

namespace PhpunitParallel\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvents;
use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    /** @var Composer */
    private $composer;

    /** @var IOInterface */
    private $io;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        $binaryDir = __DIR__ . '/.binary';
        foreach (['phpunit-parallel', 'phpunit-parallel.exe'] as $name) {
            $path = $binaryDir . '/' . $name;
            if (file_exists($path)) {
                unlink($path);
            }
        }
        if (is_dir($binaryDir)) {
            rmdir($binaryDir);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'onPostPackageInstall',
            PackageEvents::POST_PACKAGE_UPDATE => 'onPostPackageUpdate',
        ];
    }

    public function onPostPackageInstall(PackageEvent $event)
    {
        $operation = $event->getOperation();
        if (!$operation instanceof InstallOperation) {
            return;
        }

        $package = $operation->getPackage();
        if ($package->getName() !== 'alexdempster44/phpunit-parallel') {
            return;
        }

        $installer = new BinaryInstaller($this->composer, $this->io);
        $installer->install($package->getPrettyVersion());
    }

    public function onPostPackageUpdate(PackageEvent $event)
    {
        $operation = $event->getOperation();
        if (!$operation instanceof UpdateOperation) {
            return;
        }

        $package = $operation->getTargetPackage();
        if ($package->getName() !== 'alexdempster44/phpunit-parallel') {
            return;
        }

        $installer = new BinaryInstaller($this->composer, $this->io);
        $installer->install($package->getPrettyVersion());
    }
}
