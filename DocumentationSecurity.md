# Security #



## Register Globals ##

register\_globals is turned off in the `.htaccess` file.

## Error Reporting ##

If debugging is turned off (with setting the configuration variable `$debug` to `false` in a production environment), errors and exceptions are never shown in the front-end. Instead, they are logged to `private/var/log` (make sure this directory is writable, otherwise Ajde will error out horrendously).

## Cross-Site Scripting (XSS) ##

Ajde aggressively filters out HTML from requests when using `Ajde_Http_Request`.

Ajde looks for the configuration variables `$autoEscapeString` and `$autoCleanHtml`, and when set to `true`, it applies [htmlspecialchars](http://php.net/manual/en/function.htmlspecialchars.php) and [strip\_tags](http://php.net/manual/en/function.strip-tags.php) - with only a limited set of allowed tags - to request variables retrieved with `Ajde_Http_Request::get()|getParam()|getString()` and `Ajde_Http_Request::getHtml()` respectively.

**However, it is always the responsibility of the developer to filter user input!**

_Note:_ When using [HTMLPurifier](DocumentationExternalLibraries.md), `Ajde_Http_Request::getHtml()` uses `HTMLPurifier::purify()` instead.

_Note:_ Ajde doesn't prevent developers from using the raw `$_GET` and `$_POST` objects, which can contain unsafe data!

## Cross-Site Request Forgery (CSRF) Prevention ##

When using `Ajde_Component_Form` helper functions (`$this->ACForm()` or `$this->ACAjaxForm()`) in a template, and the configuration variables `$requirePostToken` is set to `true`, Ajde will validate form token and timestamp to prevent CSRF attacks.

## Session hijacking ##

Sessions are strengthened with the client user agent and IP address. On each request, the session is validated, eliminating several session hijacking attack vectors.

## Exposing Sensitive Information ##

All scripts are located in the `private/` folder, which is prevented from being accessed from outside. Server-side scripts in the `public/` folder are never executed.

Only the `private/var/*` directories should be writable by the webserver.

## User authentication ##

When using the `AjdeX_User` extension, user cookies are hashed with the client IP address, a unique user secret and application secret, making it virtually impossible to use a stolen user authentication cookie.