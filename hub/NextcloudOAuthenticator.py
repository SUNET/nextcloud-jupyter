import os
import time
import requests
from datetime import datetime
from oauthenticator.generic import GenericOAuthenticator

token_url = 'https://' + os.environ[
    'NEXTCLOUD_HOST'] + '/index.php/apps/oauth2/api/v1/token'
debug = os.environ.get('NEXTCLOUD_DEBUG_OAUTH',
                       'false').lower() in ['true', '1', 'yes']


def get_nextcloud_access_token(refresh_token):
    client_id = os.environ['NEXTCLOUD_CLIENT_ID']
    client_secret = os.environ['NEXTCLOUD_CLIENT_SECRET']

    code = refresh_token
    data = {
        'grant_type': 'refresh_token',
        'code': code,
        'refresh_token': refresh_token,
        'client_id': client_id,
        'client_secret': client_secret
    }
    response = requests.post(token_url, data=data)
    if debug:
        print(response.text)
    return response.json()


def post_auth_hook(authenticator, handler, authentication):
    # user = authentication['auth_state']['oauth_user']['ocs']['data']['id']
    auth_state = authentication['auth_state']
    auth_state['token_expires'] = time.time(
    ) + auth_state['token_response']['expires_in']
    authentication['auth_state'] = auth_state
    return authentication


class NextcloudOAuthenticator(GenericOAuthenticator):

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.user_dict = {}

    async def pre_spawn_start(self, user, spawner):
        super().pre_spawn_start(user, spawner)
        auth_state = await user.get_auth_state()
        if not auth_state:
            return
        access_token = auth_state['access_token']
        spawner.environment['NEXTCLOUD_ACCESS_TOKEN'] = access_token

    async def refresh_user(self, user, handler=None):
        auth_state = await user.get_auth_state()
        if not auth_state:
            if debug:
                print(f'auth_state missing for {user}')
            return False
        access_token = auth_state['access_token']
        refresh_token = auth_state['refresh_token']
        token_response = auth_state['token_response']
        now = time.time()
        now_hr = datetime.fromtimestamp(now)
        expires = auth_state['token_expires']
        expires_hr = datetime.fromtimestamp(expires)
        expires = 0
        if debug:
            print(f'auth_state for {user}: {auth_state}')
        if now >= expires:
            if debug:
                print(f'Time is: {now_hr}, token expired: {expires_hr}')
                print(f'Refreshing token for {user}')
            try:
                token_response = get_nextcloud_access_token(refresh_token)
                auth_state['access_token'] = token_response['access_token']
                auth_state['refresh_token'] = token_response['refresh_token']
                auth_state[
                    'token_expires'] = now + token_response['expires_in']
                auth_state['token_response'] = token_response
                if debug:
                    print(f'Successfully refreshed token for {user.name}')
                    print(f'auth_state for {user.name}: {auth_state}')
                return {'name': user.name, 'auth_state': auth_state}
            except Exception as e:
                if debug:
                    print(f'Failed to refresh token for {user}')
                return False
        if debug:
            print(f'Time is: {now_hr}, token expires: {expires_hr}')
        return True


c.JupyterHub.authenticator_class = NextcloudOAuthenticator
c.NextcloudOAuthenticator.client_id = os.environ['NEXTCLOUD_CLIENT_ID']
c.NextcloudOAuthenticator.client_secret = os.environ['NEXTCLOUD_CLIENT_SECRET']
c.NextcloudOAuthenticator.login_service = 'Sunet Drive'
c.NextcloudOAuthenticator.username_claim = lambda r: r.get('ocs', {}).get(
    'data', {}).get('id')
c.NextcloudOAuthenticator.userdata_url = 'https://' + os.environ[
    'NEXTCLOUD_HOST'] + '/ocs/v2.php/cloud/user?format=json'
c.NextcloudOAuthenticator.authorize_url = 'https://' + os.environ[
    'NEXTCLOUD_HOST'] + '/index.php/apps/oauth2/authorize'
c.NextcloudOAuthenticator.token_url = token_url
c.NextcloudOAuthenticator.oauth_callback_url = 'https://' + os.environ[
    'JUPYTER_HOST'] + '/hub/oauth_callback'
c.NextcloudOAuthenticator.allow_all = True
c.NextcloudOAuthenticator.refresh_pre_spawn = True
c.NextcloudOAuthenticator.enable_auth_state = True
c.NextcloudOAuthenticator.auth_refresh_age = 3600
c.NextcloudOAuthenticator.post_auth_hook = post_auth_hook
