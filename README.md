![MDPad](https://raw.githubusercontent.com/cgdave/mdpad/master/mdpad.png)
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

To use an alternative CSS styles set you simply need to append `&class=<my class name>` to the URL (default value for this parameter is `markdown` that matches the default classes names in the `styles.css`file).

Advanced usage
--------------

You can use MDPad as a **handler** for `.md` files with various web servers:

### Apache

You need to have PHP enabled on Apache, then the configuration of the handler is something like:

```plain
Action mdp /path/to/mdpad.php
AddHandler mdp .md
```

This can be put globally or in a `<Location>` block of the main configuration files or in a local `.htaccess` file.

### NGINX

You need to have FastCGI PHP server installed and properly configured, then the configuration is something likes:

```
```

License
-------

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License [here](http://www.apache.org/licenses/LICENSE-2.0)

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
