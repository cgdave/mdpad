<?php
//======================================================================
//
// Name: MDPad
// Description: A simple Markdown editor/viewer
// Version: 1.0
// Credits: Marked (https://github.com/chjj/marked)
//          Highlight.js (https://github.com/isagalaev/highlight.js
// 
// License: This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
//======================================================================

// ---------------- Things that can be customized... -------------------

$defaulttitle = "MDPad"; // Default title (when no file or URL)
$defaultmd = true; // Use default sample markdown (when no file or URL or post)

$handlerallowed = true; // Is usage as handler allowed ?
$fileallowed = true; // Is explicit file=<a relative MD file path> scheme allowed ?
$urlallowed = true; // Is explicit url=<a MD file URL> scheme allowed ?
$postallowed = true; // Are MD in post requests allowed ?

$editallowed = true; // Is local edition allowed ?

$absolutepathtojsandcss = ""; // Absolute path to JS and CSS *with* last "/", needed only if used as a .md file handler

$defaultcssclass = "markdown"; // Default CSS class name

$cachemaxage = 600; // Cache max age (0 = no cache)

$accesscontrolalloworigin = ""; // Allow AJAX requests from other sites, e.g. "*" for any site or explicit site name e.g. "www.mysite.com"

// ---------------------------------------------------------------------

$md = null;
$src = null;

$path = realpath($_SERVER["PATH_TRANSLATED"]);

// Case 1 : Used as a .md files handler
// For Apache web server put a .htaccess with:
//  Action mdp mdpad.php
//  AddHandler mdp .md
if ($handlerallowed && substr($path, -3) == ".md") {
	$src = basename($path);
	$md = file_get_contents($path);
}

// Case 2 : relative file scheme
if ($fileallowed && !isset($md)) {
	$src = @$_GET["file"];
	// Avoid hacking (".." is not allowed if file names)
	if (isset($src) && preg_match("/\.\.\//", $src))
		$src= null;
	if (isset($src)) {
		$md = file_get_contents($path."/".$src);
	}
}

// Case 3 : URL scheme
if ($urlallowed && !isset($md)) {
	$src = @$_GET["url"];
	if (isset($src)) {
		$c = curl_init(); 
		curl_setopt($c, CURLOPT_URL, $src); 
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); 
		$md = curl_exec($c); 
		curl_close($c);
	}
}

// Case 4 : post
if ($postallowed && !isset($md) && $_SERVER["REQUEST_METHOD"] == "POST") {
	$md = file_get_contents("php://input");
	$src = "post.md";
}

// Edit mode ?
$edit = $editallowed && (!isset($src) || isset($_GET["edit"]) || isset($_POST["edit"]));

if ($cachemaxage <= 0) {
	header("Expires: -1");
	header("Cache-Control: max-age=0, no-cache");
} else {
	header("Expires: ".gmdate("D, d M Y H:i:s", time() + $cachemaxage)." GMT");
	header("Cache-Control: max-age=".$cachemaxage.", must-revalidate");
}

if (!empty($accesscontrolalloworigin)) {
	header("Access-Control-Allow-Origin: ".$accesscontrolalloworigin);
}

$cls = @$_GET["class"];
if (!isset($cls)) $cls = $defaultcssclass;
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?php echo isset($src) ? $src : $defaulttitle; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $absolutepathtojsandcss; ?>mdpad.css"/>
<link rel="stylesheet" type="text/css"href="<?php echo $absolutepathtojsandcss; ?>highlight.css">
<?php if ($edit) { ?><style type="text/css" media="screen">
body.<?php echo $cls; ?> { overflow: hidden; }
body.<?php echo $cls; ?> .<?php echo $cls; ?> { overflow: auto; position: absolute; top: 0; bottom: 0; right: 0; left: 0; }
</style><?php } ?>
<script type="text/javascript" src="<?php echo $absolutepathtojsandcss; ?>marked.js"></script>
<script type="text/javascript" src="<?php echo $absolutepathtojsandcss; ?>highlight.js"></script>
</head>
<body class="<?php echo $cls; ?>">
<textarea id="md" rows="15" style="<?php echo $edit ? "margin: 0; border: none 0; padding: 5px; overflow: auto; outline: none; position: absolute; top: 0; bottom: 0; left: 0; width: 740px; height: 100%;" : "display: none;"; ?>">
<?php
if (isset($md)) {
	echo $md;
} else if ($defaultmd) {
?>
![Hello world](https://raw.githubusercontent.com/cgdave/mdpad/master/mdpad.png)
---

Hello world
===========

Hello world
-----------

### Hello world

#### Hello world

##### Hello world

Hello **world**, hello _world_

- Hello
	- Hello
	- World
- World 

Hello `world`, hello [world](http://www.github.com)

1. Hello
2. World

&gt; Hello world

| Hello | Hello     | Hello |
| ----- |:---------:| -----:|
| world | _world_   | $1000 |
| world | **world** |  $500 |

```java
public hello(world) {
	String s = "hello world";
}
```

```javascript
function hello(world) {
	var s = "hello world";
}
```

```css
p.hello { width: 5px; }
h1.world { height: 100%; } 
```

```html
<p>hello <span>world</span></p>
```

```sql
select 1 from hello.world;
```
<?php
}
?>
</textarea>
<div id="html" class="<?php echo $cls; ?>"<?php echo $edit ? " style=\"left: 750px;\"" : ""; ?>></div>
<script type="text/javascript">
window.onload = function() {
	hljs.initHighlightingOnLoad();
	marked.setOptions({
		gfm: true,
		highlight: function(code) {
			return hljs.highlightAuto(code).value;
		}
	});
	var md = document.getElementById("md");
	function convert() {
		document.getElementById("html").innerHTML = marked(md.value);
	}
	var t;
	md.onkeyup = function(e) {
		t = setTimeout(convert, 500);
	};
	md.onkeydown = function(e) {
		if (e.keyCode == 9 || event.which == 9) {
			e.preventDefault();
			var s = this.selectionStart;
			this.value = this.value.substring(0, this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
			this.selectionEnd = s + 1; 
		}
		if (t) clearTimeout(t);
	};
	md.onchange = function() {
		if (t) clearTimeout(t);
		convert();
	}
	convert();
	md.focus();
}
</script>
</body>
</html>
