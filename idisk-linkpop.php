<?php
/*
Plugin Name: IDisk-Link-Popularity
Plugin URI: https://immobiliendiskussion.de/
Description: Dieses Plugin integriert die Link-Popularity der ImmobilienDiskussion in WordPress.
Version: 0.3-SNAPSHOT
Author: Andreas Rudolph, Walter Wagner (OpenIndex.de)
Author URI: http://www.openindex.de/
License: MIT
*/

/**
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author Andreas Rudolph <andy@openindex.de>
 * @copyright 2006-2017 OpenIndex.de
 * @license MIT
 */

// Register the [IDiskLinkpopularity] shortcode.
// see http://codex.wordpress.org/Function_Reference/add_shortcode
add_shortcode('IDiskLinkpopularity', 'idisk_linkpop_shortcode');

/**
 * Replace [IDiskLinkpopularity] shortcode with wrapped content.
 * see http://codex.wordpress.org/Shortcode_API
 * @param array $atts Attributes in the [IDiskLinkpopularity] shortcode.
 * @return string Wrapped content.
 */
function idisk_linkpop_shortcode($atts) {

  // load attributes from the shortcode
  $settings = shortcode_atts(array(
    'id' => '0',
    'type' => 'table_long',
    'utf8' => '0',
      ), $atts);

  // get idisk user id
  $id = (isset($settings['id'])) ? $settings['id'] : null;
  if (is_null($id)) {
    return idisk_linkpop_error('Keine IDisk-Benutzer-ID angegeben!');
  }
  if (!is_numeric($id) || $id <= 0) {
    return idisk_linkpop_error('Ungültige IDisk-Benutzer-ID angegeben!');
  }

  // get type of linkpopularity view (table_short / table_long)
  $type = (isset($settings['type'])) ? strtolower($settings['type']) : null;

  // build URL for the linkpopularity view
  $url = 'http://www.immobiliendiskussion.de/LP/' . $id;
  if ($type == 'table_short') {
    $url .= '/table_short';
  }
  else {
    $url .= '/table_long';
  }

  // get the content for the URL
  $content = idisk_linkpop_download($url);
  if (!is_string($content) || strlen($content) < 1) {
    return idisk_linkpop_error('Link-Popularity konnte nicht abgerufen werden!');
  }

  // convert and return the content
  $utf8 = (isset($settings['utf8'])) ? strtolower($settings['utf8']) : null;
  return ($utf8 == '1' || $utf8 == 'true') ? utf8_encode($content) : $content;
}

function idisk_linkpop_error($msg) {
  return '<div style="border:1px solid red; padding:1em; text-align:center; background-color:#f5f5b5; color:red;">'
      . '<strong>Fehler:</strong> ' . $msg . '</div>';
}

function idisk_linkpop_download($url) {

  // load the content via file_get_contents,
  // if allow_url_fopen is enabled in the PHP runtime
  if (ini_get('allow_url_fopen')) {
    return file_get_contents($url);
  }

  // alternatively load the content via cURL,
  // if it is available in the PHP runtime
  elseif (function_exists('curl_init')) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    $content = curl_exec($curl);
    curl_close($curl);
    return $content;
  }

  // return an error message, if the download is not possible
  else {
    return idisk_linkpop_error('Ihr Webspace unterstützt weder <strong>allow_url_fopen</strong> noch <strong>cURL</strong>!');
  }
}
