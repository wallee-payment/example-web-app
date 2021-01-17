# Example App

This project shows a very simple web app integration. The app primarily consists of two files:

* `install.php` This file forwards the user to the screen on which the user has to grant permissions to the web app.
* `confirm.php` The confirm script checks that the incoming request is legit and confirms afterwards the installation with the code returned in the request.

## Docker

The project contains a `Dockerfile`. The file allows the execution of the example application within a docker container. To run you need first 
to build the container.

``
docker build -t example-web-app .
``

To run the container please use the command:

``
docker run -p 3000:80 -e APP_ENDPOINT=https://app-wallee.com -e APP_CLIENT_ID=5 -e APP_CLIENT_SECRET=7YdQ7vc0C2wQvbVyfsIZ3bub62ZJEjF6ACYpuTd0U94= example-web-app
``

Adapt the values for `APP_CLIENT_ID` and `APP_CLIENT_SECRET` according to the configuration of the web app.