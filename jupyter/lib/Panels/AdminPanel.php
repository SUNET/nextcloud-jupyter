<?php
// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Jupyter\Panels;

use \OCA\OAuth2\Db\ClientMapper;
use OCP\IUserSession;
use OCP\IConfig;
use OCP\Settings\ISettings;
use OCP\AppFramework\Http\TemplateResponse;

class AdminPanel implements ISettings
{
  private $appName;
  /**
   * @var IUserSession
   */
  private $userSession;
  
  /** 
   * @var IConfig
   */
  private $config;

  public function __construct(
    ClientMapper $clientMapper,
    IUserSession $userSession,
    IConfig      $config,
  ) {
    $this->appName = "jupyter";
    $this->userSession = $userSession;
    $this->config = $config;
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
      'user_id' => $userId,
      "jupyter_url" => $this->config->getAppValue($this->appName, 'jupyter_url'),
    ];
    $t = new TemplateResponse($this->appName, 'settings-admin', $params);
    return $t;
  }

  public function getPriority()
  {
    return 20;
  }
}
