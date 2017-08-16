/**
 * Javascript Functions for website
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Javascript
 * @package Functions
 * @namespace Functions
 * @filesource
 */

/* ------------------------------ Filter functions -----------------------------*/

/**
 * function to filter a table from a text input area
 *
 * filter table row from #myFilterInput element in DOM
 *
 * @param size int number of columns
 * @param tablename string id of table
 *
 * @tags Javascript
 * @source js\functions.js
 */
function filter(size, tablename) {
    var input, filter, table, tr, td, i, isDisplay;
    input = document.getElementById("myFilterInput");
    filter = input.value.toUpperCase().replace(/-/g,'');
    table = document.getElementById(tablename);
    tr = table.getElementsByClassName("tr");
    for (i = 1; i < tr.length; i++) {
        isDisplay = false;
        for (j = 0; j < size; j++) {
            td = tr[i].getElementsByClassName("td")[j];
            if (td) {
                if (td.innerHTML.replace(/-/g,'').toUpperCase().indexOf(filter) > -1) {
                    isDisplay = true;
                }
            }
        }
        if (isDisplay) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

/**
 * function to filter a select from a text input area
 *
 * @param id_input string id of text input for filter
 * @param id_select string id of select to filter
 * @param class_element string class of element of select to display or not
 *
 * @tags Javascript
 * @source js\functions.js
 */
function filter_select(id_input, id_select, class_element) {
    var input, filter, select, option, i, j, isDisplay;
    input = document.getElementById(id_input);
    filter = input.value.toUpperCase().replace(/-/g,'');
    select = document.getElementById(id_select);
    select.value = select.firstElementChild.value;
    option = select.getElementsByClassName(class_element);
    for (i = 0; i < option.length; i++) {
        isDisplay = false;
        if (option[i].innerHTML.replace(/-/g,'').toUpperCase().indexOf(filter) > -1) {
            isDisplay = true;
        }
        if (isDisplay) {
            option[i].style.display = "";
        } else {
            option[i].style.display = "none";
        }
    }
}

/**
 * function to filter a select from an other select
 *
 * @param id_input string id of select input for filter
 * @param id_select string id of select to filter
 * @param class_element string class of element of select to display or not
 *
 * @tags Javascript
 * @source js\functions.js
 */
function filter_select_from_select(id_input, id_select, class_element) {
    var input, value, select, option, i, isDisplay;
    input = document.getElementById(id_input);
    value = input.value;
    select = document.getElementById(id_select);
    select.value = select.firstElementChild.value;
    option = select.getElementsByClassName(class_element);
    for (i = 0; i < option.length; i++) {
        isDisplay = false;
        //console.log("test value : " + value + " index of " + option[i].getAttribute('class'));
        if (option[i].getAttribute('class').indexOf(value) > -1) {
            isDisplay = true;
        }
        if (isDisplay) {
            option[i].style.display = "";
        } else {
            option[i].style.display = "none";
        }
    }
}

/**
 * function get element with AJAX to fill text input areas
 *
 * @param id_select string id of select input for filter
 * @param json_url string url to get json of data
 * @param input_edit_list array list of input to edit content
 *
 * @tags Javascript
 * @source js\functions.js
 */
function select_edit_inputs(id_select, json_url, input_edit_list) {
    json_url += $('#' + id_select).val();
    $.getJSON(json_url, function(data){
        $.each( data, function( key, val ) {
            if(input_edit_list[key] !== undefined && input_edit_list[key] !== null) {
                $('input[name="' + input_edit_list[key] + '"]').val(val);
            }
        });
    });
}

/* ------------------------------ Filter Prototype ----------------------------- */

/**
 * Constructor of prototype
 *
 * Filter permit to filter table with complex input parameters from different input.
 * This class provides a system to filter a table from input.
 * Filter works with two elements:
 * - CONTENT : String to use in Filter
 * - CLASS : value of element class to use in Filter
 *
 * @param size int column number of table
 * @param tablename string id of table element
 * @param filter_class_buttons string id of buttons for Filter
 * @constructor
 *
 * @tags Javascript
 * @source js\functions.js
 */
var Filter = function(size, tablename, filter_class_buttons){

    this._SIZE = size;
    this._TABLENAME = tablename;
    this._BUTTON_FILTERS = (Array.isArray(filter_class_buttons)) ? filter_class_buttons : null;
    this._FILTER_CLASS = null;
    this._FILTER_CONTENT = null;
};

/**
 * Set class current filter for Filter.
 *
 * Set the button to use as filter
 *
 * @param filter_class_index index of filter
 * @param filter_class_optional if you want to add a filter with this button as a select
 *
 * @tags Javascript
 * @source js\functions.js
 */
Filter.prototype.set_class_filter = function(filter_class_index, filter_class_optional){

    this._BUTTON_INDEX = filter_class_index;
    this._BUTTON_FILTERS.forEach(function(element, index) {
        document.getElementById(element).disabled = (index === filter_class_index);
    });

    this._FILTER_CLASS = [document.getElementById(this._BUTTON_FILTERS[this._BUTTON_INDEX]).value];

    if(filter_class_optional !== undefined) {
        if (Array.isArray(filter_class_optional)) {
            this._FILTER_CLASS.concat(filter_class_optional);
        } else {
            this._FILTER_CLASS.push(filter_class_optional);
        }
    }
};

/**
 * Set the text content for the filter.
 *
 * @param filter_content_id id of content to use for Filter
 *
 * @tags Javascript
 * @source js\functions.js
 */
Filter.prototype.set_content_filter = function(filter_content_id) {
    this._FILTER_CONTENT = document.getElementById(filter_content_id).value;
};

/**
 * Execute the filter on the table
 *
 * @tags Javascript
 * @source js\functions.js
 */
Filter.prototype.execute_filter = function() {
    var filter, table, tr, td, j, i, isDisplay_class, isDisplay_content;
    filter = (this._FILTER_CONTENT!==null)? this._FILTER_CONTENT.toUpperCase().replace(/-/g,'') : "";
    table = document.getElementById(this._TABLENAME);
    tr = table.getElementsByClassName("tr");
    for(i = 1; i < tr.length; i++) {
        isDisplay_class = true;
        isDisplay_content = false;

        for(j = 0; j < this._FILTER_CLASS.length; j++) {
            isDisplay_class = (isDisplay_class && tr[i].classList.contains(this._FILTER_CLASS[j]));
        }

        td = tr[i].getElementsByClassName("td");
        for(j = 0; j < this._SIZE; j++) {
            isDisplay_content = (isDisplay_content || td[j].innerHTML.replace(/-/g,'').toUpperCase().indexOf(filter) > -1);
        }

        if(isDisplay_content && isDisplay_class) {
            tr[i].style.display = "table-row";
        } else {
            tr[i].style.display = "none";
        }
    }
};

/* ------------------------------ Button functions ----------------------------- */

/**
 * Dropdown a button
 *
 * When the user clicks on the button,
 * toggle between hiding and showing the dropdown content
 *
 * @param str string id of dropdown button. ID = "myDropdown-" + str
 *
 * @tags Javascript
 * @source js\functions.js
 */
function dropdown(str) {
    document.getElementById("myDropdown-" + str).classList.toggle("show");
}

/**
 * Window onclick events function
 *
 * @param event
 *
 * @tags Javascript
 * @source js\functions.js
 */
window.onclick = function(event) {
    // Close the dropdown if the user clicks outside of it
    if (!event.target.matches('.dropbtn')) {

        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }

    // When the user clicks anywhere outside of the modal, close it
    if (event.target == modal) {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the warning-modal, close it
    if (event.target.ID == 'warning_modal') {
        warning_modal.style.display = "none";
    }
}

/**
 * Open sidenav
 *
 * @tags Javascript
 * @source js\functions.js
 */
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

/**
 * Close sidenav
 *
 * @tags Javascript
 * @source js\functions.js
 */
function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

/**
 * Scrolls to the selected menu item on the page
 *
 * @tags Javascript
 * @source js\functions.js
 */
$(function() {
    $('a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });
});

/**
 * Open an url in a new tab
 *
 * @param url
 *
 * @tags Javascript
 * @source js\functions.js
 */
function openInNewTab(url) {
    var win = window.open(url, '_blank');
    win.focus();
}

/* ------------------------------ Edit Row functions -----------------------------*/

/**
 * Edit a row and toggle element of class element
 *
 * @param id_row string
 *
 * @tags Javascript
 * @source js\functions.js
 */
function edit_row(id_row) {
    var row, i, elements;
    row = document.getElementById('row-'+id_row);
    elements = row.getElementsByClassName('element');

    for(i = 0; i < elements.length; i++) {
        if(elements[i].style.display === "none") {
            elements[i].style.display = "block";
        } else {
            elements[i].style.display = "none";
        }
    }
}

/* ------------------------------ MAJ Excel file (AJAX) -----------------------------*/

/**
 * Get url of excel file in a button.
 *
 * @param url_excel_generator string url of excel generator
 * @param id_target string id of target to put result
 *
 * @tags Javascript
 * @source js\functions.js
 */
function call_excel(url_excel_generator, id_target) {
    id_target = (typeof id_target === 'undefined')? 'excel_file' : id_target;
    document.getElementById(id_target).innerHTML = '<button class="button">Wait..</button>';

    xmlhttpGet (url_excel_generator, function(content) {
        console.log(content);
        document.getElementById(id_target).innerHTML = content.getElementsByTagName('a')[0].outerHTML;
    });
}

/* -------------------- Function to send form -------------------- */

/**
 * Transform a request from http protocol to https protocol
 *
 * @param URL string URL to transform
 * @returns {void|string|XML} new URL transformed
 *
 * @tags Javascript
 * @source js\functions.js
 */
function urlHttpToHttps(URL) {
    return URL.replace(/^http:\/\//i, 'https://');
}

/**
 * Post a form to URL.
 *
 * Works with AJAX and get result to set or appen in a DOM element
 *
 * @param URL string url to post
 * @param formname string id of form to send
 * @param resultdiv string element where to display result
 * @param waitcontent html content to put in resultdiv during loading
 * @param appen bool appen or set content of div ? (default set)
 *
 * @tags Javascript
 * @source js\functions.js
 */
function xmlhttpPost(URL, formname, resultdiv, waitcontent, appen) {
    var self = this;
    var queryData = new FormData(document.forms[formname]);
    self.appen = (typeof appen !== 'undefined') ?  appen : false;

    if(window.location.protocol === 'https:')
        URL = urlHttpToHttps(URL);

    if(!self.appen) {
        updatepage("", resultdiv);
    }
    // Xhr for Mozilla/Chrome/Safari/Ie7
    if (window.XMLHttpRequest) {
        self.xmlHttpReq = new XMLHttpRequest();
    }
    else {
        throw new Error('Deprecated Web Browser');
    }
    self.xmlHttpReq.open('POST', URL, true);
    self.xmlHttpReq.onreadystatechange = function() {
        if (self.xmlHttpReq.readyState == 4) {
            if(self.appen) {
                appenpage(self.xmlHttpReq.responseText, resultdiv);
            } else {
                updatepage(self.xmlHttpReq.responseText, resultdiv);
            }
        } else {
            if(!self.appen) {
                updatepage(waitcontent, resultdiv);
            }
        }
    };
    self.xmlHttpReq.send(queryData);
}

/**
 * Set content of a DOM element
 *
 * @param content string content to set
 * @param resultdiv string id of element to set content
 */
function updatepage(content, resultdiv) {
    document.getElementById(resultdiv).innerHTML = content;
}

/**
 * Appen content in a DOM element
 *
 * @param content string content to appen
 * @param resultdiv string id of element where to appen content
 */
function appenpage(content, resultdiv) {
    document.getElementById(resultdiv).innerHTML += content;
}

/**
 * Send request to URL and do something with result
 *
 * Call Back parameters : callback(XML content)
 *
 * @param URL string url to send request
 * @param callback callback to execute when receive result
 */
function xmlhttpGet (URL, callback) {
    var self = this;

    if(window.location.protocol === 'https:')
        URL = urlHttpToHttps(URL);

    if(window.XMLHttpRequest) {
        self.xmlHttpReq = new XMLHttpRequest();
    } else {
        throw new Error('Deprecated Web Browser');
    }
    self.xmlHttpReq.open('GET', URL, true);
    self.xmlHttpReq.onreadystatechange = function () {
        if (self.xmlHttpReq.readyState == 4) {
            callback(self.xmlHttpReq.responseXML);
        }
    };
    self.xmlHttpReq.responseType = 'document';
    self.xmlHttpReq.send();
}

/* -------------------- Advanced Functions to filter -------------------- */

