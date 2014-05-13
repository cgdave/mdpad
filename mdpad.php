<?php
$file = $_GET["file"];
$md = null;
if (isset($file)) {
	$p = realpath($_SERVER["PATH_TRANSLATED"])."/".$file;
	$md = file_get_contents($p);
}
$edit = !isset($file) || isset($_GET["edit"]);
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
