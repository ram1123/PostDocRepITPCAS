<html>
<head>
<title><?php echo getcwd(); ?></title>
<style type='text/css'>
body {
    font-family: "Candara", sans-serif;
    font-size: 9pt;
    line-height: 13.5pt;
}
div.pic h3 { 
    font-size: 11pt;
    margin: 0.5em 1em 0.2em 1em;
}
div.pic p {
    font-size: 11pt;
    margin: 0.2em 1em 0.1em 1em;
}
div.pic {
    display: block;
    float: left;
    background-color: white;
    border: 1px solid #ccc;
    padding: 2px;
    text-align: left;
    margin: 2px 11px 10px 2px;
    -moz-box-shadow: 7px 5px 5px rgb(80,80,80);    /* Firefox 3.5 */
    -webkit-box-shadow: 7px 5px 5px rgb(80,80,80); /* Chrome, Safari */
    box-shadow: 7px 5px 5px rgb(80,80,80);         /* New browsers */  
    width: 320px;
}
a { text-decoration: none; color: rgb(0,25,238); }
b { text-decoration: none; color: rgb(200,0,25); }
a:hover { text-decoration: underline; color: rgb(255,80,80); }
div.dirlinks h2 { margin-top: 12pt;   margin-bottom: 14pt; margin-left: -24pt; color: rgb(222,0,0);  }
div.dirlinks {  margin: 0 30pt; } 
div.dirlinks a {
    font-size: 13pt; font-weight: bold;
    padding: 0 0.5em; 
}
div.dirlinks b {
    font-size: 13pt; font-weight: bold;
    padding: 0 0.5em; 
}
</style>
</head>
<body>
<h1><?php echo getcwd(); ?></h1>
<?php
$has_subs = false;
foreach (glob("*") as $filename) {
    if (is_dir($filename) && !preg_match("/^\..*|.*private.*/", $filename)) {
        $has_subs = true;
        break;
    }
}
if ($has_subs) {
    print "<div class=\"dirlinks\">\n";
    print "<h2>Directories</h2>\n";
    print "<a href=\"../\">OneUp</a> ";
    foreach (glob("*") as $filename) {
        if (is_dir($filename) && ($_SERVER['PHP_AUTH_USER'] == 'gpetrucc' || !preg_match("/^\..*|.*private.*/", $filename))) {
            print " <a href=\"$filename\">$filename</a>";
        }
    }
    print "</div>";
}

foreach (array("00_README.txt", "README.txt", "readme.txt") as $readme) {
    if (file_exists($readme)) {
        print "<pre class='readme'>\n"; readfile($readme); print "</pre>";
    }
}
?>

<h2><b name="plots">Plots</b></h2>
<p><form>Filter: <input type="text" name="match" size="30" value="<?php if (isset($_GET['match'])) print htmlspecialchars($_GET['match']);  ?>" /><input type="Submit" value="Go" /><input type="checkbox"  name="regexp" <?php if ($_GET['regexp']) print "checked=\"checked\""?> >RegExp</input></form></p>
<div>
<?php
$displayed = array();
if ($_GET['noplots']) {
    print "Plots will not be displayed.\n";
} else {
    $other_exts = array('.pdf', '.cxx', '.eps', '.root', '.txt', '.dir', '.info');
    $filenames = glob("*.png"); sort($filenames);
    foreach ($filenames as $filename) {
        if (isset($_GET['match'])) {
             if (isset($_GET['regexp']) && $_GET['regexp']) {
                if (!preg_match('/.*'.$_GET['match'].'.*/', $filename)) continue;
             } else {
                if (!fnmatch('*'.$_GET['match'].'*', $filename)) continue;
             }
        }
        array_push($displayed, $filename);
        $brfname = str_replace("_","_&shy;",$filename);
        print "<div class='pic'>\n";
        print "<h3><a href=\"$filename\">$brfname</a></h3>";
        print "<a href=\"$filename\"><img src=\"$filename\" style=\"border: none; width: 300px; \"></a>";
        $others = array();
        foreach ($other_exts as $ex) {
            $other_filename = str_replace('.png', $ex, $filename);
            if (file_exists($other_filename)) {
                array_push($others, "<a class=\"file\" href=\"$other_filename\">[" . $ex . "]</a>");
                if ($ex != '.txt') array_push($displayed, $other_filename);
            }
        }
        if ($others) print "<p>Also as ".implode(', ',$others)."</p>";
        print "</div>";
    }
}
?>
</div>
<div style="display: block; clear:both;">
<h2><a name="files">Other files</a></h2>
<ul>
<?
foreach (glob("*") as $filename) {
    if ($_GET['noplots'] || !in_array($filename, $displayed)) {
        if (isset($_GET['match'])) {
             if (isset($_GET['regexp']) && $_GET['regexp']) {
                if (!preg_match('/.*'.$_GET['match'].'.*/', $filename)) continue;
             } else {
                if (!fnmatch('*'.$_GET['match'].'*', $filename)) continue;
             }
        }
        if (is_dir($filename)) {
            print "<li>[DIR] <a href=\"$filename\">$filename</a></li>";
        } else {
            print "<li><a href=\"$filename\">$filename</a></li>";
        }
    }
}
?>
</ul>
</div>
</body>
</html>
