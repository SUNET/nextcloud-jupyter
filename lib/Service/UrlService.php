<?php
// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Jupyter\Service;

use \OCP\IConfig;

class UrlService
{
    private $appName;
    private $cloudUrlKey;
    private $config;

    public function __construct($AppName, IConfig $config)
    {
        $this->config = $config;
        $this->appName = $AppName;
        $this->cloudUrlKey = "cloudURL";
    }

    public function getURL()
    {
        return $this->config->getAppValue($this->appName, $this->cloudUrlKey);
    }

    public function setURL($value)
    {
        $this->config->setAppValue($this->appName, $this->cloudUrlKey, $value);
        return $this->getURL();
    }

    public function getOverview()
    {
        return [
            $this->cloudUrlKey => $this->getURL()
        ];
    }

    public function getCloudUrlKey()
    {
        return $this->cloudUrlKey;
    }

    public function getPortURL()
    {
        return $this->getURL() . "/port-service";
    }

    public function getExporterURL()
    {
        return $this->getURL() . "/exporter";
    }

    public function getMetadataURL()
    {
        return $this->getURL() . "/metadata";
    }

    public function getResearchURL()
    {
        return $this->getURL() . "/research";
    }
}

