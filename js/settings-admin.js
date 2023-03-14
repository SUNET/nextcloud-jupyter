// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later
$(document).ready(function () {

	$('#jupyter_submit').on('click', function (event) {
		event.preventDefault();
		OC.msg.startSaving('#jupyterSettings .msg');

		var app = "jupyter"
    let searchParams = new URLSearchParams(window.location.search);
    if (searchParams.has('jupyter_url')) {
      let urlValue = searchParams.get('jupyter_url')
      if (urlValue.endsWith("/")) {
        urlValue = urlValue.slice(0, -1);
      }
    }
		OC.AppConfig.setValue(app, 'jupyterURL', urlValue);

		OC.msg.finishedSaving('#jupyterSettings .msg', { status: 'success', data: { message: t('jupyter', 'Saved.') } });
	});

	//$('.section .icon-info').tipsy({ gravity: 'w' });
});

