# Job Home Assignment - Verification Service

Description of the task is available here: https://github.com/sunfinancegroup/docs-home-task-hh

Steps to get the system up:

1. after cloning a repository to your machine
2. execute command:
```
make dev up && docker logs verification-service-dev -f
```
it will download and build docker image, install vendors, create database, load migrations and fixtures.
after you see from the console, that everything is finished, you can try out the functionality.

There is another container built with a role `consumer`, it establishes asynchronous processing
of the commands and events by consuming them from RabbitMq. In order to see its logs, just type:
```
docker logs verification-service-consumer -f
```

In order to turn everything off, just press:
```
make dev down
```

## Code-quality

repository contains three code quality tools:

1. CS-Fixer, run by command
```
make dev cs-fixer
```
2. PHP-Stan, run by
```
make dev phpstan
```
3. PHPUnit with tests, run by
```
make dev phpunit
```

## CI compliance

The repository is made with support for CI/CD pipelines.
The CI part with all the code quality checks is executed by command
```
make ci
```

## Services in the Stack

The project uses port 80 for http requests/responses. It can be changed in file
./etc/docker/.env - environment variable WEB_APP_PORT.

After starting all the containers, I suggest to firstly open in the browser link 

http://traefik.verification-service.localhost

Here you can see all the endpoints and services exposed.

Among them, there is

http://rabbit.verification-service.localhost

the RabbitMq control panel. You can log in inside by using `guest` / `guest` as login and password.

Next, Mailhog - email catching system, where you will see all the email letters coming from the core
service. The link for Mailhog is

http://mailhog.verification-service.localhost

And finally, Gotify - a service which emulates Mobile / sms messages being sent from the core service.
The link is

http://gotify.verification-service.localhost

Credentials for the administrative account are `admin` / `admin`. Please, do not change them, as the
core service uses this administrative account for communication with Gotify.

When you send a mobile message from the core service, a recipient account is created in Gotify with
default password `secret`. The login of the recipient is the one, you provide for the core service.

With Gotify the lifecycle of the core service is as follows:

- it firstly lists all the users registered in Gotify
- if your provided recipient is missing from the Gotify user list, it is created
- it then lists all the applications of this user
- if an application with name `Verification` is not present, it is then created
- finally, a message is sent via this application to this user

Whily trying Gotify, I experienced a situation, when messages does not appear in Web UI. But they
existed if fetching through the Gotify API. It is related to how Gotify behaves and handles its messages.

## Try out functionality by your self

The core services are divided into two APIs

http://api.template.localhost

and

http://api.verification.localhost

The first stores message templates for verification purposes. The second - processes the verification
requests.

Please, be noticed, that in most of the cases APIs do not respond with message body, the only measure
of information is the response code. This could be easily changed in the code, but for now it is like this.

Requests and responses to both APIs are described in the task for this solution, the Github repository
with the task description is provided in the beginning of this file.

# NOTE!

There might be issues when trying to bootstrap service under `root` account in Linux environment. 
More precisely, issues with file permissions. The service is configured in such a way, that 
it successfully deals with _non-root_ directories and files, especially, when the repository
itself and its files / directories do not belong to the `root` user. On Mac environment, if you 
are using your ordinary user for dealing with this project, everything should work out of the box.

Good luck!
