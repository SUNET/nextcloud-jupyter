// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later
$(document).ready(function() {
  $('#jupyter_submit').on('click', function(event) {
    var app = "jupyter"
    event.preventDefault();
    OC.msg.startSaving('#jupyterSettings .msg');

    var url = $("#jupyter_url");
    var urlValue = url.val();
    if (urlValue.endsWith("/")) {
      urlValue = urlValue.slice(0, -1);
      url.val(urlValue);
    }
    OC.AppConfig.setValue(app, url.attr('name'), url.val());
    OC.msg.finishedSaving('#jupyterSettings .msg', { status: 'success', data: { message: t('jupyter', 'Saved.') } });
  });
});

