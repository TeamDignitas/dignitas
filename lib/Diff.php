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
   */
  private static function pushCopy(&$ses) {
    self::pushCopyDelete($ses, self::OP_COPY);
  }

  /**
   * Pushes a delete operation onto the edit script.
   *
   * @param $ses Shortest edit script being built.
   */
  private static function pushDelete(&$ses) {
    self::pushCopyDelete($ses, self::OP_DELETE);
  }

  /**
   * Pushes a copy or delete operation onto the edit script.
   *
   * @param $ses Shortest edit script being built.
   * @param $op Operation to push (self::OP_COPY or self::OP_DELETE)
   */
  private static function pushCopyDelete(&$ses, $op) {
    $l = count($ses);
    if ($l && ($ses[$l - 1][0] == $op)) {
      $ses[$l - 1][1]++;
    } else {
      $ses[] = [ $op, 1 ];
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
    $d = -1;

    do {
      $d++;

      for ($k = -$d; $k <= $d; $k += 2) {

        $l = max(
          1 + ($v[$d - 1][$k - 1] ?? -self::INFINITY),
          $v[$d - 1][$k + 1] ?? -self::INFINITY
        );

        $c = $l - $k;

        while (($l < $la) &&
               ($c < $lb) &&
               ($a[$l] == $b[$c])) {
          $l++;
          $c++;
        }

        $v[$d][$k] = $l;
      }

    } while ($la != ($v[$d][$la - $lb] ?? -self::INFINITY));

    return $v;
  }

  /**
   * Returns a shortest edit script from $a to $b.
   *
   * @param array $a An array, presumably of text lines, but not necessarily.
   * @param array $b An array, presumably of text lines, but not necessarily.
   * @return One of the shortest edit scripts.
   */
  static function ses(array $a, array $b) {
    $v = self::buildFRP($a, $b);
    $ses = [];
    $l = count($a);
    $c = count($b);
    $d = array_key_last($v);
    $k = $l - $c;

    for (; $d; $d--) {
      while ($l && $c && ($a[$l - 1] == $b[$c - 1])) {
        $l--;
        $c--;
        self::pushCopy($ses);
      }

      if ($c && ($l == ($v[$d - 1][$k + 1] ?? -self::INFINITY))) {
        $c--;
        $k++;
        self::pushInsert($ses, $b[$c]);
      } else { // $l && ($v[$d][$k] == 1 + $v[$d - 1][$k - 1])
        $l--;
        $k--;
        self::pushDelete($ses);
      }
    }

    $ses = array_reverse($ses);

    print_r($ses);
  }
}

$a = [ 'A', 'B', 'C', 'A', 'B', 'B', 'A' ];
$b = [ 'C', 'B', 'A', 'B', 'A', 'C' ];

$a = ['a', 'b', 'c', 'd'];
$b = ['e', 'f', 'g', 'h'];

Diff::ses($a, $b);
