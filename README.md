ID-LinkPopularity-WordPress 0.3.3
=================================

Dieses Repository enthält ein Plugin zur Einbindung der von
[ImmobilienDiskussion.de](https://immobiliendiskussion.de) bereitgestellten
Link-Popularity in eine auf [WordPress](https://de.wordpress.org/) basierende
Webseite.

-   Allgemeine Informationen zur Link-Popularity finden Sie
    [in diesem Wissensartikel](https://immobiliendiskussion.de/wiki/idisk-link-popularity).
-   Weitere Informationen zur Verwendung des WordPress-Plugins finden Sie
    [in diesem Wissensartikel](https://immobiliendiskussion.de/wiki/idisk-link-popularity-wordpress).
-   Bei Fragen oder Unklarheiten zur Verwendung des WordPress-Plugins
    [nehmen Sie bitte Kontakt mit der Moderation](https://immobiliendiskussion.de/contact)
    der ImmobilienDiskussion auf.

Lizenz
------

Die in diesem Repository bereitgestellten Skripte können unter den Bedingungen
der [MIT Lizenz](https://opensource.org/licenses/MIT) frei verwendet und
angepasst werden.

Changelog
---------

### 0.3.3

-   Cache downloaded link list for 24 hours in order to improve page generation
    time and fault tolerance.

### 0.3.2

-   Disable certificate checks on download, if it was explicitly configured by
    the `nocert` parameter or if the PHP environment does not support SNI
    ([Server Name Indication](https://en.wikipedia.org/wiki/Server_Name_Indication)).

### 0.3.1

-   Use https encrypted URL in order to download the link list.

### 0.3

-   Migrated to the less restrictive [MIT license](https://opensource.org/licenses/MIT).
-   Made some changes for the upcoming relaunch of ImmobilienDiskussion.de.
-   Removed Netbeans project structure.
-   **Notice:** Please consider, that the shortcode parameters has been changed
    with this update.

### 0.2

-   Bugfix: Make use of the [WordPress Shortcode API](http://codex.wordpress.org/Shortcode_API) in order to fix a compatibility issue with WordPress 4.0.1.
-   Feature: Try to download content via cURL, if `allow_url_fopen` is disabled.
-   made some syntax fixes

### 0.1

-   First public release
