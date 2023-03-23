from oauthenticator.generic import GenericOAuthenticator
import os


def post_auth_hook(authenticator, handler, authentication):
    user = authentication['auth_state']['oauth_user']['ocs']['data']['id']
    auth_state = authentication['auth_state']
    authenticator.user_dict[user] = auth_state
    return authentication


class NextcloudOAuthenticator(GenericOAuthenticator):

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.user_dict = {}

    def pre_spawn_start(self, user, spawner):
        super().pre_spawn_start(user, spawner)
        access_token = self.user_dict[user.name]['access_token']
        #refresh_token = self.user_dict[user.name]['refresh_token']
        spawner.environment['NEXTCLOUD_ACCESS_TOKEN'] = access_token


c.JupyterHub.authenticator_class = NextcloudOAuthenticator
c.NextcloudOAuthenticator.client_id = os.environ['NEXTCLOUD_CLIENT_ID']
c.NextcloudOAuthenticator.client_secret = os.environ['NEXTCLOUD_CLIENT_SECRET']
c.NextcloudOAuthenticator.login_service = 'Sunet Drive'
c.NextcloudOAuthenticator.username_key = lambda r: r.get('ocs', {}).get(
    'data', {}).get('id')
c.NextcloudOAuthenticator.userdata_url = 'https://' + os.environ['NEXTCLOUD_HOST'] + '/ocs/v2.php/cloud/user?format=json'
c.NextcloudOAuthenticator.authorize_url = 'https://' + os.environ['NEXTCLOUD_HOST'] + '/index.php/apps/oauth2/authorize'
c.NextcloudOAuthenticator.token_url = 'https://' + os.environ['NEXTCLOUD_HOST'] + '/index.php/apps/oauth2/api/v1/token'
c.NextcloudOAuthenticator.oauth_callback_url = 'https://' + os.environ['JUPYTER_HOST'] + '/hub/oauth_callback'
c.NextcloudOAuthenticator.refresh_pre_spawn = True
c.NextcloudOAuthenticator.enable_auth_state = True
c.NextcloudOAuthenticator.post_auth_hook = post_auth_hook
