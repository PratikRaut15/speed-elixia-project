/**
 * Ajax.Request.abort
 * extend the prototype.js Ajax.Request object so that it supports an abort method
 */
Ajax.Request.prototype.abort = function () {
    // prevent and state change callbacks from being issued
    this.transport.onreadystatechange = Prototype.emptyFunction;
    // abort the XHR
    this.transport.abort();
    // update the request counter
    Ajax.activeRequestCount--;
};

var _servicelistamount = 0;
var _SAYTmatchList = new Array(); //Our core list of matches.. we don't change this, the Ajax call does.
var _SAYTControls = new Array();
var _SAYTIsStillSearching = false;
var _SAYTfilteredList = new Array(); // Our butchered list of matches based on further searches.
var _SAYTlastSearchString = "";
var _SAYTqueryPhrase = ""; // The original query used to get the match list
var _SAYTtype = ""; // The type of search we're performing right now'
var _SAYTActiveControl = ""; // The control currently performing a SAYT search. 1 at a time only.
var _SAYTCurrentRow = 0;
var _SAYTMaxRows = 0;
var _SAYTIgnoreKeyUp = false; // Prevent us from over-reacting to keyboard events
var _SAYTOverListDontKillIt = false; // Prevent us from killing the list if we click in the list.
var _SAYTAjax = null;
var _SAYTDelayId = -1;
var _SAYTSelected = false;
var _keycount = 0;
var _del_serviceid = new Array();
var ret1 = false;

function _SAYTgetMatches(textToMatchOn, listtype, respondFunction) {



    // Get the list of matching items (only using the 1st 3 chars,
    // calling the query only if the text to match on is
    // different than the original query
    //
    // then filter this list based on textToMatchOn, making the filtered list
    //
    // If the lastSearchString is longer than the textToMatchOn, rebuild the
    // filter list from the matchList
    //
    // Otherwise, filter the filter list by the new query.

    if (textToMatchOn.length < 2) {
        // If not enough
        $(_SAYTActiveControl).MatchingRecords();
        return;
    }
    if (_SAYTIsStillSearching == true) return;
    if (textToMatchOn.toUpperCase().substr(0, 2) == _SAYTqueryPhrase.toUpperCase().substr(0, 2)) {
        // Same basic Query.
        if (_SAYTlastSearchString.toUpperCase()) {
            if (textToMatchOn.toUpperCase().substr(0, _SAYTlastSearchString.length) == _SAYTlastSearchString.toUpperCase()) {
                // New text added. Filter
                _SAYTfilteredList = _SAYTgetFilterList(_SAYTfilteredList, textToMatchOn);
            } else {
                // New text changed. Filter
                _SAYTfilteredList = _SAYTgetFilterList(_SAYTmatchList, textToMatchOn);
            }
        } else {
            _SAYTfilteredList = _SAYTgetFilterList(_SAYTmatchList, textToMatchOn);
        }
        _SAYTlastSearchString = textToMatchOn;
        respondFunction(_SAYTfilteredList);
    } else {
        _SAYTlastSearchString = textToMatchOn;
        // Basic query has changed. Get new match list.

        _SAYTgetMatchList(textToMatchOn, listtype, respondFunction);

    }
    return;
}

function _SAYTgetFilterList(sourceList, query) {
    // We're building a sub-list from an original source.
    // this source could be a sub list or the main list, we don't care.

    var resultList = new Array();

    for (var i = 0, length = sourceList.length; i < length; i++) {
        var item = sourceList[i];
        var testtext = item.text.toUpperCase();

        if (testtext.indexOf(query.toUpperCase()) > -1) {
            resultList.push(item);
        }
    }

    return resultList;
}

function _SAYTAbortRequest() {
    if (_SAYTIsStillSearching) {
        _SAYTAjax.abort();
        _SAYTmatchList.clear();
        _SAYTIsStillSearching = false;
    }

}

function _SAYTgetMatchList(query, listtype, respondFunction) {
    // The Meat!. Perform the request to the web server.
    // Get the data for the type of list we've requested.
    _SAYTIsStillSearching = true;
    _SAYTqueryPhrase = query;
    try {
        if (query == "") {
            _SAYTmatchList.clear();
            _SAYTfilteredList = _SAYTgetFilterList(_SAYTmatchList, _SAYTlastSearchString);
            respondFunction(_SAYTfilteredList);
            return;
        }

        var params = "q=" + encodeURIComponent(query) + "&t=" + encodeURIComponent(listtype);
        //alert( params );
        $(_SAYTActiveControl + "_icon").hide();
        $(_SAYTActiveControl + "_progress").show();

        _SAYTAjax = new Ajax.Request('getListAjax.php', {
            parameters: params,
            onSuccess: function (transport) {
                $(_SAYTActiveControl + "_progress").hide();
                $(_SAYTActiveControl + "_icon").show();
                _SAYTIsStillSearching = false;
                var json;
                json = transport.responseText.evalJSON();


                if (json.length > 0) {
                    _SAYTmatchList = json;
                    //                    _SAYTfilteredList = _SAYTgetFilterList( _SAYTmatchList,_SAYTlastSearchString);

                    _SAYTfilteredList = _SAYTmatchList;
                    if (json.length == 1 && query == json[0].key) {
                        _SAYTSelectElementInternal(json[0]);
                    } else {
                        respondFunction(_SAYTfilteredList);
                    }
                } else {
                    _SAYTmatchList.clear();
                    _SAYTfilteredList = _SAYTgetFilterList(_SAYTmatchList, _SAYTlastSearchString);
                    respondFunction(_SAYTfilteredList);
                }
            },
            onFailure: function () {
                $(_SAYTActiveControl + "_progress").hide();
                $(_SAYTActiveControl + "_icon").show();
                _SAYTIsStillSearching = false;

                _SAYTmatchList.clear();
                _SAYTfilteredList = _SAYTmatchList.clone();
                respondFunction(_SAYTfilteredList);
            },
            onComplete: function () {
                if (Ajax.activeRequestCount < 0) {
                    Ajax.activeRequestCount = 0;
                }
            }
        });

    } catch (e) {
        _SAYTIsStillSearching = false;
        _SAYTmatchList.clear();
        _SAYTfilteredList = _SAYTgetFilterList(_SAYTmatchList, _SAYTlastSearchString);
        respondFunction(_SAYTfilteredList);
    }

    params = null;
}

function _SAYTsearchItem(event) {
    _SAYTSelected = false;
    if (_SAYTDelayId != -1) {

        window.clearTimeout(_SAYTDelayId);
    }
    var caller = event.findElement();
    _SAYTDelayId = _SAYTsearchItemInner.delay(.5, caller);

}

function _SAYTsearchItemInner(caller) {

    _SAYTDelayId = -1;
    // var caller = event.findElement();
    if (caller != null && caller != undefined) {
        if (_SAYTActiveControl == caller.id) // Only interested if it's the active control'
        {

            if (_SAYTIsActive(caller)) {
                var searchtext = $(caller).getValue();
                _SAYTgetMatches(searchtext, _SAYTtype, _SAYTcallback);
            }
            // If the control was deactivated, we've pressed a key.. reactivate it.

            reactivateSAYT(_SAYTActiveControl);

            caller = null;

        }
    }
}

function _SAYTcallback(results) {
    // Ajax calls are Asynchronous so we don't know when they will finish
    // When it's done, we run the call back.

    if (_SAYTSelected) return false;


    $(_SAYTActiveControl + "_searchresults").update("");
    var clipno = 0;
    // Build a list of results.
    if (results.length == 0 && _SAYTIsStillSearching == false) {
        $(_SAYTActiveControl).NoMatchingRecords();
        $(_SAYTActiveControl).focus(); //Hack to prevent lost focus.
        _SAYTCloseResults();
    }

    var onlytext = "";

    for (var i = 0, length = results.length; i < length; i++) {
        var item = results[i];

        var clip;
        var disptext = item.text;
        if (item.displaytext != undefined && item.displaytext != "") {
            disptext = item.displaytext;
        }

        onlytext = item.text;
        item.key = "";
        clip = '<div id="' + _SAYTActiveControl + '_result_' + clipno + '" rowno="' + clipno + '" rowid="' + item.id + '" rowtext="' + item.text + '" rowkey="' + item.key + '" onmouseover="_SAYTitemhighlight(this);" onmouseout="_SAYTitemunhighlight(this);" onclick="_SAYTSelectElement(this)" >' + disptext + '</div>';
        $(_SAYTActiveControl + "_searchresults").insert({
            bottom: clip
        });
        _SAYTMaxRows = clipno;
        clipno++;
        item = null;
    }

    if (clipno == 1 && onlytext == $(_SAYTActiveControl).getValue()) {
        // We only had one result come back and the text returned was a match for what was already there.
        _SAYTSelectElement($(_SAYTActiveControl + '_result_' + (clipno - 1)));
    } else {
        // Do we have results?
        if (clipno > 0) {
            $(_SAYTActiveControl + "_searchresults").up().style.zIndex = 2;
            $(_SAYTActiveControl + "_searchresults").show();
        }

        // Make sure the selected item is set properly.

        if (_SAYTCurrentRow > _SAYTMaxRows) {
            _SAYTCurrentRow = _SAYTMaxRows;
        }
        if (_SAYTCurrentRow < 0) {
            _SAYTCurrentRow = 0;
        }
        _SAYTitemhighlight(_SAYTgetSelectedElement());
    }

    if (results.length > 0) {
        $(_SAYTActiveControl).MatchingRecords();

    }
}

function _SAYTgetSelectedElement() {
    var key = _SAYTActiveControl + "_result_" + _SAYTCurrentRow;
    if ($(key) != null) {
        return $(key);
    } else {
        return null;
    }
}

function _SAYTitemunhighlight(element) {
    // Remove the highlight styling from this element.
    if ($(element) == null) return;

    $(element).removeClassName("highlight");

}

function _SAYTitemhighlight(element) {
    // Add the highligh styling to this element
    if ($(element) == null) return;
    $(element).addClassName("highlight");
    _SAYTCurrentRow = $(element).readAttribute("rowno");

}

function _SAYTkeyupcontrol(event) {
    var _SAYTIgnoreKeyUp = false;
    if (event != null && event != undefined) {
        if (event.keyCode == 9) {
            if (_keycount > 0) {
                if (_SAYTDelayId != -1) {
                    // If this id is set, we're rapid typing... possibly scanning.
                    _SAYTIgnoreKeyUp = true;

                }
            }

        }
        if (event.keyCode != 13) {
            _SAYTsearchItem(event);
        }



        if (_SAYTIgnoreKeyUp) {
            if (event.preventDefault != undefined) event.preventDefault();

        }
    }

    return _SAYTIgnoreKeyUp;

}

function _SAYTkeycontrol(event) {
    // As of IE7 and FF3, this works on both browsers, but this doesn't work on all
    // We're checking to see which key has been pressed and acting on the results

    _SAYTIgnoreKeyUp = false;
    if (event.keyCode == 40) {
        _SAYTIgnoreKeyUp = true;
        _SAYTDownPressed();
    } else if (event.keyCode == 38) {
        _SAYTIgnoreKeyUp = true;
        _SAYTUpPressed();
    } else if (event.keyCode == 27) {
        _SAYTIgnoreKeyUp = true;
        _SAYTEscPressed();
    } else if (event.keyCode == 13) {
        _SAYTIgnoreKeyUp = true;
        _SAYTSelectElement();
    } else if (event.keyCode == 45) // Insert.. doesn't work on the Mac'
    {
        _SAYTIgnoreKeyUp = true;
        _SAYTInsertPressed();
    } else if (event.keyCode == 9) {
        if (_keycount > 0) {
            if (_SAYTDelayId != -1) {
                // If this id is set, we're rapid typing... possibly scanning.
                _SAYTIgnoreKeyUp = true;
                _SAYTsearchItem(event);
                _SAYTCloseResults();
                dirtifySAYT();
            }
        }
    }

    if (_SAYTIgnoreKeyUp) {
        if (event.preventDefault != undefined) event.preventDefault();
    } else {
        _keycount++;
    }

    return _SAYTIgnoreKeyUp;

}

function _SAYTInsertPressed() {
    // Close the search if there.
    if (_SAYTfilteredList.length == 0) {
        // OK, no matches, you m ight want to add the record.
        $(_SAYTActiveControl).InsertNewRecords();
    }
}

function _SAYTEscPressed() {
    // Close the search if there.
    _SAYTCloseResults();
}

function _SAYTUpPressed() {
    // List up.
    _SAYTitemunhighlight(_SAYTgetSelectedElement());
    if (_SAYTCurrentRow > 0) {
        _SAYTCurrentRow--;
    }
    _SAYTitemhighlight(_SAYTgetSelectedElement());
    scrollIntoView(_SAYTgetSelectedElement());
}

function _SAYTDownPressed() {
    // List down.
    _SAYTitemunhighlight(_SAYTgetSelectedElement());
    if (_SAYTCurrentRow < _SAYTMaxRows) {
        _SAYTCurrentRow++;
    }
    _SAYTitemhighlight(_SAYTgetSelectedElement());
    scrollIntoView(_SAYTgetSelectedElement());
}

function _SAYTCloseResults() {
    // We're done. Close it.
    _SAYTOverListDontKillIt = false;
    if ($(_SAYTActiveControl + "_searchresults") != null) {
        $(_SAYTActiveControl + "_searchresults").hide();
        $(_SAYTActiveControl + "_searchresults").up().style.zIndex = 0;
    }
}

function _SAYTSelectElementInternal(jsonElement) {
    var rowid = jsonElement.id;
    var rowtext = jsonElement.text;
    var rowcode = jsonElement.key;
    if ($(_SAYTActiveControl) != null) {
        $(_SAYTActiveControl).ElementIsSelected(rowid, rowtext, rowcode);
    }
}

function _SAYTSelectElement(element) {
    // A decision has been made. Select the element.
    if (element == undefined) {
        // Not passed in.
        element = _SAYTgetSelectedElement();
    }

    if ($(element) != null) {
        var rowid = $(element).readAttribute('rowid');
        var rowtext = $(element).readAttribute('rowtext'); // $(element).firstChild.nodeValue;
        var rowcode = $(element).readAttribute('rowkey');
        _SAYTCloseResults();
        _SAYTSelected = true;
        // When we registered the control, we set this nice event.
        // this is our selected element callback.
        // We're telling the calling code to do something now.

        $(_SAYTActiveControl).ElementIsSelected(rowid, rowtext, rowcode);
        rowid = null;
        rowtext = null;
    }
}

function scrollIntoView(elem) {

}

function _SAYTIsActive(inputid) {
    return ($(inputid).readAttribute("IsSAYTActive") == 1);
}

function _SAYTIsLocked(inputid) {
    return ($(inputid).readAttribute("IsSAYTLocked") == 1);
}

function registerNonSAYT(inputid) {
    $(inputid).observe('focus', this._SAYTDeActivatecontrol.bindAsEventListener(this));
}

function registerSAYT(inputid, listtype, containerid, selectionCallback, nomatchCallback, matchCallback, insertCallback) {
    // Configure the component for SAYT
    // Add the required event handlers and methods.

    $(inputid).observe('keyup', this._SAYTkeyupcontrol.bindAsEventListener(this));
    $(inputid).observe('focus', this._SAYTactivatecontrol.bindAsEventListener(this));
    $(inputid).observe('blur', this._SAYTdeactivatecontrol.bindAsEventListener(this));
    $(inputid).observe('keydown', this._SAYTkeycontrol.bindAsEventListener(this));
    // Add out selection callback. It must have ID and Text parameters.
    $(inputid).ElementIsSelected = selectionCallback;
    $(containerid).style.zIndex = 1;

    if (nomatchCallback == undefined) {
        // The caller doesn't care about no matches.. but we still want to be able to call something.
        nomatchCallback = Prototype.emptyFunction;
    }

    if (matchCallback == undefined) {
        // The caller doesn't care about no matches.. but we still want to be able to call something.
        matchCallback = Prototype.emptyFunction;
    }

    if (insertCallback == undefined) {
        // The caller doesn't care about no matches.. but we still want to be able to call something.
        insertCallback = Prototype.emptyFunction;
    }

    $(inputid).NoMatchingRecords = nomatchCallback;
    $(inputid).MatchingRecords = matchCallback;
    $(inputid).InsertNewRecords = insertCallback;

    // Switch the control on.
    $(inputid).writeAttribute("IsSAYTActive", 1);
    $(inputid).writeAttribute("title", "Start typing to Search. Up & Down to choose, Enter to select.");
    // Add the Attribute
    $(inputid).writeAttribute('listtype', listtype);
    $(inputid).writeAttribute('autocomplete', 'off'); //Don't try to suggest entries.. it messes with the SAYT search.
    // Add the HTML now.
    var boxid = inputid + "_searchresults"
    var snippet = "<div id='" + boxid + "' class='searchresults' style='display: none; z-index:10;'>&nbsp;</div>"
    $(containerid).addClassName("searchbox");
    $(containerid).insert({
        top: snippet
    });
    var progress = "<img class='SAYTprogress' id='" + inputid + "_progress' src='../images/busytrans.gif' title='Fetching matches, please wait.' style='display:none;' />";
    $(inputid).insert({
        after: progress
    });
    var SAYTIcon = "<img class='SAYTicon' id='" + inputid + "_icon' src='../images/SAYTIcon.png' title='This is a SAYT box, Search As You Type' />";
    $(inputid).insert({
        after: SAYTIcon
    });
    // Make sure it doesn't trip up when the mouse selects another element
    $(boxid).observe('mouseover', this._SAYTOverList.bindAsEventListener(this));
    $(boxid).observe('mouseout', this._SAYTNotOverList.bindAsEventListener(this));

    // Now remember this control.
    _SAYTControls.push($(inputid));

    boxid = null;
    snippet = null;
    progress = null;
}

function deactivateSAYT(inputid) {
    // No more searching.
    // do this if you want to populate the search control without events firing
    if (_SAYTIsLocked(inputid) == 0) {
        $(inputid).writeAttribute("IsSAYTActive", 0);
    }
    _SAYTDoDeactivate(inputid);


    _keycount = 0;
}

function reactivateSAYT(inputid) {
    //Search please
    if (_SAYTIsLocked(inputid) == 0) {
        $(inputid).writeAttribute("IsSAYTActive", 1);
    }
    _keycount = 0;
}

function lockactivationSAYT(inputid) {
    // Sometimes becasue of stupid browser behaviour and event order,
    // we want to lock the activation and deactivation of the control
    // Once locked, the control will remain either activated or deactivated
    // until the lock is released.
    $(inputid).writeAttribute("IsSAYTLocked", 1);
}

function unlockactivationSAYT(inputid) {
    $(inputid).writeAttribute("IsSAYTLocked", 0);
}

function dirtifySAYT() {
    // We know that the current list is invalid..
    // possible because we've added a new record and we need to include it in our results.

    _SAYTmatchList = new Array();
    _SAYTfilteredList = new Array();
    _SAYTlastSearchString = "";
    _SAYTqueryPhrase = "";
    _keycount = 0;

}

function _SAYTDeActivatecontrol(event) {
    // Wake up and go to work.
    // This happens when you click into the control
    // It just sets it up for searching, nothing more.
    _SAYTOverListDontKillIt = false;
    _keycount = 0;
    _SAYTDeactivateAll(true);

}

function _SAYTactivatecontrol(event) {
    // Wake up and go to work.
    // This happens when you click into the control
    // It just sets it up for searching, nothing more.

    _SAYTDeactivateAll(true);
    _keycount = 0;
    if (_SAYTOverListDontKillIt == true) {
        // There's another control active here.
        return false;
    }
    var control = event.findElement();
    _SAYTOverListDontKillIt = false;
    _SAYTActiveControl = control.id;

    _SAYTDeactivateAll(true);

    _SAYTtype = $(control).readAttribute("listtype");
    document.defaultAction = false;
    reactivateSAYT(_SAYTActiveControl);
    control = null;
}

function _SAYTdeactivatecontrol(event, force) {
    // This happens when you leave the control.
    // Deactivates the list and enables the document's default keyboard action.
    if (force == undefined) {
        force = false;
    }
    var control = event.findElement();
    document.defaultAction = true;


    if (force == false && _SAYTOverListDontKillIt == true) {
        // Don't kill the list, we're hovering over it.'
    } else {
        _SAYTOverListDontKillIt = false;
        _SAYTDoDeactivate(control);
    }
    control = null;
}

function _SAYTDoDeactivate(control) {
    if ($(control.id + "_progress") != null) {
        $(control.id + "_progress").hide();
        _SAYTAbortRequest();
        dirtifySAYT();
        _SAYTCloseResults();
    }

}

function _SAYTDeactivateAll(force) {
    for (var i = 0, length = _SAYTControls.length; i < length; i++) {
        var item = _SAYTControls[i];
        _SAYTDoDeactivate(item, force);
        item = null;
    }
}

function _SAYTOverList(event) {
    _SAYTOverListDontKillIt = true;
}

function _SAYTNotOverList(event) {
    _SAYTOverListDontKillIt = false;
}

