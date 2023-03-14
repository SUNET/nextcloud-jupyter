<?php
// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Jupyter\Service;

use \OCP\IConfig;
use \OCP\IURLGenerator;
use OCA\Jupyter\Service\UrlService;

class JupyterService
{
    protected $urlService;

    public function __construct($AppName, UrlService $urlService, IConfig $config, IURLGenerator $urlGenerator)
    {
        $this->urlService = $urlService;
        $this->appName = $AppName;
        $this->urlGenerator = $urlGenerator;
    }

    public function getUrlService()
    {
        return $this->urlService;
    }

}

