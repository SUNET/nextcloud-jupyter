<?php
// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Jupyter\Panels;

use \OCA\OAuth2\Db\ClientMapper;
use OCP\IURLGenerator;
use OCP\IUserSession;
use OCP\Settings\ISettings;
use OCP\AppFramework\Http\TemplateResponse;
use \OCA\Jupyter\Service\UrlService;
use \OCA\Jupyter\Service\JupyterService;

class AdminPanel implements ISettings
{
  private $appName;
  /**
   * @var \OCA\OAuth2\Db\ClientMapper
   */
  private $clientMapper;
  /**
   * @var IUserSession
   */
  private $userSession;

  /**
   * @var IURLGenerator
   */
  private $urlGenerator;

  /**
   * @var UrlService
   */
  private $urlService;

  private $jupyterService;


  public function __construct(
    ClientMapper $clientMapper,
    IUserSession $userSession,
    JupyterService $jupyterService
  ) {
    $this->appName = "jupyter";
    $this->clientMapper = $clientMapper;
    $this->userSession = $userSession;
    $this->urlGenerator = \OC::$server->getURLGenerator();
    $this->jupyterService = $jupyterService;
    $this->urlService = $jupyterService->getUrlService();
  }

  public function getSection()
  {
    return 'additional';
  }

  /**
   * @return TemplateResponse
   */
  public function getForm()
  {
    $userId = $this->userSession->getUser()->getUID();
    $params = [
      'clients' => $this->clientMapper->getClients(),
      'user_id' => $userId,
      'urlGenerator' => $this->urlGenerator,
      "jupyterURL" => $this->urlService->getURL(),
    ];
    $t = new TemplateResponse($this->appName, 'settings-admin', $params);
    return $t;
  }

  public function getPriority()
  {
    return 20;
  }
}
