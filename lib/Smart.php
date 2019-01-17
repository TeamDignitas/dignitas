<?php

require_once Core::portable(__DIR__ . '/third-party/smarty-3.1.33/Smarty.class.php');

class Smart {
  private static $theSmarty = null;
  private static $cssFiles = [];
  private static $jsFiles = [];
  private static $includedCss = [];
  private static $includedJs = [];
  private static $cssMap = [
    'bootstrap' => [ 'third-party/bootstrap-4.2.1.min.css' ],
    'fontello' => [ 'third-party/fontello/css/icons.css' ],
  ];
  private static $jsMap = [
    'jquery' => [ 'third-party/jquery-3.3.1.min.js' ],
    'bootstrap' => [ 'third-party/bootstrap-4.2.1.min.js' ],
  ];

  static function init() {
    self::$theSmarty = new Smarty();
    self::$theSmarty->template_dir = Core::getRootPath() . 'templates';
    self::$theSmarty->compile_dir = Config::TMP_DIR . 'templates_c';
    self::$theSmarty->addPluginsDir(__DIR__ . '/smarty-plugins');
  }

  // Add $template.css and $template.js to the file lists, if they exist.
  static function addSameNameFiles($template) {
    $baseName = pathinfo($template)['filename'];

    // Add {$template}.css if the file exists
    $cssFile = "autoload/{$baseName}.css";
    $fileName = Core::getRootPath() . 'www/css/' . $cssFile;
    if (file_exists($fileName)) {
      self::$cssFiles[] = $cssFile;
    }

    // Add {$template}.js if the file exists
    $jsFile = "autoload/{$baseName}.js";
    $fileName = Core::getRootPath() . 'www/js/' . $jsFile;
    if (file_exists($fileName)) {
      self::$jsFiles[] = $jsFile;
    }
  }

  static function orderResources($mapping, $selected) {
    $result = [];
    foreach ($mapping as $name => $files) {
      if (isset($selected[$name])) {
        $result = array_merge($result, $files);
      }
    }
    return $result;
  }

  static function mergeResources($files, $type) {
    // Note the priorities. This allows files to be added in any order, regardless of dependencies
    // compute the full file names and get the latest timestamp
    $full = [];
    $maxTimestamp = 0;
    foreach ($files as $file) {
      $name = sprintf('%swww/%s/%s', Core::getRootPath(), $type, $file);
      $full[] = $name;
      $timestamp = filemtime($name);
      $maxTimestamp = max($maxTimestamp, $timestamp);
    }

    // compute the output file name
    $hash = md5(implode(',', $full));
    $outputDir = sprintf('%swww/%s/merged/', Core::getRootPath(), $type);
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
    $path = sprintf('%s%s/merged/%s.%s', Core::getWwwRoot(), $type, $hash, $type);
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

      // Copy the file to be safe. It could have changed, for example if we
      // added new icons to our icon font.
      @mkdir($absImageDir);
      copy($absSrcImage, $absDestImage);

      $url = $relDestImage . $anchor;
    }
    return "url($url)";
  }

  static function addCss(...$ids) {
    foreach ($ids as $id) {
      if (!isset(self::$cssMap[$id])) {
        die("Cannot load CSS file {$id}.");
      }
      self::$includedCss[$id] = true;
    }
  }

  static function addJs(...$ids) {
    foreach ($ids as $id) {
      if (!isset(self::$jsMap[$id])) {
        die("Cannot load JS script {$id}.");
      }
      self::$includedJs[$id] = true;
    }
  }

  static function assign($data) {
    foreach ($data as $name => $value) {
      self::$theSmarty->assign($name, $value);
    }
  }

  /* Prepare and display a template. */
  static function display($templateName) {
    self::addCss('bootstrap', 'fontello');
    self::addJs('jquery', 'bootstrap');
    self::addSameNameFiles($templateName);
    print self::fetch($templateName);
  }

  static function displayWithoutSkin($templateName) {
    print self::fetch($templateName);
  }

  static function fetch($templateName) {
    self::$cssFiles = array_merge(
      self::orderResources(self::$cssMap, self::$includedCss),
      self::$cssFiles
    );
    self::assign([
      'cssFile' => self::mergeResources(self::$cssFiles, 'css'),
    ]);

    self::$jsFiles = array_merge(
      self::orderResources(self::$jsMap, self::$includedJs),
      self::$jsFiles
    );
    self::assign([
      'jsFile' => self::mergeResources(self::$jsFiles, 'js'),
    ]);

    self::assign([
      'flashMessages' => FlashMessage::getMessages(),
      'wwwRoot' => Core::getWwwRoot(),
    ]);
    return self::$theSmarty->fetch($templateName);
  }
}
