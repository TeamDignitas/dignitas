<?php

class HelpPage extends Proto {
  use MarkdownTrait;

  function getObjectType() {
    return Proto::TYPE_HELP_PAGE;
  }

  function getMarkdownFields() {
    return [ 'contents' ];
  }

  function getCategory() {
    return HelpCategory::get_by_id($this->categoryId);
  }

  /**
   * For newly created pages, assigns the next available rank. For existing
   * pages, does nothing.
   */
  function assignNewRank() {
    if (!$this->id) {
      $this->rank = 1 + HelpPage::count_by_categoryId($this->categoryId);
    }
  }

}
