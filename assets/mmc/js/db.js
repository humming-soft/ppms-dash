enableDays = [];
var result = [{date:"31-Jan-16"}];
var datelist = $("#date_list").empty();
var curr_data_date = "";
for (var i = 0; i < result.length; i++) {
    var date = result[i].date;
    enableDays.push(date);
     if (i == 0)
         datelist.append("<li><a href='"+baseURL+"dashboard?date="+date+"'>" + date + " (Latest)</a></li>");
     else
         datelist.append("<li><a href='"+baseURL+"dashboard?date="+date+"')>" + date + "</a></li>");

     //Update the current date field
     if (getParameterByName("date") === date) {
         $("#data_date").val(moment(getParameterByName("date"), "DD-MMM-YY").format("DD MMM YYYY").toUpperCase());
            curr_data_date = date;
      }
}
if (getParameterByName("date").length == 0) {
    console.log(result[0].date);
     $("#data_date").val(moment(result[0].date, "DD-MMM-YY").format("DD MMM YYYY").toUpperCase());
     curr_data_date = result[0].date;
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function enableAllTheseDays(date) {
    var sdate = $.datepicker.formatDate('dd-M-y', date)
    if ($.inArray(sdate, enableDays) != -1) {
        return [true];
    }
    return [false];
}
$(function () {
    $('#data_date').datepicker({
        dateFormat: "dd M yy",
        beforeShowDay: enableAllTheseDays,
        nextText: "",
        prevText: "",
        altField: '#data_date_selected',
        altFormat: "dd-M-y",
        onSelect: function (dateText, inst) {
            var selected = $('#data_date_selected').val();
            $("#date-display").text(selected);
            window.location.href = baseURL +'dashboard?date=' + selected;
        }
    });

    $('#date_selector').on('click', function () {
        $('#data_date').datepicker('show');
    })

});