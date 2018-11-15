# Puzzletron

Software for editing/testing puzzles for the
[MIT Mystery Hunt](http://www.mit.edu/~puzzle/)

Currently being hacked by Death and Mayhem in preparation for the 2018
Hunt. Many thanks to our predecessors Setec Astronomy (2017), Team
Luck (2016), Random Fish (2015), Alice Shrugged (2014), and the long
line of programmers who came before them.

# Developing Puzzletron

You've got a couple of options.

## Virtual Machine (recommended for Macs and Linux)

This project is configured to set itself up in a virtual machine on
your computer. I've tested this on the Mac. Alas, the setup code uses
Ansible which does not work on Windows right now.

- If you have not done so already, use `ssh-keygen` to create your
personal SSH key and store it at `~/.ssh/id_rsa.pub` -- or, if you
store it elsewhere, edit the Vagrantfile to point to it.

- Install [Virtualbox](https://www.virtualbox.org).

- Install [Vagrant](http://www.vagrantup.com).

- Install
  [Ansible](http://docs.ansible.com/intro_installation.html#installing-the-control-machine). e.g. on
  the Mac with Homebrew: `brew update`, `brew install ansible`.

- Copy `ansible/secrets.yml.example` to `ansible/secrets.yml`. Then,
if you like, edit the configuration settings in there. Read
`ansible/README-secrets.md` for more instructions.

- From the directory with the Vagrantfile, run `vagrant up`.

- Use `vagrant ssh` to connect to the box, or add this line to your `/etc/hosts`:

```
192.168.33.31  puzzletron.vm
```

...and try `ssh ubuntu@puzzletron.vm`.

### Mail

The Vagrant box doesn't include a mail server because they're a pain,
and because transactional email services are free at small
volumes. Set up a Mailgun account and point Puzzletron at it using the
`MAILGUN` configuration settings in `ansible/secrets.yml`.

## Docker

- Install [Docker](https://www.docker.com/community-edition#/download)
- `cp ./docker/dev/.env ./.env`
- Edit `.env` to add e.g. mailgun settings if desired
- `docker-compose up`
- Head to http://localhost:8000

## On your local machine using a LAMP stack

If you're willing to contemplate running PHP, MySQL, and Apache on
your local machine you can use this code directly. You can use
projects like MAMP to help set up your computer with these things.

- Initialize the MySQL database:

    - Log into your mysql database server with full administrative
      priviliges.
    - Create a puzzletron user
    - Create a puzzletron database
    - Grant the puzzletron user access to that database

```
          mysql -u <mysqlusername> -h <servername> -p <databasename> < schema.sql
```
      (enter password for the puzzletron DB user when prompted)

      `'puzzletron'@'localhost'` won't work on NFS due to distributed blah blah blah, just omit `@'localhost'`

      CREATE USER 'puzzletron' IDENTIFIED BY 'your password goes here';
      CREATE DATABASE puzzletron;
      GRANT ALL ON puzzletron.* TO 'puzzletron';

- Copy `dotenv.example` to `.env` and edit
  appropriately. `PTRON_DB_NAME`, `PTRON_DB_USER`, and
  `PTRON_DB_PASSWORD` are the name, user, and password of the database
  you created above. `PTRON_URL` is the URL that will appear in links
  which point back at your app.

- `composer install`
- you probably want to give yourself permissions or something so `select * from users;` and `insert into user_role values (<your uid>, 8);`
- (just NFS things) make and `chgrp web` some random folders: `tmp/purifier-cache`, `uploads/pictures/thumbs`, `uploads/puzzle_files`

### File Permissions:

Make sure that the `uploads` directory exists in this directory (you
may need to create it), and that it (and everything underneath it) and
the `tmp` directory (and everything underneath it) are writable and
searchable by your web server.


### To get email notifications working:

In order for puzzletron to actually send its email queue (comments on
puzzles, etc.) there needs to be a cron job that runs
`email_cronjob.php` script with your php interpreter at some regular
frequency. The Vagrant box and the deployment scripts set this up
automatically, but you might have to set it up by hand.

### To get idle puzzle reminder notifications working:

If reminder emails are enabled (using `PTRON_REMINDER_EMAIL_DAYS`),
there also needs to be a cronjob that runs `reminder_cronjob.php`
script at some regular frequency.

# How does Puzzletron work?

## Customize database stuff:

* `priv` table contains list of roles and what privileges they have

* `pstatus` table contains list of possible puzzle statuses and what
  can happen at each status


The last people to touch this code if you need help:

* matthew.gruskin@gmail.com (2017)

* mike@mechanicalfish.net (2016)
