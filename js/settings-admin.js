// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later
$(document).ready(function() {
  var url =  OC.AppConfig.getValue(app, 'jupyterURL');
  if (url) {
    document.getElementById('jupyter_url').value = url;
  }
  $('#jupyter_submit').on('click', function(event) {
    event.preventDefault();
    OC.msg.startSaving('#jupyterSettings .msg');

    var app = "jupyter"
    var urlValue = document.getElementById('jupyter_url').value;
    OC.AppConfig.setValue(app, 'jupyterURL', urlValue);
    OC.msg.finishedSaving('#jupyterSettings .msg', { status: 'success', data: { message: t('jupyter', 'Saved.') } });
  });

  //$('.section .icon-info').tipsy({ gravity: 'w' });
});

