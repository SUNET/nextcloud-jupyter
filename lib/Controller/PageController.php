<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Jupyter\Controller;

use OCA\Jupyter\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\IUserSession;

class PageController extends Controller {
	private $userId;
	public function __construct(IRequest $request, $userId, IUserSession $userSession) {
		parent::__construct(Application::APP_ID, $request);
		$this->userId = $userId;
		$this->userSession = $userSession;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index(): TemplateResponse {
		//Util::addScript(Application::APP_ID, 'jupyter-main');
		$policy = new \OCP\AppFramework\Http\EmptyContentSecurityPolicy();

		$userId = $this->userSession->getUser()->getUID();
		$url = 'https://jupyter.drive.test.sunet.se/user/' .$userId . '/lab';
		$parsed_url = parse_url($url);

		$http = $parsed_url["scheme"] . "://" . $parsed_url["host"];
		$policy->addAllowedConnectDomain($http);
		$policy->addAllowedScriptDomain($http);
		$policy->addAllowedFrameDomain($http);
		\OC::$server->getContentSecurityPolicyManager()->addDefaultPolicy($policy);


		$params = [
		    'user_id' => $userId,
		    'jupyter_url' => $url,
		];

		return new TemplateResponse(Application::APP_ID, "main", $params);


	}
}
