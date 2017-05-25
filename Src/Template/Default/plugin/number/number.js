/**
 * Created by hatmt on 27/12/2016.
 * Require jquery, jquery input mask
 */
$.fn.myNumber = function (fn, option) {
    if (!fn) {
        fn = 'numeric';
    }
    option = $.extend(option.frontEnd, {
        autoGroup: true,
        autoUnmask: true,
        unmaskAsNumber: true
    });

    var field = $(this);

    if (fn == 'currency') {
        option['prefix'] = '';
        option['suffix'] = '';
    }

    //if(fn == 'numeric'){
    //    field.focus(function () {
    //        $(this).select();
    //    });
    //}

    field.inputmask(fn, option);
    return field;
};

myNumber = {
    convertByFormat: function (value, fromFormat, toFormat) {
        var result = value + '';

        result = result
            .replace(' ', '')
            .replace(fromFormat.frontEnd['groupSeparator'], '')
            .replace(fromFormat.frontEnd['radixPoint'], '.');


        result = $.number(
            result,
            toFormat.frontEnd['digits'],
            toFormat.frontEnd['radixPoint'],
            toFormat.frontEnd['groupSeparator']
        );

        result = result + (toFormat.frontEnd['suffix'] ? toFormat.frontEnd['suffix'] : '');
        result = (toFormat.frontEnd['prefix'] ? toFormat.frontEnd['prefix'] : '') + result;

        return result;

    }
};