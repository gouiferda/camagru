# CAMAGRU project

## Description:

  - CAMAGRU is an instagram like web app that is made with PHP (OOP, MVC structure, PDO), and MySQL

## Disclaimer:

  - The project is still under development (unfinished and might have errors and bugs)

## Installing:

Install the app through the setup page to create automatically the database and a user account, steps:

1. **Change database credentials and environment variables**

    1. Copy and rename the database file:
        - cp config/database.example.php config/database.php

    2. Edit database credentials:
        - vim config/database.php

    1. Copy and rename the environment variables file:
        - cp config/env.vars.example.php config/env.vars.php

    2. Edit environment variables:
        - vim config/env.vars.php

1. **Open setup page:**

    - http://localhost/camagru/setup

1. **Authentication**

    - Login page: http://localhost/camagru/signin
        > Created user in setup page: user1:helloWorld10$$
    - Sign up page : http://localhost/camagru/signup
        > After signing up you will need to activate you account through email

## Progress:


- [X] Config database file: has PDO connection credentials to connect with the database
- [X] Config setup file: creates database and tables and a user account (user1:helloWorld10$$)

- [X] Database Class (inherited by all classes)
  - [X] get all
  - [X] get where (by conditions and more)
  - [X] create
  - [X] update
  - [X] delete
  - [X] delete where
  - [X] count all
  - [X] count where

- [X] User class
  - [X] login function
  - [X] sign up function (with secured password in validation)
  - [X] create function : with hashed password
  - [X] update function : for edit profile page
- [X] Post class
  - [X] validate post from local
  - [X] publish post from camera
  - [X] validate post from camera
  - [X] publish post from local
  - [X] get all
  - [X] get for user
  - [X] like
  - [X] unlike
  - [X] comment
  - [X] delete and deletes all associated likes and comments
  - [X] get all with limit (for pagination in home and profile page)
- [X] Like class
- [X] Comment class
  - [X] send mail after comment published if preference is activated

- [X] Mail class (functions to send mail)
- [X] Session class (deals with sessions and session messages)

- [X] Routing functions

- [X] Rendering functions

- [X] Cookies functions (secured)

- [X] Design
  - [X] Themes and CSS
  - [X] Navigation menu (dynamic)
  - [X] Layout (nav,body,sidebar,footer)
  - [X] Darkmode button (using cookies)
  - [X] Mobile optimized
    - [X] Fix nav menu (with js)

- [X] Error page
- [X] Sign in page
- [X] Logout page
- [X] Sign up page
- [X] Confirm account by email page
- [X] Recover password page
- [X] Profile page
  - [X] Design
  - [X] Logic
    - [X] Basic info
    - [X] Connected user's post all
    - [X] Comments and likes statistics
    - [X] Connected user's post pagination
- [X] Edit profile page
  - [X] Design
  - [X] Logic (change info and password)
- [X] Publish picture page
  - [X] Design
  - [X] Logic
    - [X] Capture picture from Live webcam (js)
    - [X] Add stickers to picture from camera (js)
    - [X] Upload picture from camera (php)
    - [X] Upload picture from local (php)
    - [X] Validate the local picture (php)
    - [X] Combine sticker and pick on php
    - [X] Add stickers to picture from local (js)
    - [X] Switch between modes (cemera/upload) (js)
- [X] Post page
  - [X] Design
  - [X] Logic
    - [X] (get post by id)
    - [X] get all (for publish page)
    - [X] get all by user with offset and number (for profile page)
    - [X] like & unlike & delete route (/post/1/like & /post/1/unlike & /post/1/delete)
    - [X] delete post
    - [X] comment on post
- [X] Home page
  - [X] Design
  - [X] Logic
    - [X] listing all posts of all users
    - [X] listing posts with Pagination (infinite pagination)

- [X] Validation and messages
    - [X] Validation functions
    - [X] Session messages
    - [X] Validation messages

- [X] Security functions
  - [X] securing routes (by session and htaccess)
  - [X] check user logged and permissions
  - [X] hashed password
  - [X] protection against database injection (prepared statements)
  - [X] Protection against inject HTML or “user” JavaScript in badly protected variables.
  - [X] Protection against Use an extern form to manipulate so-called private data

- [X] Testing
  - [X] compatible with Firefox (>= 41) and Chrome (>= 46)
  - [X] private and protected pages
  - [X] validation errors in forms
  - [X] incorrect page links
  - [X] injection testing
  - [X] Test Gif with png problem & png with png problem (filter) (other machines)
  - [ ] Test compatibility with chrome old v
  - [ ] Extern form to manipulate private data??

- [ ] Finalizing
    Deleting debugging messages and elements
    One database file

- More to do
    - [ ] Hide load more if all loaded
    - [ ] Likes and comments ajax
    - [ ] Improve design