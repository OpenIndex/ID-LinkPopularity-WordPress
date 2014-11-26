<?php
/*
Plugin Name: IDisk-Link-Popularity
Plugin URI: http://www.immobiliendiskussion.de/
Description: Dieses Plugin integriert die Link-Popularity der ImmobilienDiskussion in WordPress.
Version: 0.2-SNAPSHOT
Author: Andreas Rudolph, Walter Wagner (OpenIndex.de)
Author URI: http://www.openindex.de/
License: GPL3
Id: $Id$
*/

add_filter('the_content', 'idisk_linkpop_post');
function idisk_linkpop_post( $post ) {
  if (!is_single() && !is_page()) return $post;

  // Platzhalter suchen und ersetzen
  $regex = '/\[\s?IDiskLinkpopularity\s*([^\]]*)\]/is';
  return preg_replace_callback( $regex, 'idisk_linkpop_post_callback', $post );
}
function idisk_linkpop_post_callback( $matches ) {

  // Konfiguration im Platzhalter ermitteln
  //echo '<pre>'; print_r($matches); echo '</pre>';
  $regex = '/\s?([^=]*)\s?="([^"]*)"/is';
  $values = array();
  preg_match_all( $regex, $matches[1], $values );
  //echo '<pre>'; print_r($values); echo '</pre>';
  $settings = array();
  foreach ($values[1] as $pos=>$key) {
    $key = strtolower( trim($key) );
    $settings[$key] = trim( $values[2][$pos] );
  }

  // IDisk-Benutzer-ID ermitteln
  $id = (isset($settings['id']))? $settings['id']: null;
  if (is_null($id)) {
    return idisk_linkpop_error( 'Keine IDisk-Benutzer-ID angegeben!' );
  }
  if (!is_numeric($id)) {
    return idisk_linkpop_error( 'Ungültige IDisk-Benutzer-ID angegeben!' );
  }

  // Art der LP-Darstellung ermitteln
  $type = (isset($settings['type']))? strtolower($settings['type']): null;

  // URL zur LP-Darstellung konstruieren
  $url = 'http://www.immobiliendiskussion.de/LP/' . $id;
  if ($type=='table_short') {
    $url .= '/table_short';
  }
  else {
    $url .= '/table_long';
  }

  // Ausgabe erzeugen
  $content = implode( '', file( $url ) );
  $utf8 = (isset($settings['utf8']))? strtolower($settings['utf8']): null;
  return ($utf8=='1' || $utf8=='true')? utf8_encode( $content ): $content;
}
function idisk_linkpop_error( $msg ) {
  return '<div style="border:1px solid red; padding:1em; text-align:center; background-color:#f5f5b5; color:red;">'.
    '<strong>Fehler:</strong> ' . $msg . '</div>';
}
