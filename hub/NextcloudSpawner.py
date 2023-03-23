from kubespawner.spawner import KubeSpawner

class NextcloudSpawner(KubeSpawner):

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)


c.JupyterHub.spawner_class = NextcloudSpawner
