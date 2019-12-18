<?php

/**
 * An O(ND) implementation of the diff algorithm. See
 * http://www.xmailserver.org/diff2.pdf
 */
class Diff {
  const INFINITY = 1000000000;

  const OP_COPY = 0;
  const OP_INSERT = 1;
  const OP_DELETE = 2;

  /**
   * Pushes an insert operation onto the edit script.
   *
   * @param $ses Shortest edit script being built.
   * @param $item Item to insert.
   */
  private static function pushInsert(&$ses, $item) {
    $l = count($ses);
    if ($l && ($ses[$l - 1][0] == self::OP_INSERT)) {
      array_unshift($ses[$l - 1][1], $item);
    } else {
      $ses[] = [ self::OP_INSERT, [ $item ] ];
    }
  }

  /**
   * Pushes a copy operation onto the edit script.
   *
   * @param $ses Shortest edit script being built.
   * @param $count Number of operations to push.
   */
  private static function pushCopy(&$ses, $count = 1) {
    self::pushCopyDelete($ses, self::OP_COPY, $count);
  }

  /**
   * Pushes a delete operation onto the edit script.
   *
   * @param $ses Shortest edit script being built.
   * @param $count Number of operations to push.
   */
  private static function pushDelete(&$ses, $count = 1) {
    self::pushCopyDelete($ses, self::OP_DELETE, $count);
  }

  /**
   * Pushes a copy or delete operation onto the edit script.
   *
   * @param $ses Shortest edit script being built.
   * @param $op Operation to push (self::OP_COPY or self::OP_DELETE)
   * @param $count Number of operations to push.
   */
  private static function pushCopyDelete(&$ses, $op, $count) {
    $l = count($ses);
    if ($l && ($ses[$l - 1][0] == $op)) {
      $ses[$l - 1][1] += $count;
    } else {
      $ses[] = [ $op, $count ];
    }
  }

  /**
   * Builds the furthest reaching paths from $a to $b. That is, builds a
   * matrix V such that V[d][k] is the furthest reaching path on the k-th
   * diagonal using d non-diagonal edges. Equivalently, if V[d][k] = l, then
   * the first l items of $a can be transformed to the first l-k items of $b
   * using exactly d non-copy operations, and no value larger than l has this
   * property.
   *
   * @return array The matrix V.
   */
  static function buildFRP($a, $b) {
    $la = count($a);
    $lb = count($b);
    $v[-1][-1] = -1;
    $v[-1][1] = 0;
    $d = -1;

    do {
      $d++;

      for ($k = -$d; $k <= $d; $k += 2) {

        $l = max(1 + $v[$d - 1][$k - 1], $v[$d - 1][$k + 1]);

        $c = $l - $k;

        while (($l < $la) &&
               ($c < $lb) &&
               ($a[$l] == $b[$c])) {
          $l++;
          $c++;
        }

        $v[$d][$k] = $l;
      }

      $v[$d][-$d - 2] = $v[$d][$d + 2] = -self::INFINITY;

    } while (($v[$d][$la - $lb] ?? -self::INFINITY) != $la);

    return $v;
  }

  /**
   * Returns a shortest edit script between two arrays of strings. The edit
   * script is an array of pairs of three kinds:
   * - (OP_COPY, <number of strings to copy>)
   * - (OP_DELETE, <number of strings to delete>)
   * - (OP_INSERT, <array of strings to insert>)
   *
   * @param string[] $a Strings to modify.
   * @param string[] $b Strings to obtain.
   * @return One of the shortest edit scripts.
   */
  static function ses(array $a, array $b) {
    $v = self::buildFRP($a, $b);

    // traverse the furthest reaching paths matrix backwards
    $ses = [];
    $l = count($a);
    $c = count($b);
    $k = $l - $c;

    for ($d = array_key_last($v); $d >= 0; $d--) {
      $top = 1 + $v[$d - 1][$k - 1];
      $left = $v[$d - 1][$k + 1];
      $max = max($left, $top);

      if ($max < $l) {
        // go diagonally to our parent
        self::pushCopy($ses, $l - $max);
        $l = $max;
        $c = $l - $k;
      }

      if ($d) {
        if ($top > $left) {
          // go up
          $l--;
          $k--;
          self::pushDelete($ses);
        } else {
          // go left
          $c--;
          $k++;
          self::pushInsert($ses, $b[$c]);
        }
      }
    }

    // reverse the array since we collected the operations in reverse
    $ses = array_reverse($ses);

    return $ses;
  }

  /**
   * Returns a shortest edit script between two strings.
   *
   * @param string $a String to modify.
   * @param string $b String to obtain.
   * @return One of the shortest edit scripts.
   */
  static function sesStr(string $a, string $b) {
    // explode strings to array of characters
    $va = Str::unicodeExplode($a);
    $vb = Str::unicodeExplode($b);

    $ses = self::ses($va, $vb);

    // implode character inserts to string inserts
    foreach ($ses as &$rec) {
      if ($rec[0] == self::OP_INSERT) {
        $rec[1] = implode($rec[1]);
      }
    }
    return $ses;
  }

  /**
   * Returns a two-level shortest edit script between two multiline
   * strings. We first compute a line-level SES, then compute a
   * character-level SES for every chunk of different lines. The edit script
   * is an array of triplets:
   *
   * - an edit script (possibly empty) between a chunk of different lines
   * - a chunk of text to copy, consisting of entire lines only
   * - the number of lines of the chunk above
   *
   * @param string $a String to modify.
   * @param string $b String to obtain.
   */
  static function sesText(string $a, string $b) {
    $va = explode("\n", trim($a));
    $vb = explode("\n", trim($b));

    $ses = self::ses($va, $vb);
    $ses[] = [ self::OP_COPY, 0 ]; // make sure every ins+del is followed by a copy

    $result = [];
    $i = 0; // position in $va
    $ins = $del = '';

    foreach ($ses as $rec) {
      switch ($rec[0]) {
        case self::OP_INSERT:
          $ins = implode("\n", $rec[1]);
          break;

        case self::OP_DELETE:
          $del = implode("\n", array_slice($va, $i, $rec[1]));
          $i += $rec[1];
          break;

        case self::OP_COPY:
          if ($rec[1] || $ins || $del) {
            $copy = implode("\n", array_slice($va, $i, $rec[1]));
            $i += $rec[1];

            $result[] = [
              'diff' => self::sesStr($del, $ins),
              'copy' => $copy,
              'copyLineCount' => $rec[1],
            ];
            $del = '';
            $ins = '';
          }
          break;
      }
    }

    return $result;
  }
}
