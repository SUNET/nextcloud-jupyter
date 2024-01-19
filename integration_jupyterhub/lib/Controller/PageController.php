<?php

declare(strict_types=1);
// SPDX-FileCopyrightText: Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Jupyter\Controller;

use OCA\Jupyter\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IRequest;
use OCP\IUserSession;

class PageController extends Controller
{
  protected $appName;
  private $jupyter_url;
  /** 
   * @var IConfig
   */
  private $config;
  /** 
   * @var IUserSession
   */
  private $userSession;
  public function __construct(
    IRequest $request,
    IUserSession $userSession,
    IConfig      $config,
  ) {
    parent::__construct(Application::APP_ID, $request);
    $this->appName = "integration_jupyterhub";
    $this->userId = $userSession->getUser()->getUID();
    $this->jupyter_url = $config->getAppValue($this->appName, 'jupyter_url') . '/hub/home';
  }

  /**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
  public function index(): TemplateResponse
  {
    //Util::addScript(Application::APP_ID, 'jupyter-main');
    $policy = new \OCP\AppFramework\Http\EmptyContentSecurityPolicy();

    $parsed_url = parse_url($this->jupyter_url);

    $http = $parsed_url["scheme"] . "://" . $parsed_url["host"];
    $policy->addAllowedConnectDomain($http);
    $policy->addAllowedScriptDomain($http);
    $policy->addAllowedFrameDomain($http);
    \OC::$server->getContentSecurityPolicyManager()->addDefaultPolicy($policy);


    $params = [
      'user_id' => $this->userId,
      'jupyter_url' => $this->jupyter_url,
      'short_url' => $http,
    ];

    return new TemplateResponse(Application::APP_ID, "main", $params);
  }
}
