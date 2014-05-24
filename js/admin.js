jQuery(document).ready(function($){


  var redirectsTable, tableRow, addRow, ID;


  redirectsTable = $('.gj-redirects > tbody:last');
  addRow = $('.addRow');


  function createRow() {
    addRow.click(function() {

      ID = $('.gj-redirects > tbody:last').children('tr').last().data('id') + 1;

      tableRow = [
        '<tr id="redirect-' + ID + '" class="alternate" data-id="' + ID + '">',
          '<input type="hidden" name="' + ID + '[]" value="' + ID + '">',
          '<input type="hidden" name="' + ID + '[]" value="create">',
          '<th class="check-column">',
            '<input type="checkbox" name="' + ID + '[]" id="redirect_' + ID + '">',
          '</th>',
          '<td><input type="text" name="' + ID + '[]" value=""></td>',
          '<td><input type="text" name="' + ID + '[]" value=""></td>',
          '<td>',
            '<select name="' + ID + '[]">',
              '<option value="disabled">Disabled</option>',
              '<option value="301">301</option>',
              '<option value="302">302</option>',
            '</select>',
          '</td>',
        '</tr>'
      ].join("\n");

      redirectsTable.append(tableRow);

    });
  }
  createRow();


});