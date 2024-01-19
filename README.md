<!--
SPDX-FileCopyrightText: Mikael Nordin <kano@sunet.se>
SPDX-License-Identifier: CC0-1.0
-->

# Jupyter
Place this app in **nextcloud/custom_apps/**

## Configuring Nextcloud
Install this app and configure it with OCC:
```
occ integration_jupyterhub:set-url <URL to Your JupyterHub>
```
Or under Administration -> Additional settings

Create an OAuth2 Client under Administration -> Security and note Client identifier and secret for use later. 

## Configuring JupyterHub
You need to configure csp headers for JupyterHub before it can be embedded in Nextcloud.

That can be done in the values.yaml, if you deploy JupyterHub using Helm.

You should probably not use '*' as shown here, but instead specify your domain:

```
hub:
  config:
    Authenticator:
      auto_login: true
      enable_auth_state: true
    JupyterHub:
      tornado_settings:
        headers: { 'Content-Security-Policy': "frame-ancestors *;" }
  extraConfig:
    oauthCode: |
      from oauthenticator.generic import GenericOAuthenticator
      c.JupyterHub.authenticator_class = GenericOAuthenticator
      c.GenericOAuthenticator.client_id = '< Client Identity from Nextcloud goes here >'
   image:
    name: jupyter/scipy-notebook
    tag: 2023-02-28
  extraFiles:
    jupyter_notebook_config:
      mountPath: /home/jovyan/.jupyter/jupyter_server_config.py
      stringData: |
        c = get_config()
        c.NotebookApp.allow_origin = '*'
        c.NotebookApp.tornado_settings = {
            'headers': { 'Content-Security-Policy': "frame-ancestors *;" }
        }

      mode: 0644
```
A full configuration guide can be found here: https://wiki.sunet.se/pages/viewpage.action?pageId=178290700
