<?php
// SPDX-FileCopyrightText: Enrique Pérez Arnaud <eperez@emergya.com>, Mikael Nordin <kano@sunet.se>
// SPDX-License-Identifier: AGPL-3.0-or-later

/** @var \OCP\IL10N $l */
/** @var array $_ */
script('jupyter', 'settings-admin');
?>

<div id="jupyterSettings" class="section">
  <h2 class="app-name has-documentation"><?php p($l->t('JupyterHub')); ?></h2>

  <a target="_blank" rel="noreferrer" class="icon-info" title="<?php p($l->t('Open documentation')); ?>" href="https://jupyter.org/hub"></a>

  <form id="jupyter-settings">
    <label for="jupyter_url">
      <?php p($l->t('Specify here the URL, where the Nextcloud instance can find your jupyter instance e.g. https://jupyter.example.com.')); ?>
    </label>
    <br />
    <input type="text" name="jupyter_url" id="jupyter_url" class="text" value="<?php print_unescaped( $_["jupyter_url"]); ?>" placeholder="url to jupyter instance" style="width: auto !important" />
    <br />
    <input id="jupyter_submit" type="button" class="button" value="<?php p($l->t('Save')); ?>">
    <span class="msg"></span>
  </form>
</div>
