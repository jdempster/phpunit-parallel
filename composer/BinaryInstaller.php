<?php

namespace PhpunitParallel\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Util\HttpDownloader;

class BinaryInstaller
{
    private const GITHUB_REPO = 'alexdempster44/phpunit-parallel';

    /** @var Composer */
    private $composer;

    /** @var IOInterface */
    private $io;

    public function __construct(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function install(string $version)
    {
        $os = $this->detectOs();
        $arch = $this->detectArch();

        if ($os === null) {
            $this->io->writeError('<warning>phpunit-parallel: unsupported OS, skipping binary download</warning>');
            return;
        }
        if ($arch === null) {
            $this->io->writeError('<warning>phpunit-parallel: unsupported architecture, skipping binary download</warning>');
            return;
        }

        $cleanVersion = ltrim($version, 'v');
        $extension = $os === 'windows' ? 'zip' : 'tar.gz';
        $binaryName = $os === 'windows' ? 'phpunit-parallel.exe' : 'phpunit-parallel';

        $url = sprintf(
            'https://github.com/%s/releases/download/v%s/phpunit-parallel_%s_%s_%s.%s',
            self::GITHUB_REPO,
            $cleanVersion,
            $cleanVersion,
            $os,
            $arch,
            $extension
        );

        $this->io->write(sprintf(
            '<info>phpunit-parallel:</info> Downloading binary for %s/%s (v%s)...',
            $os,
            $arch,
            $cleanVersion
        ));

        $targetDir = __DIR__ . '/.binary';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $targetPath = $targetDir . '/' . $binaryName;

        $tempFile = $targetDir . '/archive.' . $extension;

        try {
            $this->downloadFile($url, $tempFile);
            $this->extractBinary($tempFile, $targetDir, $binaryName, $extension);

            if ($os !== 'windows') {
                chmod($targetPath, 0755);
            }

            $this->io->write('<info>phpunit-parallel:</info> Binary installed successfully');
        } catch (\Exception $e) {
            $this->io->writeError(sprintf(
                '<error>phpunit-parallel: Failed to install binary: %s</error>',
                $e->getMessage()
            ));
        } finally {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }

    /**
     * @return string|null
     */
    private function detectOs()
    {
        if (PHP_OS_FAMILY === 'Linux') {
            return 'linux';
        }
        if (PHP_OS_FAMILY === 'Darwin') {
            return 'darwin';
        }
        if (PHP_OS_FAMILY === 'Windows') {
            return 'windows';
        }
        return null;
    }

    /**
     * @return string|null
     */
    private function detectArch()
    {
        $machine = php_uname('m');

        if ($machine === 'x86_64' || $machine === 'amd64') {
            return 'amd64';
        }
        if ($machine === 'aarch64' || $machine === 'arm64') {
            return 'arm64';
        }
        return null;
    }

    private function downloadFile(string $url, string $destination)
    {
        $httpDownloader = \Composer\Factory::createHttpDownloader($this->io, $this->composer->getConfig());
        $response = $httpDownloader->get($url);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(sprintf(
                'Failed to download %s (HTTP %d)',
                $url,
                $response->getStatusCode()
            ));
        }

        file_put_contents($destination, $response->getBody());
    }

    private function extractBinary(string $archivePath, string $targetDir, string $binaryName, string $extension)
    {
        $targetPath = $targetDir . '/' . $binaryName;

        if ($extension === 'zip') {
            $this->extractFromZip($archivePath, $targetPath, $binaryName);
        } else {
            $this->extractFromTarGz($archivePath, $targetPath, $binaryName);
        }

        if (!file_exists($targetPath)) {
            throw new \RuntimeException('Binary not found in archive after extraction');
        }
    }

    private function extractFromTarGz(string $archivePath, string $targetPath, string $binaryName)
    {
        $phar = new \PharData($archivePath);
        $found = false;

        foreach ($phar as $file) {
            if (basename($file) === $binaryName) {
                copy($file->getPathname(), $targetPath);
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new \RuntimeException(sprintf('Binary "%s" not found in tar.gz archive', $binaryName));
        }
    }

    private function extractFromZip(string $archivePath, string $targetPath, string $binaryName)
    {
        $zip = new \ZipArchive();
        $result = $zip->open($archivePath);

        if ($result !== true) {
            throw new \RuntimeException(sprintf('Failed to open zip archive (error code: %d)', $result));
        }

        $found = false;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (basename($name) === $binaryName) {
                $content = $zip->getFromIndex($i);
                file_put_contents($targetPath, $content);
                $found = true;
                break;
            }
        }

        $zip->close();

        if (!$found) {
            throw new \RuntimeException(sprintf('Binary "%s" not found in zip archive', $binaryName));
        }
    }
}
