# Prestashop with Docker

Simple Prestashop's car parts store to demonstrate using Apache server in Docker container.

## Get started

> To follow these steps Windows 10 Pro or Unix system is required

1. Install Docker with Docker Compose
2. In project directory run command: `docker-compose up` (add `-d` param to background running)
3. Set permissions for **fix.sh** script: `chmod -x fix.sh`, then run: `./fix.sh` (use your db password)
4. App is ready to run from browser (check for url below)

## Access

**apache**

- h: localhost:8888 (or 0.0.0.0:8888)

**mysql**

- h: 0.0.0.0:4444 (or db)
- u: root
- p: prestashop

**prestashop**

- u: prestashop@prestashop.prestashop
- p: prestashop

## Specification

- apache `2.4`
- php `5.6`
- mysql `5.7`

- prestashop `1.6.1.17`

## Files structure

- [docker-compose.yml](docker-compose.yml)

- [dockerfile](Dockerfile)

- [prestashop dir: /www](/www)

## Commands

`docker run <image>`
`docker push <image>`
`docker-compose build && docker-compose up -d`
`docker-compose down`
`docker ps`
`docker images`

`docker stop $(docker ps -a -q)`
`docker rm $(docker ps -a -q)`
`docker rmi $(docker images -q) -f`

`git init`
`git status`
`git add .`
`git commit -m "<message>"`
`git push -u origin master`

## Links:

http://github.com/mackankowski/prestashop/

https://hub.docker.com/r/mackankowski/prestashop/

https://hub.docker.com/_/php/

https://hub.docker.com/_/mysql/

https://docs.docker.com/compose/

https://docs.docker.com/docker-hub/builds/
