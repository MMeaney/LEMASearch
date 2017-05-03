/*

Copyright 2010, Google Inc.
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

 * Redistributions of source code must retain the above copyright
notice, this list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above
copyright notice, this list of conditions and the following disclaimer
in the documentation and/or other materials provided with the
distribution.
 * Neither the name of Google Inc. nor the names of its
contributors may be used to endorse or promote products derived from
this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,           
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY           
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

 */

function ListFacet(div, config, options, selection) {
  this._div = div;
  this._config = config;
  if (!("invert" in this._config)) {
    this._config.invert = false;
  }

  this._options = options || {};
  if (!("sort" in this._options)) {
    this._options.sort = "name";
  }

  this._selection = selection || [];
  this._blankChoice = (config.selectBlank) ? { s : true, c : 0 } : null;
  this._errorChoice = (config.selectError) ? { s : true, c : 0 } : null;

  this._data = null;

  this._initializeUI();
  this._update();
}

ListFacet.reconstruct = function(div, uiState) {
  return new ListFacet(div, uiState.c, uiState.o, uiState.s);
};

ListFacet.prototype.dispose = function() {
};

ListFacet.prototype.reset = function() {
  this._selection = [];
  this._blankChoice = null;
  this._errorChoice = null;
};

ListFacet.prototype.getUIState = function() {
  var json = {
      c: this.getJSON(),
      o: this._options
  };

  json.s = json.c.selection;
  delete json.c.selection;

  return json;
};

ListFacet.prototype.getJSON = function() {
  var o = {
      type: "list",
      name: this._config.name,
      columnName: this._config.columnName,
      expression: this._config.expression,
      omitBlank: "omitBlank" in this._config ? this._config.omitBlank : false,
          omitError: "omitError" in this._config ? this._config.omitError : false,
              selection: [],
              selectBlank: this._blankChoice !== null && this._blankChoice.s,
              selectError: this._errorChoice !== null && this._errorChoice.s,
              invert: this._config.invert
  };
  for (var i = 0; i < this._selection.length; i++) {
    var choice = {
        v: cloneDeep(this._selection[i].v)
    };
    o.selection.push(choice);
  }
  return o;
};

ListFacet.prototype.hasSelection = function() {
  return this._selection.length > 0 || 
  (this._blankChoice !== null && this._blankChoice.s) || 
  (this._errorChoice !== null && this._errorChoice.s);
};

ListFacet.prototype.updateState = function(data) {
  this._data = data;

  if ("choices" in data) {
    var selection = [];
    var choices = data.choices;
    for (var i = 0; i < choices.length; i++) {
      var choice = choices[i];
      if (choice.s) {
        selection.push(choice);
      }
    }
    this._selection = selection;
    this._reSortChoices();

    this._blankChoice = data.blankChoice || null;
    this._errorChoice = data.errorChoice || null;
  }

  this._update();
};

ListFacet.prototype._reSortChoices = function() {
  this._data.choices.sort(this._options.sort == "name" ?
      function(a, b) {
    return a.v.l.toLowerCase().localeCompare(b.v.l.toLowerCase());
  } :
    function(a, b) {
    var c = b.c - a.c;
    return c !== 0 ? c : a.v.l.localeCompare(b.v.l);
  }
  );
};

ListFacet.prototype._initializeUI = function() {
  var self = this;

  var facet_id = this._div.attr("id");

  this._div.empty().show().html(
      '<div class="facet-title">' +
        '<div class="grid-layout layout-tightest layout-full"><table><tr>' +
          '<td width="1%"><a href="javascript:{}" title="Remove this facet" class="facet-title-remove" bind="removeButton"> </a></td>' +
          '<td>' +
            '<a href="javascript:{}" class="facet-choice-link" bind="resetButton">reset</a>' +
            '<a href="javascript:{}" class="facet-choice-link" bind="invertButton">invert</a>' +
            '<a href="javascript:{}" class="facet-choice-link" bind="changeButton">change</a>' +
            '<span bind="titleSpan"></span>' +
          '</td>' +
        '</tr></table></div>' +
      '</div>' +
      '<div class="facet-expression" bind="expressionDiv" title="Click to edit expression"></div>' +
      '<div class="facet-controls" bind="controlsDiv" style="display:none;">' +
        '<a bind="choiceCountContainer" class="action" href="javascript:{}"></a> ' +
        '<span class="facet-controls-sortControls" bind="sortGroup">Sort by: ' +
          '<a href="javascript:{}" bind="sortByNameLink">name</a>' +
          '<a href="javascript:{}" bind="sortByCountLink">count</a>' +
        '</span>' +
        '<button bind="clusterLink" class="facet-controls-button button">Cluster</button>' +
      '</div>' +
      '<div class="facet-body" bind="bodyDiv">' +
        '<div class="facet-body-inner" bind="bodyInnerDiv"></div>' +
      '</div>'
  );
  this._elmts = DOM.bind(this._div);

  this._elmts.titleSpan.text(this._config.name);
  this._elmts.changeButton.attr("title","Current Expression: " + this._config.expression).click(function() {
    self._elmts.expressionDiv.slideToggle(100, function() {
      if (self._elmts.expressionDiv.css("display") != "none") {
        self._editExpression();
      }
    });
  });
  this._elmts.expressionDiv.text(this._config.expression).hide().click(function() { self._editExpression(); });
  this._elmts.removeButton.click(function() { self._remove(); });
  this._elmts.resetButton.click(function() { self._reset(); });
  this._elmts.invertButton.click(function() { self._invert(); });

  this._elmts.choiceCountContainer.click(function() { self._copyChoices(); });
  this._elmts.sortByCountLink.click(function() {
    if (self._options.sort != "count") {
      self._options.sort = "count";
      self._reSortChoices();
      self._update(true);
    }
  });
  this._elmts.sortByNameLink.click(function() {
    if (self._options.sort != "name") {
      self._options.sort = "name";
      self._reSortChoices();
      self._update(true);
    }
  });

  this._elmts.clusterLink.click(function() { self._doEdit(); });
  if (this._config.expression != "value" && this._config.expression != "grel:value") {
    this._elmts.clusterLink.hide();
  }

  if (!("scroll" in this._options) || this._options.scroll) {
    this._elmts.bodyDiv.addClass("facet-body-scrollable");
    this._elmts.bodyDiv.resizable({
      minHeight: 30,
      handles: 's',
      stop: function(event, ui) {
        event.target.style.width = "auto"; // don't force the width
      }
    });
  }
};

ListFacet.prototype._copyChoices = function() {
  var self = this;
  var frame = DialogSystem.createDialog();
  frame.width("600px");

  var header = $('<div></div>').addClass("dialog-header").text("Facet Choices as Tab Separated Values").appendTo(frame);
  var body = $('<div></div>').addClass("dialog-body").appendTo(frame);
  var footer = $('<div></div>').addClass("dialog-footer").appendTo(frame);

  body.html('<textarea wrap="off" bind="textarea" style="display: block; width: 100%; height: 400px;" >		

