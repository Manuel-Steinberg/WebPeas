<?php
/**
 * On-the-fly CSS Compression
 * Copyright (c) 2009 and onwards, Manas Tungare.
 * Creative Commons Attribution, Share-Alike.
 *
 * In order to minimize the number and size of HTTP requests for CSS content,
 * this script combines multiple CSS files into a single file and compresses
 * it on-the-fly.
 *
 * To use this in your HTML, link to it in the usual way:
 * <link rel="stylesheet" type="text/css" media="screen, print, projection" href="/css/compressed.CSS.php" />
 */

include_once("../../site/plugins/minify/CSS.php");

$css = new CSS("assets/css/");

/**
 * Ideally, you wouldn't need to change any code beyond this point.
 */
$buffer = $css->getBuffer();

// Enable GZip encoding.
ob_start("ob_gzhandler");
// Enable caching
header('Cache-Control: public');
// Expire in one day
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
// Set the correct MIME type, because Apache won't set it for us
header("Content-type: text/css");
// Write everything out
echo($buffer);
?>