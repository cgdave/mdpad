![MDPad](https://raw.githubusercontent.com/cgdave/mdpad/master/logo.png)
---

Introduction
------------

MDPad is a simple web based markdown viewer and local editor based on [Marked](https://github.com/chjj/marked) and [Highlight.js](https://github.com/isagalaev/highlight.js).

![MDPad](https://raw.githubusercontent.com/cgdave/mdpad/master/snapshot.jpg)

Up-to-date version is available at [GitHub](https://github.com/cgdave/mdpad).

Installation
------------

Copy the `mdpad.php`, `*.js` and `*.css` files to your target directory in your web server's document root.

Usage
-----

Start your favorite web browser and point to `http://yourserver.yourdomain/yourdirectory/mdpad.php`.

You can specify the location of the markdown file to view/edit:

- A relative file using the `file` parameter (e.g. `http://yourserver.yourdomain/yourdirectory/mdpad.php?file=relative/path/to/README.md). Note that relative paths with `..` in it are ignored.
- A URL using the `url` parameter (e.g. `http://yourserver.yourdomain/yourdirectory/mdpad.php?url=http%3A%2F%2Fsomewher.com%2Fpath%2Fto%2FREADME.md`). Note that URL must be URL-encoded.

To get the local edit panel you simply need to append `&edit` to the URL. Any change made in the edit panel is automatically reflected on the view panel **but** nothing is saved.

Advanced usage
--------------

You can use MDPad as a **handler** for `.md` files. For instance, with Apache the configuration (e.g. in a local `.htaccess` file) would be like:

```plain
Action mdp /path/to/mdpad.php
AddHandler mdp .md

```
