# Prestashop with Docker

Simple car parts store to symulate Docker features

## images/versions:

* apache `2.4`
* php `5.6`
* mysql `5.7`

* prestashop `1.6.1.17`

## access:

__apache__

- h: 0.0.0.0:8888

__mysql__

- h: 0.0.0.0:4444 (or db)
- u: root
- p: prestashop

__presta__
- u: prestashop@prestashop.prestashop
- p: prestashop

## files structure:

- [docker-compose.yml](docker-compose.yml)

- [dockerfile](Dockerfile)

- [prestashop dir: /www](/www)

## helpful commands:

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

## links:

http://github.com/mackankowski/prestashop/

https://hub.docker.com/r/mackankowski/prestashop/

https://hub.docker.com/_/php/

https://hub.docker.com/_/mysql/

https://docs.docker.com/compose/

https://docs.docker.com/docker-hub/builds/
