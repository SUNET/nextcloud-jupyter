<!--
SPDX-FileCopyrightText: Mikael Nordin <kano@sunet.se>
SPDX-License-Identifier: CC0-1.0
-->

# Jupyter
Place this app in **nextcloud/apps/**

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
      c.GenericOAuthenticator.client_secret = '< Client secret from Nextclouid goes here >'
      c.GenericOAuthenticator.login_service = '< Your Service Name goes here >'
      c.GenericOAuthenticator.username_key = lambda r: r.get('ocs', {}).get('data', {}).get('id')
      c.GenericOAuthenticator.userdata_url = 'https://<Nextcloud domain goes here>/ocs/v2.php/cloud/user?format=json'
      c.GenericOAuthenticator.authorize_url = 'https://<Nextcloud domain goes here>/index.php/apps/oauth2/authorize'
      c.GenericOAuthenticator.token_url = 'https://<Nextcloud domain goes here>/index.php/apps/oauth2/api/v1/token'
      c.GenericOAuthenticator.oauth_callback_url = 'https://<Jupyter Hub domain goes here>/hub/oauth_callback'

singleuser:
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


## Publish to App Store

First get an account for the [App Store](http://apps.nextcloud.com/) then run:

    make appstore

The archive is located in build/artifacts/appstore and can then be uploaded to the App Store.

## Running tests
You can use the provided Makefile to run all tests by using:

    make test

This will run the PHP unit and integration tests and if a package.json is present in the **js/** folder will execute **npm run test**

Of course you can also install [PHPUnit](http://phpunit.de/getting-started.html) and use the configurations directly:

    phpunit -c phpunit.xml

or:

    phpunit -c phpunit.integration.xml

for integration tests
