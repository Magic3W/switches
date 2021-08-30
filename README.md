

# Switches
Switches provides a user directory for micro service networks, while PHPAS usually focus on the authentication
of users, switches focuses on providing a consistent account management experience.

Applications can use switches to store user settings and allow the user to edit them right on the swicthes
page, retrieve profile information and similar from the user.

## Installing / Getting started

You can start Switches with `docker-compose` like

```shell
docker build .
docker-compose up -d
```

The `d` stands for daemon and keeps docker running in the background, so Switches will continue
running even if you close the terminal. It's optional.

If you want the project to run without `docker` I recommend you still check into the `Dockerfile`
since that contains the detailed instructions on how we exactly set up our environment. But you'll
need [composer](https://getcomposer.com) to pull the dependencies for the project.

```shell
apt install php-mysql php-memcached memcached php-intl
composer install
nano bin/settings/environments.php # To edit the database credentials
```

### Initial Configuration

Switches requires to be properly configured to pair and authenticate a user against PHPAS,
after that, the application requires no further setup.

## Developing

To help the development of switches, please start of by reaching out to us. We do
only mirror our code to Github, but internally use Phabricator for code reviews and
similar. This means that accepting Pull Requests is cumbersome for us and will
remove credit from you. Just shoot us an email at cesar@magic3w.com and I'll help
you get started with Phabricator.

Once you have an account on our Phabricator and have `Arcanist` set up, you can just 
pull the repository like normal:

```shell
git clone ssh://git@phabricator.magic3w.com/source/switches.git
cd switches/
composer install
```

To start working on your own changes I recommend you check out a new branch on your
local repository.

```shell
git checkout -b dev/my-new-feature
```

You can now make the changes you need. Test the code locally, once you're satisfied,
you can send us a `diff` (Phabricator's version of a Pull Request) by following these
steps.

```shell
composer test # This will tell you if your code matches our guidelines.
git add .
git commit -m "Your commit message"
arc diff
```

You will be prompted to explain the changes you made, how to test them and who should
review your change. You can leave the reviewer empty if you're unsure.

### Building

Once you've made your changes and wish to test the application, just run:

```shell
docker build .
```

You should get a built container out of this that you can now publish to your
server or push to a docker registry from which it can be downloaded. If you're
running on a Kubernetes cluster, you can also push it to the cluster directly.

## Features

* Upload user avatars
* Set user display names, bios and banners
* Change the account configuration


## Contributing

Thank you so much for contributing to making the project better. We appreciate
all contributions, big and small.

Code submissions are always welcome. As stated above, I would recommend you reach
out and get onboarded to our Phabricator, but if you just want to make a small
submission you can always send a pull request on Github.

## Links

- Project homepage: https://phabricator.magic3w.com/source/switches/
- Repository: https://phabricator.magic3w.com/source/switches/
- Issue tracker: https://phabricator.magic3w.com/maniphest/task/edit/form/default/
  - You will need an account to access the Phabricator
  - In case of sensitive bugs like security vulnerabilities, please contact
    cesar@magic3w.com directly instead of using issue tracker. We value your effort
    to improve the security and privacy of this project!
- Related projects:
  - Authentication server: https://phabricator.magic3w.com/source/phpas/
  - Permission server: https://phabricator.magic3w.com/source/permission/
  - Profile server: https://phabricator.magic3w.com/source/Switches/


## Licensing

The code in this project is licensed under GPL license.
