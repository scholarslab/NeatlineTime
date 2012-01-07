jQuery(document).ready(function() {
  var datefields = jQuery('#element-40 textarea');

  datefields.each(function(index, item) {
    var $item = jQuery(item);
    mDate = moment($item.text(), "YYYY-MM-DD");
    console.log(mDate);
  });
});
