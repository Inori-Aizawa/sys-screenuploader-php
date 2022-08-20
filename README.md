# sys-screenuploader-php
PHP Version from https://github.com/bakatrouble/sys-screenuploader-servers

This will recieve images or videos from a nintendo switch that has https://github.com/bakatrouble/sys-screenuploader installed

## Install sys-screenuploader to your Nintendo Switch

https://github.com/bakatrouble/sys-screenuploader

check release page

set **url** at to your server, below URL ignored

https://github.com/bakatrouble/sys-screenuploader/blob/4354095be7ff53e0298f6e8e448e5a960daf53e2/config.ini#L10

## edit the network in the docker-compose.yml
I use this behind a proxy. But you can also remove it and just open a port on your local network. if you want to use it outside your local network you can port forward in your router.
