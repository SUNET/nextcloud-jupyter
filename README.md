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
    JupyterHub:
      tornado_settings:
        headers: { 'Content-Security-Policy': "frame-ancestors *;" }
      allow_origin: '*'
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

## Building the app

The app can be built by using the provided Makefile by running:

    make

This requires the following things to be present:
* make
* which
* tar: for building the archive
* curl: used if phpunit and composer are not installed to fetch them from the web
* npm: for building and testing everything JS, only required if a package.json is placed inside the **js/** folder

The make command will install or update Composer dependencies if a composer.json is present and also **npm run build** if a package.json is present in the **js/** folder. The npm **build** script should use local paths for build systems and package managers, so people that simply want to build the app won't need to install npm libraries globally, e.g.:

**package.json**:
```json
"scripts": {
    "test": "node node_modules/gulp-cli/bin/gulp.js karma",
    "prebuild": "npm install && node_modules/bower/bin/bower install && node_modules/bower/bin/bower update",
    "build": "node node_modules/gulp-cli/bin/gulp.js"
}
```


## Publish to App Store

First get an account for the [App Store](http://apps.nextcloud.com/) then run:

    make && make appstore

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
