/**
 * Creator prototype for PO/RFQ Creator
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Javascript
 * @package Creator
 * @namespace Creator
 * @filesource
 */
/* ------------------------------ PO/RFQ Creator functions -----------------------------*/

/**
 * Constructor of prototype
 *
 * @param id_area string : id of area of creator
 * @param dir string : url to get row
 * @constructor
 *
 * @tags Creator
 * @source js\file_creator\Creator.js
 */
var Creator = function(id_area, dir) {
    this._AREA = id_area;
    this._URL = document.location.origin + dir;
    this._ROW_BLANK = null;
    this._ROW_COUNT = 0;
};

/**
 * Load and initialize creator
 *
 * @tags Creator
 * @source js\file_creator\Creator.js
 */
Creator.prototype.start = function() {
    $('#' + this._AREA).load(this._URL + '&EDITOR=1');
};

/**
 * Add a blank row
 *
 * Get row blank from corresponding URL in prototype variables
 *
 * @tags Creator
 * @source js\file_creator\Creator.js
 */
Creator.prototype.add_row_blank = function() {

    if(this._ROW_BLANK === null) {

        $.get(this._URL + '&GP=0&ROW=1', function(data){
            creator._ROW_BLANK = data;
            creator.add_row_blank();
        }, 'html');

    } else {

        $('#' + creator._AREA + ' .number_editor').before(this._ROW_BLANK.replace(/%ROW%/g, this._ROW_COUNT));
        this._ROW_COUNT++;
        this.set_count_form(this._ROW_COUNT);
    }
};

/**
 * Add a row for a specific Generic Part from a project ERV
 *
 * @param id_gp int id of generic part
 * @param ipc string ipc in ERV
 * @param qty int quantity requested
 * @param sti string subtask index
 *
 * @tags Creator
 * @source js\file_creator\Creator.js
 */
Creator.prototype.add_row_GP = function(id_gp, ipc, qty, sti) {

    ipc = encodeURIComponent(ipc);
    sti = encodeURIComponent(sti);

    var req = this._URL + '&GP=' + id_gp + '&ROW=0';

    ipc = (typeof ipc !== 'undefined') ? ipc : null;
    if(ipc !== null) req += '&IPC=' + ipc;

    qty = (typeof qty !== 'undefined') ? qty : null;
    if(qty !== null) req += '&QTY=' + qty;

    sti = (typeof sti !== 'undefined') ? sti : null;
    if(sti !== null) req += '&STI=' + sti;

    $.get(req, function(data) {
        $('#' + creator._AREA + ' .number_editor').before(data.replace(/%ROW%/g, creator._ROW_COUNT));
        creator._ROW_COUNT++;
        creator.set_count_form(creator._ROW_COUNT);
    }, 'html');

};

/**
 * Delete last row from Creator
 *
 * @tags Creator
 * @source js\file_creator\Creator.js
 */
Creator.prototype.delete_row = function() {

    if(this._ROW_COUNT > 0) {

        this._ROW_COUNT--;
        this.set_count_form(creator._ROW_COUNT);

        $('#' + this._AREA + ' #row-' + this._ROW_COUNT).remove();
        $('#' + this._AREA + ' #row-' + this._ROW_COUNT + '-bis').remove();
    }
};

/**
 * Toggle to new generic part mode for a row
 *
 * @param id_row int
 *
 * @tags Creator
 * @source js\file_creator\Creator.js
 */
Creator.prototype.switch_new_gp = function(id_row) {

    if( -1 < id_row && id_row < this._ROW_COUNT) {

        if($('#' + this._AREA + ' #' + id_row + '-select_part').val() > 0) {
            $('#' + this._AREA + ' #row-' + id_row + '-bis').css('display','none');
            $('#' + this._AREA + ' input[name="' + id_row + '-f_new_gp_name"]').prop('required', false);
            $('#' + this._AREA + ' input[name="' + id_row + '-f_new_gp_number"]').prop('required', false);

        } else {
            $('#' + this._AREA + ' #row-' + id_row + '-bis').css('display','block');
            $('#' + this._AREA + ' input[name="' + id_row + '-f_new_gp_name"]').prop('required', true);
            $('#' + this._AREA + ' input[name="' + id_row + '-f_new_gp_number"]').prop('required', true);
        }
    }
};

/**
 * Set number of row in form
 *
 * @param value int new value
 *
 * @tags Creator
 * @source js\file_creator\Creator.js
 */
Creator.prototype.set_count_form = function(value) {
    $('#' + creator._AREA + ' #f_count').val(value);
};

/**
 * post form of creator to create excel file
 *
 * @param id_download_area string id of area to but link
 * @param id_form string id of form to send
 * @param id_submit_button string id of submit button
 * @param url string url to send
 *
 * @deprecated No longer used by internal code and not recommended.
 * @tags Creator
 * @source js\file_creator\Creator.js
 */
Creator.prototype.post_form = function(id_download_area, id_form, id_submit_button, url) {

    // Attach a submit handler to the form
    $( "#" + id_form ).submit(function( event ) {

        // Stop form from submitting normally
        event.preventDefault();
        // Send the data using post
        var value = $('#' + id_form).serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: value,
            beforeSend: function () {
                $("input[name='f_g_add_receive']").prop('checked', false);
                $('#' + id_submit_button).hide();
                $("#" + id_download_area).html('<button class="button" type="button" disabled>Wait...</button>');
            },
            success: function (data) {
                // Put the results in a div
                var content = $(data);
                $("#" + id_download_area).empty().append(content);
                $('#' + id_submit_button).show();
            },
            error: function () {
                $('#' + id_submit_button).show();
            },
            complete: function () {
            }
        });
    });
};