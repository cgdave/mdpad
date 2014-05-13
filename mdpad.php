<?php
$md = null;

$path = realpath($_SERVER["PATH_TRANSLATED"]);

// Case 1 : path translated is a markdown file, this means MDPad is used as a .md files handler, e.g.:
// Action mdp mdpad.php
// AddHandler mdp .md
if (substr($path, -3) == ".md") {
	$md = file_get_contents($path);
}

$src = null;

// case 2 : explicit file
if (!isset($md)) {
	$src = $_GET["file"];
	// Avoid hacking (".." is not allowed if file names)
	if (preg_match("/\.\.\//", $src))
		$src= null;
	if (isset($src)) {
		$md = file_get_contents($path."/".$src);
	}
}

// Case 3 : explicit URL
if (!isset($md)) {
	$src = $_GET["url"];
	if (isset($src)) {
		$c = curl_init(); 
		curl_setopt($c, CURLOPT_URL, $src); 
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); 
		$md = curl_exec($c); 
		curl_close($c);
	}
}

// Edit mode ?
$edit = !isset($src) || isset($_GET["edit"]);
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo isset($file) ? $file : "MDPad"; ?></title>
<link rel="stylesheet" type="text/css" href="markdown.css"/>
<link rel="stylesheet" type="text/css"href="highlight.css">
<script type="text/javascript" src="marked.js"></script>
<script type="text/javascript" src="highlight.js"></script>
</head>
<body style="margin: 0; padding: 0; overflow: hidden;">
<textarea id="md" rows="15" style="<?php echo $edit ? "margin: 0; border: none 0; padding: 5px; overflow: auto; outline: none; position: absolute; top: 0; bottom: 0; left: 0; width: 50%; height: 100%;" : "display: none;"; ?>">
<?php
if (isset($md)) {
	echo $md;
} else {
?>
![Hello world](https://raw.githubusercontent.com/cgdave/mdpad/master/logo.png)
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

```java
public hello(world) {
	// Hello World
}
```
<?php
}
?>
</textarea>
<div id="html" class="markdown" style="overflow: auto; position: absolute; top: 0; bottom: 0; right: 0; left: <?php echo $edit ? "50%" : "0"; ?>;"></div>
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
		console.log("Convertion !");
		document.getElementById("html").innerHTML = marked(md.value);
	}
	var t;
	md.onkeyup = function(e) {
		t = setTimeout(convert, 500);
	};
	md.onkeydown = function(e) {
		if (e.keyCode==9 || event.which==9){
			e.preventDefault();
			var s = this.selectionStart;
			this.value = this.value.substring(0, this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
			this.selectionEnd = s+1; 
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
