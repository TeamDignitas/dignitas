<?php

/**
 * Two-way routing:
 * (1) Resolve URLs like path1/path2/arg1/arg2 to PHP files + GET arguments;
 * (2) Resolve links like path/to/file to localized URLs.
 **/

class Router {

  // Reverse route definitions, mapping file names to localized URLs. We
  // prefer this format in order to group all information about one file. We
  // compute the forward routes upon initialization. Files have an implicit
  // .php extension.
  const ROUTES = [
    // aggregate
    'aggregate/dashboard' => [
      'en_US.utf8' => 'dashboard',
      'ro_RO.utf8' => 'panou-control',
    ],
    'aggregate/search' => [
      'en_US.utf8' => 'search',
      'ro_RO.utf8' => 'cautare',
    ],
    'aggregate/ajaxSearch' => [
      'en_US.utf8' => 'ajax/search',
    ],
    'aggregate/index' => [
      'en_US.utf8' => '',
    ],

    // answers
    'answer/edit' => [
      'en_US.utf8' => 'edit-answer',
      'ro_RO.utf8' => 'editeaza-raspuns',
    ],
    'answer/history' => [
      'en_US.utf8' => 'answer-history',
      'ro_RO.utf8' => 'istoric-raspuns',
    ],
    'answer/saveProof' => [
      'en_US.utf8' => 'ajax/save-proof',
    ],

    // attachments
    'attachment/upload' => [
      'en_US.utf8' => 'ajax/upload-attachment',
    ],
    'attachment/view' => [
      'en_US.utf8' => 'attachment',
    ],

    // auth
    'auth/login' => [
      'en_US.utf8' => 'login',
      'ro_RO.utf8' => 'autentificare',
    ],
    'auth/logout' => [
      'en_US.utf8' => 'logout',
      'ro_RO.utf8' => 'deconectare',
    ],
    'auth/lostPassword' => [
      'en_US.utf8' => 'lost-password',
      'ro_RO.utf8' => 'parola-uitata',
    ],
    'auth/passwordRecovery' => [
      'en_US.utf8' => 'password-recovery',
      'ro_RO.utf8' => 'recuperare-parola',
    ],
    'auth/register' => [
      'en_US.utf8' => 'register',
      'ro_RO.utf8' => 'inregistrare',
    ],

    // canned responses
    'cannedResponse/edit' => [
      'en_US.utf8' => 'edit-canned-response',
      'ro_RO.utf8' => 'editeaza-raspuns-predefinit',
    ],
    'cannedResponse/history' => [
      'en_US.utf8' => 'canned-response-history',
      'ro_RO.utf8' => 'istoric-raspuns-predefinit',
    ],
    'cannedResponse/list' => [
      'en_US.utf8' => 'canned-responses',
      'ro_RO.utf8' => 'raspunsuri-predefinite',
    ],

    // comments
    'comment/delete' => [
      'en_US.utf8' => 'ajax/delete-comment',
    ],
    'comment/save' => [
      'en_US.utf8' => 'ajax/save-comment',
    ],

    // domains
    'domain/edit' => [
      'en_US.utf8' => 'edit-domain',
      'ro_RO.utf8' => 'editeaza-domeniu',
    ],
    'domain/image' => [
      'en_US.utf8' => 'domain-image',
      'ro_RO.utf8' => 'imagine-domeniu',
    ],
    'domain/list' => [
      'en_US.utf8' => 'domains',
      'ro_RO.utf8' => 'domenii',
    ],

    // entities
    'entity/edit' => [
      'en_US.utf8' => 'edit-author',
      'ro_RO.utf8' => 'editeaza-autor',
    ],
    'entity/history' => [
      'en_US.utf8' => 'author-history',
      'ro_RO.utf8' => 'istoric-autor',
    ],
    'entity/image' => [
      'en_US.utf8' => 'entity-image',
      'ro_RO.utf8' => 'imagine-entitate',
    ],
    'entity/load' => [
      'en_US.utf8' => 'ajax/load-entities',
    ],
    'entity/search' => [
      'en_US.utf8' => 'ajax/search-entities',
    ],
    'entity/view' => [
      'en_US.utf8' => 'entity',
      'ro_RO.utf8' => 'autor',
    ],

    // flags
    'flag/delete' => [
      'en_US.utf8' => 'ajax/delete-flag',
    ],
    'flag/save' => [
      'en_US.utf8' => 'ajax/save-flag',
    ],

    // help pages and categories
    'help/categoryEdit' => [
      'en_US.utf8' => 'edit-help-category',
      'ro_RO.utf8' => 'editeaza-categorie-ajutor',
    ],
    'help/categoryList' => [
      'en_US.utf8' => 'help-categories',
      'ro_RO.utf8' => 'categorii-ajutor',
    ],
    'help/index' => [
      'en_US.utf8' => 'help',
      'ro_RO.utf8' => 'ajutor',
    ],
    'help/pageEdit' => [
      'en_US.utf8' => 'edit-help-page',
      'ro_RO.utf8' => 'editeaza-pagina-ajutor',
    ],
    'help/pageHistory' => [
      'en_US.utf8' => 'help-history',
      'ro_RO.utf8' => 'istoric-ajutor',
    ],

    // helpers
    'helpers/changeLocale' => [
      'en_US.utf8' => 'changeLocale',
    ],

    // relations
    'relation/edit' => [
      'en_US.utf8' => 'edit-relation',
      'ro_RO.utf8' => 'editare-relatie',
    ],

    // reviews
    'review/view' => [
      'en_US.utf8' => 'review',
      'ro_RO.utf8' => 'evaluare',
    ],

    // statements
    'statement/edit' => [
      'en_US.utf8' => 'edit-statement',
      'ro_RO.utf8' => 'editeaza-afirmatie',
    ],
    'statement/history' => [
      'en_US.utf8' => 'statement-history',
      'ro_RO.utf8' => 'istoric-afirmatie',
    ],
    'statement/search' => [
      'en_US.utf8' => 'ajax/search-statements',
    ],
    'statement/view' => [
      'en_US.utf8' => 'statement',
      'ro_RO.utf8' => 'afirmatie',
    ],
    'statement/verdictReport' => [
      'en_US.utf8' => 'verdict-report',
      'ro_RO.utf8' => 'raport-verdicte',
    ],

    // tags
    'tag/edit' => [
      'en_US.utf8' => 'edit-tag',
      'ro_RO.utf8' => 'editeaza-eticheta',
    ],
    'tag/list' => [
      'en_US.utf8' => 'tags',
      'ro_RO.utf8' => 'etichete',
    ],
    'tag/load' => [
      'en_US.utf8' => 'ajax/load-tags',
    ],
    'tag/search' => [
      'en_US.utf8' => 'ajax/search-tags',
    ],
    'tag/view' => [
      'en_US.utf8' => 'tag',
      'ro_RO.utf8' => 'eticheta',
    ],

    // users
    'user/changeReputation' => [
      'en_US.utf8' => 'ajax/change-reputation',
    ],
    'user/edit' => [
      'en_US.utf8' => 'edit-user',
      'ro_RO.utf8' => 'editeaza-utilizator',
    ],
    'user/image' => [
      'en_US.utf8' => 'avatar',
    ],
    'user/view' => [
      'en_US.utf8' => 'user',
      'ro_RO.utf8' => 'utilizator',
    ],

    // votes
    'vote/save' => [
      'en_US.utf8' => 'ajax/save-vote',
    ],
  ];

  // file => list of parameters expected in the URL (none by default)
  const PARAMS = [
    'aggregate/search' => [ 'q' ],
    'answer/edit' => [ 'id' ],
    'answer/history' => [ 'id' ],
    'attachment/view' => [ 'id', 'fileName' ],
    'cannedResponse/edit' => [ 'id' ],
    'cannedResponse/history' => [ 'id' ],
    'comment/delete' => [ 'id' ],
    'domain/image' => [ 'id', 'fileName' ],
    'domain/edit' => [ 'id' ],
    'entity/edit' => [ 'id' ],
    'entity/history' => [ 'id' ],
    'entity/image' => [ 'id', 'fileName' ],
    'entity/load' => [ 'ids' ],
    'entity/search' => [ 'term' ],
    'entity/view' => [ 'id' ],
    'help/categoryEdit' => [ 'id' ],
    'help/index' => [ 'path' ],
    'help/pageEdit' => [ 'id' ],
    'help/pageHistory' => [ 'id' ],
    'relation/edit' => [ 'id', ],
    'review/view' => [ 'reason', 'reviewId', ],
    'statement/edit' => [ 'id' ],
    'statement/history' => [ 'id' ],
    'statement/view' => [ 'id', 'answerId' ],
    'tag/edit' => [ 'id' ],
    'tag/load' => [ 'ids' ],
    'tag/view' => [ 'id' ],
    'user/edit' => [ 'id' ],
    'user/image' => [ 'id', 'fileName' ],
    'user/view' => [ 'id', 'nickname' ],
  ];

  private static $fwdRoutes = [];
  private static $relAlternate = [];

  static function init() {
    // compute the forward routes, mapping localized URLs to PHP files
    foreach (self::ROUTES as $file => $locales) {
      foreach ($locales as $url) {
        self::$fwdRoutes[$url] = $file;
      }
    }
  }

  // Executes the corresponding PHP file for this request, then exits.
  // Returns null on routing errors.
  static function route($uri) {
    // strip the GET parameters
    $path = parse_url($uri, PHP_URL_PATH);

    $parts = explode('/', $path);
    $route = array_shift($parts);

    // the route may contain slashes, so try increasingly long segments
    while (!isset(self::$fwdRoutes[$route]) && !empty($parts)) {
      $route .= '/' . array_shift($parts);
    }

    if (isset(self::$fwdRoutes[$route])) {

      // get the PHP file
      $rec = self::$fwdRoutes[$route];
      $file = $rec . '.php';

      // save any alternate versions in case we need to print them in header tags
      self::setRelAlternate($route, $uri);

    } else {

      // fallback: look for a file by the same name under routes/
      $file = $path . '.php';
      $rec = '';

    }

    $absolute = Config::ROOT . 'routes/' . $file;
    if (file_exists($absolute)) {
      // Set additional params if the file expects them and the URL has them.
      // If there are more parts than params defined, then the last param
      // collects all remaining parts.
      $params = self::PARAMS[$rec] ?? [];

      while (($part = array_shift($parts)) &&
             ($param = array_shift($params))) {
        if (empty($params) && !empty($parts)) {
          $part .= '/' . implode('/', $parts);
        }
        $_REQUEST[$param] = urldecode($part);
      }

      Log::debug('routing %s to %s', $path, $file);

      require_once $absolute;
      exit;
    }

    Log::notice('no route found for %s', $path);
    return null;
  }

  // Returns a human-readable URL for this file.
  static function link($file, $absolute = false) {
    if (isset(self::ROUTES[$file])) {
      $routes = self::ROUTES[$file];
      $rel = $routes[LocaleUtil::getCurrent()]     // current locale
        ?? $routes[Config::DEFAULT_ROUTING_LOCALE] // or default locale
        ?? '';                                     // or home page
    } else {
      $rel = $file;
    }

    $url = ($absolute ? Config::URL_HOST : '') . Config::URL_PREFIX . $rel;
    return $url;
  }

  static function userLink($user) {
    return sprintf('%s/%s/%s',
                   self::link('user/view'),
                   $user->id,
                   urlencode($user->nickname));
  }

  // Collect URLs for localized versions of this page.
  // See https://support.google.com/webmasters/answer/189077
  static function setRelAlternate($route, $uri) {
    $routes = self::ROUTES[self::$fwdRoutes[$route]];

    if (count($routes) > 1) {
      foreach ($routes as $locale => $langRoute) {
        $langCode = explode('_', $locale)[0];
        $langUri = substr_replace($uri, $langRoute, 0, strlen($route));
        $langUrl = Config::URL_HOST . Config::URL_PREFIX . $langUri;
        self::$relAlternate[$langCode] = $langUrl;
      }
    }
  }

  static function getRelAlternate() {
    return self::$relAlternate;
  }

  /**
   * Returns the edit link for this object.
   *
   * @param object $object
   * @return string The edit link for this object or null if the object
   * doesn't have an edit page.
   */
  static function getEditLink($object) {
    switch ($object->getObjectType()) {
      case Proto::TYPE_ANSWER:
        return Router::link('answer/edit') . '/' . $object->id;
      case Proto::TYPE_DOMAIN:
        return Router::link('domain/edit') . '/' . $object->id;
      case Proto::TYPE_ENTITY:
        return Router::link('entity/edit') . '/' . $object->id;
      case Proto::TYPE_STATEMENT:
        return Router::link('statement/edit') . '/' . $object->id;
      default:
        return null;
    }
  }

  /**
   * Returns the view link for this object.
   *
   * @param object $object
   * @return string The view link for this object or null if the object
   * doesn't have a view page.
   */
  static function getViewLink($object) {
    switch ($object->getObjectType()) {
      case Proto::TYPE_ENTITY:
        return Router::link('entity/view') . '/' . $object->id;
      case Proto::TYPE_STATEMENT:
        return Router::link('statement/view') . '/' . $object->id;
      default:
        return null;
    }
  }

  /**
   * Returns a link to a help category or page.
   *
   * @param object $object A HelpCategory or HelpPage object.
   */
  static function helpLink($object) {
    return self::link('help/index') . '/' . $object->path;
  }

}
