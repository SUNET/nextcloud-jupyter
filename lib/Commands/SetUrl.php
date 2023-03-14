<?php
// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Jupyter\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \OCP\IConfig;
use \OCA\Jupyter\Service\JupyterService;


class SetUrl extends Command
{

  private $config;
  private $appName;
  private $jupyterService;

  public function __construct($AppName, IConfig $config, JupyterService $jupyterService)
  {
    parent::__construct();
    $this->appName = $AppName;
    $this->config = $config;
    $this->jupyterService = $jupyterService;
  }


  protected function configure()
  {
    $this
      ->setName('jupyter:set-url')
      ->setDescription('Sets the iframe url within Jupyter app.')
      ->addArgument(
        'url',
        InputArgument::REQUIRED,
        'The url for JupyterHub - will be shown as iframe inside of Jupyter app.'
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
    $this->config->setAppValue($this->appName, $this->jupyterService->getUrlService()->getCloudUrlKey(), $url);
    $output->writeln("Set <$url> successfully.");
  }
}
