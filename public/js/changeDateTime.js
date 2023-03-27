const dates = document.querySelectorAll('.date');
const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;

$( document ).ready(function() {
    dates.forEach(date => {
        if(date.innerHTML){
            date.innerHTML = convertDateTime(date.innerHTML);
        }
    });
});

function convertDateTime(datetime)
{
    return moment.tz(datetime, tz).format('LLL Z');
}
