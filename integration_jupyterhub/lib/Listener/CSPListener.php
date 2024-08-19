<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Jupyter\Listener;

use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;
use Psr\Log\LoggerInterface;
use OCP\IConfig;

class CSPListener implements IEventListener
{
  protected string $appName;
  private string $jupyter_url;
  public function __construct(
    private IConfig $config,
    private LoggerInterface $logger
  ) {
    $this->jupyter_url = $config->getAppValue($this->appName, 'jupyter_url') . '/hub/home';
    $this->appName = "integration_jupyterhub";
  }

  public function handle(Event $event): void
  {
    $this->logger->debug('Adding CSP for Jupyter', ['app' => 'integration_jupyterhub']);
    if (!($event instanceof AddContentSecurityPolicyEvent)) {
      return;
    }
    $csp = new ContentSecurityPolicy();
    $url = parse_url($this->jupyter_url);
    $http = $url["scheme"] . "://" . $url["host"];
    $csp->addAllowedConnectDomain($http);
    $csp->addAllowedScriptDomain($http);
    $csp->addAllowedFrameDomain($http);

    $event->addPolicy($csp);
  }
}
