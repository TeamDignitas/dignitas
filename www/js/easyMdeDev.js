$(function() {
  /**
   * Customize EasyMDE's toolbar in order to replace icon CSS classes and get
   * rid of the external dependency on Font Awesome.
   **/
  const EASY_MDE_TOOLBAR = [
    {
      name: 'bold',
      action: EasyMDE.toggleBold,
      className: 'icon icon-bold',
      title: 'Bold',
    }, {
      name: 'italic',
      action: EasyMDE.toggleItalic,
      className: 'icon icon-italic',
      title: 'Italic',
    }, {
      name: 'heading',
      action: EasyMDE.toggleHeadingSmaller,
      className: 'icon icon-header',
      title: 'Heading',
    }, '|', {
      name: 'quote',
      action: EasyMDE.toggleBlockquote,
      className: 'icon icon-quote-left',
      title: 'Quote',
    }, {
      name: 'unordered-list',
      action: EasyMDE.toggleUnorderedList,
      className: 'icon icon-list-bullet',
      title: 'Generic List',
    }, {
      name: 'ordered-list',
      action: EasyMDE.toggleOrderedList,
      className: 'icon icon-list-numbered',
      title: 'Numbered List',
    }, '|', {
      name: 'link',
      action: EasyMDE.drawLink,
      className: 'icon icon-link',
      title: 'Create Link',
    }, {
      name: 'image',
      action: EasyMDE.drawImage,
      className: 'icon icon-picture',
      title: 'Insert Image',
    }, '|', {
      name: 'preview',
      action: EasyMDE.togglePreview,
      className: 'icon icon-eye no-disable',
      title: 'Toggle Preview',
    }, {
      name: 'side-by-side',
      action: EasyMDE.toggleSideBySide,
      className: 'icon icon-columns no-disable no-mobile',
      title: 'Toggle Side by Side',
    }, {
      name: 'fullscreen',
      action: EasyMDE.toggleFullScreen,
      className: 'icon icon-resize-full-alt no-disable no-mobile',
      title: 'Toggle Fullscreen',
    }, '|', {
      name: 'guide',
      action: 'https://simplemde.com/markdown-guide',
      className: 'icon icon-help-circled',
      title: 'Markdown Guide',
    },
  ];

  // initialize EasyMDE editors
  $('.simple-mde').each(function() {
    var textarea = this;

    var simpleMde = new EasyMDE({
      autoDownloadFontAwesome: false,
      element: textarea,
      forceSync: true, // so that the remaining chars value gets updated
      inputStyle: 'contenteditable',
      insertTexts: {
        link: ["[", "]()"], // insert []() for links, not [](http://)
      },
      nativeSpellcheck: true,
      spellChecker: false,
      status: false,
      toolbar: EASY_MDE_TOOLBAR,
    });

    simpleMde.codemirror.on('change', function() {
      // fire the change event which in turn updates the chars remaining info
      $(textarea).change();
    });

    // allow drag-and-drop file uploads, see
    // https://github.com/sparksuite/simplemde-markdown-editor/issues/328
    inlineAttachment.editors.codemirror4.attach(simpleMde.codemirror, {
      allowedTypes: UPLOAD_MIME_TYPES,
      onFileUploadError: handleAjaxError,
      onFileUploadResponse: handleAjaxSuccess,
      uploadUrl: URL_PREFIX + 'ajax/upload-attachment',
    });

    simpleMde.codemirror.on('change', unsavedChangesHandler);

    return simpleMde;
  });

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

      // Also move the cursor to the end of the inserted block. Otherwise it
      // would sit at the end of the text "![Uploading file...]()", whereas
      // our HTML is longer.
      var cm = this.editor.codeMirror;
      var delta = result.html.length - this.lastValue.length;
      // skip delta characters ahead
      cm.doc.setCursor(cm.findPosH(cm.doc.getCursor(), delta, 'char'));
    }
    return false;
  }

});
