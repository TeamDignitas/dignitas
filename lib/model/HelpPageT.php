<?php

class HelpPageT extends Proto {
  use MarkdownTrait;

  function getMarkdownFields() {
    return [ 'contents' ];
  }

}
