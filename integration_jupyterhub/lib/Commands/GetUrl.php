<?php
// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Jupyter\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \OCP\IConfig;


class GetUrl extends Command
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
      ->setName('integration_jupyterhub:get-url')
      ->setDescription('Gets the iframe url from the database.');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int
   * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $url = $this->config->getAppValue($this->appName, 'jupyter_url');
    $output->writeln($url);
    return 0;
  }
}
