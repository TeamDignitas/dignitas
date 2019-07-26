$(function() {
  /**
   * Customize SimpleMDE's toolbar in order to replace icon CSS classes and get
   * rid of the external dependency on Font Awesome.
   **/
  const SIMPLE_MDE_TOOLBAR = [
    {
      name: 'bold',
      action: SimpleMDE.toggleBold,
      className: 'icon icon-bold',
      title: 'Bold',
    }, {
      name: 'italic',
      action: SimpleMDE.toggleItalic,
      className: 'icon icon-italic',
      title: 'Italic',
    }, {
      name: 'heading',
      action: SimpleMDE.toggleHeadingSmaller,
      className: 'icon icon-header',
      title: 'Heading',
    }, '|', {
      name: 'quote',
      action: SimpleMDE.toggleBlockquote,
      className: 'icon icon-quote-left',
      title: 'Quote',
    }, {
      name: 'unordered-list',
      action: SimpleMDE.toggleUnorderedList,
      className: 'icon icon-list-bullet',
      title: 'Generic List',
    }, {
      name: 'ordered-list',
      action: SimpleMDE.toggleOrderedList,
      className: 'icon icon-list-numbered',
      title: 'Numbered List',
    }, '|', {
      name: 'link',
      action: SimpleMDE.drawLink,
      className: 'icon icon-link',
      title: 'Create Link',
    }, {
      name: 'image',
      action: SimpleMDE.drawImage,
      className: 'icon icon-picture',
      title: 'Insert Image',
    }, '|', {
      name: 'preview',
      action: SimpleMDE.togglePreview,
      className: 'icon icon-eye no-disable',
      title: 'Toggle Preview',
    }, {
      name: 'side-by-side',
      action: SimpleMDE.toggleSideBySide,
      className: 'icon icon-columns no-disable no-mobile',
      title: 'Toggle Side by Side',
    }, {
      name: 'fullscreen',
      action: SimpleMDE.toggleFullScreen,
      className: 'icon icon-resize-full-alt no-disable no-mobile',
      title: 'Toggle Fullscreen',
    }, '|', {
      name: 'guide',
      action: 'https://simplemde.com/markdown-guide',
      className: 'icon icon-help-circled',
      title: 'Markdown Guide',
    },
  ];

  window.initSimpleMde = function(elementId) {
    var simpleMde = new SimpleMDE({
      autoDownloadFontAwesome: false,
      element: document.getElementById(elementId),
      spellChecker: false,
      status: false,
      toolbar: SIMPLE_MDE_TOOLBAR,
    });

    // allow drag-and-drop file uploads, see
    // https://github.com/sparksuite/simplemde-markdown-editor/issues/328
    inlineAttachment.editors.codemirror4.attach(simpleMde.codemirror, {
      allowedTypes: UPLOAD_MIME_TYPES,
      onFileUploadError: handleAjaxError,
      onFileUploadResponse: handleAjaxSuccess,
      uploadUrl: URL_PREFIX + 'ajax/upload-attachment',
    });

    return simpleMde;
  }

  function handleAjaxError(xhr) {
    var jsonResponse;

    try {
      jsonResponse = JSON.parse(xhr.responseText);
      error = jsonResponse.error;
    } catch (e) {
      error = 'An error occurred while uploading the file. Possibly the file was too large.';
    }

    var text = this.editor.getValue().replace(this.lastValue, error);

    this.editor.setValue(text);
    return false;
  }

  function handleAjaxSuccess(xhr) {
    var result = JSON.parse(xhr.responseText);

    if (result) {
      // ditch the built-in urlText and compute the output value ourselves
      var text = this.editor.getValue().replace(this.lastValue, result.html);
      this.editor.setValue(text);
    }
    return false;
  }
});
