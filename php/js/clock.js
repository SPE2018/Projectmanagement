// clock
function Clock(){
    var time = moment().format("HH:mm:ss");
    var date = moment().format("DD.MM.YYYY");
    $('#clock').html(time);
    $('#date').html(date);
}
setInterval(Clock, 1000);