jQuery(document).ready(function($){


  var redirectsTable, tableRow, addRow, ID;


  redirectsTable = $('.gj-redirects > tbody:last');
  addRow = $('.addRow');


  function createRow() {
    addRow.click(function() {

      ID = $('.gj-redirects > tbody:last').children('tr').last().data('id') + 1;

      tableRow = [
        '<tr id="redirect-' + ID + '" class="alternate redirect" data-id="' + ID + '">',
          '<input type="hidden" name="' + ID + '[id]" value="' + ID + '">',
          '<input type="hidden" name="' + ID + '[mode]" value="create">',
          '<th class="check-column">',
            '<input type="checkbox" name="' + ID + '[delete]" id="redirect_' + ID + '">',
          '</th>',
          '<td><input type="text" name="' + ID + '[url]" value=""></td>',
          '<td><input type="text" name="' + ID + '[redirect]" value=""></td>',
          '<td>',
            '<select name="' + ID + '[status]">',
              '<option value="disabled">Disabled</option>',
              '<option value="301">301</option>',
              '<option value="302">302</option>',
            '</select>',
          '</td>',
          '<td>',
            '<select name="' + ID + '[scope]">',
              '<option value="exact">Exact</option>',
              '<option value="plusquery">Exact + Query</option>',
              '<option value="plusfragment">Exact + Fragment</option>',
              '<option value="any">Any</option>',
            '</select>',
          '</td>',
        '</tr>'
      ].join("\n");

      redirectsTable.append(tableRow);

    });
  }
  createRow();

  $('.detect-change').change(function() {
    console.log('CHANGE!');
    $(this).parents('tr').children('.mode').val('update');
  });


});
