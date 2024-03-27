<?php

namespace Frontpack\ComposerAssetsPlugin;

use Composer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshAssetsCommand extends Composer\Command\BaseCommand
{
    protected function configure()
    {
        $this->setName('refresh-assets');
        $this->setDescription('Refresh assets from vendor');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installer = new AssetsInstaller($this->requireComposer(), $this->getIO(), new Composer\Util\Filesystem);
        $installer->process();

        return 0;
    }
}