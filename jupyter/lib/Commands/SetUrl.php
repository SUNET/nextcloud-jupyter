<?php
// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Jupyter\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \OCP\IConfig;


class SetUrl extends Command
{

  private $config;
  protected $appName;

  public function __construct($AppName, IConfig $config)
  {
    parent::__construct();
    $this->appName = $AppName;
    $this->config = $config;
  }


  protected function configure()
  {
    $this
      ->setName('jupyter:set-url')
      ->setDescription('Sets the iframe url within the Jupyter app.')
      ->addArgument(
        'url',
        InputArgument::REQUIRED,
        'The url for the JupyterHub installation - will be shown as iframe inside of the Jupyter app.'
      );
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|void
   * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $url = $input->getArgument('url');
    $this->config->setAppValue($this->appName, 'jupyter_url', $url);
    $output->writeln("Set <$url> as jupyter_url successful.");
  }
}
