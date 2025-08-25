<?php

require_once 'third-party/smarty-4.5.5/Smarty.class.php';

class Smart {
  private static $theSmarty = null;
  private static $cssFiles = [];
  private static $jsFiles = [];
  private static $includedResources = [];

  const RESOURCE_MAP = [
    'i18n' => [
      'js' => [
        'locale/%l.js',
        'i18n.js',
      ],
    ],
    'jquery' => [
      'js' => [ 'third-party/jquery-3.3.1.min.js' ],
    ],
    'select2' => [
      'css' => [
        'third-party/select2.min.css',
        'third-party/select2-bootstrap-5-theme.min.css',
      ],
      'js' => [
        'third-party/select2/select2.min.js',
        'select2/i18n/ro.js',
        'select2/select2Dev.js',
      ],
    ],
    'bootstrap' => [
      'css' => [
        'third-party/bootstrap.min.css',
        'third-party/bootstrap-diff.css',
      ],
      'js' => [ 'third-party/bootstrap.bundle.min.js' ],
      'deps' => [ 'select2' ], /* because our themes include Select2 overrides */
    ],
    'bootstrap-select' => [
      'css' => [ 'third-party/bootstrap-select.min.css' ],
      'js' => [
        'third-party/bootstrap-select/bootstrap-select.min.js',
        'third-party/bootstrap-select/i18n/defaults-%l.js',
      ],
      'deps' => [ 'bootstrap' ],
    ],
    'cookie-consent' => [
      'css' => [ 'third-party/silktide-consent-manager.css' ],
      'js' => [ 'third-party/silktide-consent-manager.js', ],
      'deps' => [ 'jquery' ],
    ],
    'main' => [
      'css' => [ 'main.css', 'fonts.css' ],
      'js' => [ 'main.js' ],
      'deps' => ['i18n', 'jquery', 'bootstrap', 'select2' ],
    ],
    'codemirror' => [
      'css' => [
        'third-party/codemirror-5.58.1/codemirror.css',
        'third-party/codemirror-5.58.1/addon/display/fullscreen.css',
        'third-party/codemirror-5.58.1/addon/hint/show-hint.css',
      ],
      'js' => [
        'third-party/codemirror-5.58.1/codemirror.js',
        'third-party/codemirror-5.58.1/addon/display/fullscreen.js',
        'third-party/codemirror-5.58.1/addon/display/placeholder.js',
        'third-party/codemirror-5.58.1/addon/edit/continuelist.js',
        'third-party/codemirror-5.58.1/addon/hint/show-hint.js',
        'third-party/codemirror-5.58.1/addon/mode/overlay.js',
        'third-party/codemirror-5.58.1/addon/search/searchcursor.js',
        'third-party/codemirror-5.58.1/addon/selection/mark-selection.js',
        'third-party/codemirror-5.58.1/mode/css/css.js',
        'third-party/codemirror-5.58.1/mode/gfm/gfm.js',
        'third-party/codemirror-5.58.1/mode/htmlmixed/htmlmixed.js',
        'third-party/codemirror-5.58.1/mode/javascript/javascript.js',
        'third-party/codemirror-5.58.1/mode/markdown/markdown.js',
        'third-party/codemirror-5.58.1/mode/xml/xml.js',
      ],
    ],
    'datepicker' => [
      'js' => [
        'datepicker/intl/%l.js',
        'datepicker/datepicker.js',
      ],
      'deps' => [ 'bootstrap'],
    ],
    'easymde' => [
      'css' => [ 'third-party/easymde-2.11.0.css' ],
      'js' => [
        'third-party/marked.js',
        'third-party/easymde-2.11.0/easymde.js',
        'third-party/easymde-2.11.0/codemirror/tablist.js',
        'third-party/codemirror-4.inline-attachment.min.js',
        'easyMdeDev.js',
      ],
      'deps' => [ 'codemirror'],
    ],
    'sortable' => [
      'js' => [ 'third-party/Sortable.min.js' ],
    ],
    'flag' => [
      'js' => [ 'flag.js' ],
    ],
    'history' => [
      'css' => [ 'history.css' ],
      'js' => [ 'history.js' ],
    ],
    'imageModal' => [
      'css' => [ 'imageModal.css' ],
      'js' => [ 'imageModal.js' ],
    ],
    'subscribe' => [
      'js' => [ 'subscribe.js' ],
    ],
    'linkEditor' => [
      'js' => [ 'linkEditor.js' ],
      'deps' => [ 'sortable'],
    ],
    'pagination' => [
      'js' => [ 'pagination.js' ],
    ],
    'answerResources' => [
      'css' => [ 'answerResources.css' ],
      'js' => [ 'answerResources.js' ],
    ],
  ];

  static function init() {
    $s = new Smarty();
    $s->template_dir = Config::ROOT . 'templates';
    $s->compile_dir = Config::TMP_DIR . 'templates_c';
    $s->addPluginsDir(__DIR__ . '/smarty-plugins');
    $s->registerPlugin('modifier', 'esc', 'Str::htmlEscape');
    $s->registerPlugin('modifier', 'floor', 'floor');
    $s->registerPlugin('modifier', 'implode', 'implode');
    $s->registerPlugin('modifier', 'ld', 'Time::localDate');
    $s->registerPlugin('modifier', 'lt', 'Time::localTimestamp');
    $s->registerPlugin('modifier', 'md', 'Markdown::convert');
    $s->registerPlugin('modifier', 'moment', 'Time::moment');
    $s->registerPlugin('modifier', 'nf', 'Str::formatNumber');
    $s->registerPlugin('modifier', 'shorten', 'Str::shorten');
    self::$theSmarty = $s;
    self::registerStaticClasses();
  }

  // Define classes from which we can use {Class::CONSTANT} in Smarty.
  static function registerStaticClasses(): void {
    $registeredClasses = [
      'Ban', 'CannedResponse', 'Comment', 'Config', 'Ct', 'Entity', 'Flag',
      'HelpCategory', 'Involvement', 'LocaleUtil', 'Notification', 'ORM',
      'Proto', 'Region', 'RelationType', 'Review', 'Router', 'Statement',
      'Subscription', 'Tag', 'User', 'Util', 'Vote',
    ];
    foreach ($registeredClasses as $class) {
      self::$theSmarty->registerClass($class, $class);
    }
  }

  // Add $template.css and $template.js to the file lists, if they exist.
  static function addSameNameFiles($template) {
    $baseName = str_replace('.tpl', '', $template);

    // Add {$template}.css if the file exists
    $cssFile = "autoload/{$baseName}.css";
    $fileName = Config::ROOT . 'www/css/' . $cssFile;
    if (file_exists($fileName)) {
      self::$cssFiles[] = $cssFile;
    }

    // Add {$template}.js if the file exists
    $jsFile = "autoload/{$baseName}.js";
    $fileName = Config::ROOT . 'www/js/' . $jsFile;
    if (file_exists($fileName)) {
      self::$jsFiles[] = $jsFile;
    }
  }

  // Returns lists of css and js files to include. Selects CSS and JS files
  // from the included resources and RESOURCE_MAP and adds self::$cssFiles
  // and self::$jsFiles at the end.
  static function orderResources() {
    // first add all dependencies
    $map = [];
    while ($key = array_pop(self::$includedResources)) {
      $map[$key] = true;
      $deps = self::RESOURCE_MAP[$key]['deps'] ?? [];
      foreach ($deps as $dep) {
        if (!isset($map[$dep])) {
          self::$includedResources[] = $dep;
        }
      }
    }

    // now collect CSS and JS files in map order
    $resultCss = [];
    $resultJs = [];
    foreach (self::RESOURCE_MAP as $key => $data) {
      if (isset($map[$key])) {
        $list = $data['css'] ?? [];
        foreach ($list as $css) {
          $resultCss[] = $css;
        }

        $list = $data['js'] ?? [];
        foreach ($list as $js) {
          $resultJs[] = $js;
        }
      }
    }

    // finally, append $cssFiles and $jsFiles
    $resultCss = array_merge($resultCss, self::$cssFiles);
    $resultJs = array_merge($resultJs, self::$jsFiles);
    return [ $resultCss, $resultJs ];
  }

  static function mergeResources($files, $type) {
    // %L and %l will be substituted by the long/short locale name
    $longLocale = LocaleUtil::getCurrent();
    $shortLocale = LocaleUtil::getShort();

    // compute the full file names and get the latest timestamp
    $full = [];
    $maxTimestamp = 0;
    foreach ($files as $file) {
      $orig = $file;
      $file = str_replace('%L', $longLocale, $file);
      $file = str_replace('%l', $shortLocale, $file);
      $hasLocale = ($file != $orig);

      if (!Str::startsWith($file, '/')) {
        // don't touch paths which are already absolute
        $file = sprintf('%swww/%s/%s', Config::ROOT, $type, $file);
      }

      // complain about missing files, unless they are localizations
      if (!$hasLocale || file_exists($file)) {
        $full[] = $file;
        $timestamp = filemtime($file);
        $maxTimestamp = max($maxTimestamp, $timestamp);
      }
    }

    // compute the output file name
    $hash = md5(implode(',', $full));
    $outputDir = sprintf('%swww/%s/merged/', Config::ROOT, $type);
    $output = sprintf('%s%s.%s', $outputDir, $hash, $type);

    // generate the output file if it doesn't exist or if it's too old
    if (!file_exists($output) || (filemtime($output) < $maxTimestamp)) {
      $tmpFile = tempnam(Config::TMP_DIR, 'merge_');
      foreach ($full as $f) {
        $contents = file_get_contents($f);
        if ($type == 'css') {
          // replace image references
          $contents = preg_replace_callback(
            '/url\([\'"]?([^\'")]+)[\'"]?\)/',
            function($match) use ($f, $outputDir) {
              return self::convertImages($f, $outputDir, $match[1]);
            },
            $contents);
        }
        file_put_contents($tmpFile,  $contents . "\n", FILE_APPEND);
      }
      rename($tmpFile, $output);
      chmod($output, 0666);
    }

    // return the URL path and the timestamp
    $path = sprintf('%s%s/merged/%s.%s', Config::URL_PREFIX, $type, $hash, $type);
    $date = date('YmdHis', filemtime($output));
    return [
      'path' => $path,
      'date' => $date,
    ];
  }

  // Copy an image file and return a reference to it.
  // Assumes that $cssFile is being moved to $outputDir.
  // $url is the contents between parentheses in "url(...)"
  static function convertImages($cssFile, $outputDir, $url) {
    // only handle third-party files; do nothing for data URIs, fonts, own CSS files etc.
    if ((strpos($cssFile, 'third-party') !== false) &&
        !Str::startsWith($url, 'data:')){

      // trim and save the anchor
      $parts = preg_split('/([#?])/', $url, 2, PREG_SPLIT_DELIM_CAPTURE);
      if (count($parts) > 1) {
        $url = $parts[0];
        $anchor = $parts[1] . $parts[2];
      } else {
        $anchor = '';
      }

      // get the absolute and relative source image filename
      $absSrcImage = realpath(dirname($cssFile) . '/' . $url);
      $relImage = basename($absSrcImage);

      // get the relative and absolute destination directory
      $basename = basename($cssFile, '.custom.min.css');
      $basename = basename($basename, '.min.css');
      $basename = basename($basename, '.css');
      $relImageDir = $basename . '/';
      $absImageDir = $outputDir . $relImageDir;

      // get the relative and absolute image target filename
      $relDestImage = $relImageDir . $relImage;
      $absDestImage = $absImageDir . $relImage;

      if (!file_exists($absDestImage)) {
        @mkdir($absImageDir);
        copy($absSrcImage, $absDestImage);
      }
      $url = $relDestImage . $anchor;
    }
    return "url($url)";
  }

  // Marks required CSS and JS files for inclusion.
  // $keys: array of keys in self::RESOURCE_MAP
  static function addResources(...$keys) {
    foreach ($keys as $key) {
      if (!isset(self::RESOURCE_MAP[$key])) {
        Snackbar::add("Unknown resource ID {$key}");
        Util::redirectToHome();
      }
      self::$includedResources[] = $key;
    }
  }

  /**
   * Registers the underlying file of a static resource for inclusion on the
   * current page.
   *
   * @param StaticResource $sr A static resource with a .css or .js extension.
   */
  static function addStaticResource($sr) {
    if (!$sr) {
      return;
    }
    $path = $sr->getFilePath();
    if (Str::endsWith($path, '.css')) {
      self::$cssFiles[] = $path;
    } else {
      self::$jsFiles[] = $path;
    }
  }

  /**
   * Can be called as
   * assign($name, $value) or
   * assign([$name1 => $value1, $name2 => $value2, ...])
   **/
  static function assign($arg1, $arg2 = null) {
    if (is_array($arg1)) {
      foreach ($arg1 as $name => $value) {
        self::$theSmarty->assign($name, $value);
      }
    } else {
      self::$theSmarty->assign($arg1, $arg2);
    }
  }

  /* Prepare and display a template. */
  static function display($templateName) {
    if (Config::GOOGLE_TAG_MANAGER_ID) {
      self::addResources('cookie-consent');
    }
    self::addResources('main');
    self::addSameNameFiles($templateName);
    print self::fetch($templateName);
  }

  static function displayWithoutSkin($templateName) {
    print self::fetch($templateName);
  }

  static function fetch($templateName) {
    list ($cssFiles, $jsFiles) = self::orderResources();
    self::assign([
      'copyrightYear' => date('Y'),
      'cssFile' => self::mergeResources($cssFiles, 'css'),
      'jsFile' => self::mergeResources($jsFiles, 'js'),
      'snackbars' => Snackbar::getAll(),
    ]);
    return self::$theSmarty->fetch($templateName);
  }
}
