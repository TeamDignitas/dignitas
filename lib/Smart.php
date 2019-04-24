<?php

require_once 'third-party/smarty-3.1.33/Smarty.class.php';

class Smart {
  private static $theSmarty = null;
  private static $cssFiles = [];
  private static $jsFiles = [];
  private static $includedResources = [];

  const RESOURCE_MAP = [
    'jquery' => [
      'js' => [ 'third-party/jquery-3.3.1.min.js' ],
    ],
    'bootstrap' => [
      'css' => [ 'third-party/bootstrap-4.2.1.min.css' ],
      'js' => [ 'third-party/bootstrap-4.2.1.min.js' ],
    ],
    'fontello' => [
      'css' => [ 'third-party/fontello/css/icons.css' ],
    ],
    'marked' => [
      'js' => [ 'third-party/marked-0.6.2.min.js' ],
    ],
    'select2' => [
      'css' => [
        'third-party/select2-4.0.5.min.css',
        'third-party/select2-bootstrap4.min.css',
      ],
      'js' => [
        'third-party/select2/select2-4.0.5.min.js',
        'third-party/select2/i18n/ro.js',
      ],
    ],
    'select2Dev' => [
      'js' => [ 'select2Dev.js' ],
      'deps' => [ 'select2' ],
    ],
    'sortable' => [
      'js' => [ 'third-party/Sortable.min.js' ],
    ],
    'main' => [
      'css' => [ 'main.css' ],
      'js' => [ 'main.js' ],
    ],
  ];

  static function init() {
    $s = new Smarty();
    $s->template_dir = Config::ROOT . 'templates';
    $s->compile_dir = Config::TMP_DIR . 'templates_c';
    $s->addPluginsDir(__DIR__ . '/smarty-plugins');
    $s->registerPlugin('modifier', 'ld', 'Util::localDate');
    $s->registerPlugin('modifier', 'lt', 'Util::localTimestamp');
    $s->registerPlugin('modifier', 'md', 'Str::markdown');
    $s->registerPlugin('modifier', 'moment', 'Util::moment');
    self::$theSmarty = $s;
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
    // compute the full file names and get the latest timestamp
    $full = [];
    $maxTimestamp = 0;
    foreach ($files as $file) {
      $name = sprintf('%swww/%s/%s', Config::ROOT, $type, $file);
      $full[] = $name;
      $timestamp = filemtime($name);
      $maxTimestamp = max($maxTimestamp, $timestamp);
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
        FlashMessage::add("Unknown resource ID {$key}");
        Util::redirectToHome();
      }
      self::$includedResources[] = $key;
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
    self::addResources('jquery', 'bootstrap', 'fontello', 'main');
    self::addSameNameFiles($templateName);
    print self::fetch($templateName);
  }

  static function displayWithoutSkin($templateName) {
    print self::fetch($templateName);
  }

  static function fetch($templateName) {
    list ($cssFiles, $jsFiles) = self::orderResources();
    self::assign([
      'cssFile' => self::mergeResources($cssFiles, 'css'),
      'jsFile' => self::mergeResources($jsFiles, 'js'),
      'flashMessages' => FlashMessage::getMessages(),
    ]);
    return self::$theSmarty->fetch($templateName);
  }
}
