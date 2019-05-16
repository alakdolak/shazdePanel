/**
 * @author zhixin wen <wenzhixin2010@gmail.com>
 * extensions: https://github.com/vitalets/x-editable
 */

var counter = 0;

(function($) {

    'use strict';

    $.extend($.fn.bootstrapTable.defaults, {
        editable: true,
        onEditableInit: function() {
            return false;
        },
        onEditableSave: function(field, row, oldValue, $el) {
            return false;
        },
        onEditableShown: function(field, row, $el, editable) {
            return false;
        },
        onEditableHidden: function(field, row, $el, reason) {
            return false;
        }
    });

    $.extend($.fn.bootstrapTable.Constructor.EVENTS, {
        'editable-init.bs.table': 'onEditableInit',
        'editable-save.bs.table': 'onEditableSave',
        'editable-shown.bs.table': 'onEditableShown',
        'editable-hidden.bs.table': 'onEditableHidden'
    });

    var BootstrapTable = $.fn.bootstrapTable.Constructor,
        _initTable = BootstrapTable.prototype.initTable,
        _initBody = BootstrapTable.prototype.initBody;


    BootstrapTable.prototype.initTable = function() {
        var that = this;
        _initTable.apply(this, Array.prototype.slice.apply(arguments));

        if (!this.options.editable) {
            return;
        }

        $.each(this.columns, function(i, column) {
            if (!column.editable) {
                return;
            }

            var editableOptions = {},
                editableDataMarkup = [],
                editableDataPrefix = 'editable-';

            var processDataOptions = function(key, value) {
                // Replace camel case with dashes.
                var dashKey = key.replace(/([A-Z])/g, function($1) {
                    return "-" + $1.toLowerCase();
                });
                if (dashKey.slice(0, editableDataPrefix.length) == editableDataPrefix) {
                    var dataKey = dashKey.replace(editableDataPrefix, 'data-');
                    editableOptions[dataKey] = value;
                }
            };

            $.each(that.options, processDataOptions);

            column.formatter = column.formatter || function(value, row, index) {
                return value;
            };
            column._formatter = column._formatter ? column._formatter : column.formatter;

            column.formatter = function(value, row, index) {
                var result = column._formatter ? column._formatter(value, row, index) : value;

                $.each(column, processDataOptions);

                $.each(editableOptions, function(key, value) {
                    editableDataMarkup.push(' ' + key + '="' + value + '"');
                });

                var _dont_edit_formatter = false;
                if (column.editable.hasOwnProperty('noeditFormatter')) {
                    _dont_edit_formatter = column.editable.noeditFormatter(value, row, index);
                }

                if (_dont_edit_formatter === false) {

                    if("type" in column && column["type"] == "select") {

                        var options = column["options"];

                        var newElement = '<select id="' + counter + '_' + column.field + '" onchange="handleChangeSelect(' + row['id'] + ', ' + row['kindPlaceId'] + ', \'' + column.field + '\', ' + counter +')"' +
                            ' data-name="' + column.field + '"' +
                            ' data-pk="' + row[that.options.idField] + '"' +
                            ' data-value="' + result + '"' +
                             editableDataMarkup.join('') +
                        '>';

                        counter++;

                        for(var i = 0; i < options.length; i++) {
                            if (options[i].id == row[column.field])
                                newElement += "<option selected value='" + options[i].id + "'>" + options[i].name + "</option>";
                            else
                                newElement += "<option value='" + options[i].id + "'>" + options[i].name + "</option>";
                        }

                        newElement += '</select>';

                        return [newElement
                        ].join('');
                    }

                    else if("type" in column && column["type"] == "select2") {

                        var options2 = column["options"];

                        var newElement2 = '<select class="js-example-basic-single" id="' + counter + '_' + column.field + '" onchange="handleChangeSelect(' + row['id'] + ', ' + row['kindPlaceId'] + ', \'' + column.field + '\', ' + counter +')"' +
                            ' data-name="' + column.field + '"' +
                            ' data-pk="' + row[that.options.idField] + '"' +
                            ' data-value="' + result + '"' +
                            editableDataMarkup.join('') +
                            '>';

                        counter++;

                        for(var ii = 0; ii < options2.length; ii++) {

                            newElement2 += "<optgroup label='" + options2[ii].name + "'>";

                            for(var jj = 0; jj < options2[ii].nodes.length; jj++) {
                                if (options2[ii].nodes[jj].id == row[column.field])
                                    newElement2 += "<option selected value='" + options2[ii].nodes[jj].id + "'>" + options2[ii].nodes[jj].name + "</option>";
                                else
                                    newElement2 += "<option value='" + options2[ii].nodes[jj].id + "'>" + options2[ii].nodes[jj].name + "</option>";
                            }

                            newElement2 += "</optgroup>";
                        }

                        newElement2 += '</select>';

                        return [newElement2].join('');
                    }

                    else {
                        return ['<a style="cursor: pointer" onclick="handleClick(' + row['id'] + ', ' + row['kindPlaceId'] + ', \'' + column.field + '\')"',
                            ' data-name="' + column.field + '"',
                            ' data-pk="' + row[that.options.idField] + '"',
                            ' data-value="' + result + '"',
                            editableDataMarkup.join(''),
                            '>' + '</a>'
                        ].join('');
                    }
                } else {
                    return _dont_edit_formatter;
                }

            };
        });
    };

    BootstrapTable.prototype.initBody = function() {
        var that = this;
        _initBody.apply(this, Array.prototype.slice.apply(arguments));

        if (!this.options.editable) {
            return;
        }

        $.each(this.columns, function(i, column) {
            if (!column.editable) {
                return;
            }

            that.$body.find('a[data-name="' + column.field + '"]').editable(column.editable)
                .off('save').on('save', function(e, params) {
                    var data = that.getData(),
                        index = $(this).parents('tr[data-index]').data('index'),
                        row = data[index],
                        oldValue = row[column.field];

                    $(this).data('value', params.submitValue);
                    row[column.field] = params.submitValue;
                    that.trigger('editable-save', column.field, row, oldValue, $(this));
                    that.resetFooter();
                });
            that.$body.find('a[data-name="' + column.field + '"]').editable(column.editable)
                .off('shown').on('shown', function(e, editable) {
                    var data = that.getData(),
                        index = $(this).parents('tr[data-index]').data('index'),
                        row = data[index];

                    that.trigger('editable-shown', column.field, row, $(this), editable);
                });
            that.$body.find('a[data-name="' + column.field + '"]').editable(column.editable)
                .off('hidden').on('hidden', function(e, reason) {
                    var data = that.getData(),
                        index = $(this).parents('tr[data-index]').data('index'),
                        row = data[index];

                    that.trigger('editable-hidden', column.field, row, $(this), reason);
                });
        });
        this.trigger('editable-init');
    };

})(jQuery);