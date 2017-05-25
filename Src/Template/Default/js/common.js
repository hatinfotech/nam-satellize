/**
 * Created by hatmt on 21/4/2017.
 */
$.fn.setSelectList = function (list) {
    var $this = this;
    $this.find('option:not(.label)').remove();
    $.each(list, function (index, item) {
        $this.append('<option value="' + item['Code'] + '">' + item['FullName'] + '</option>');
    });
    return $this;
};