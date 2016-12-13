
var vid = document.getElementsByClassName("video");
vid.loop = true;
vid.autoplay = true;
// vid.load();

function checkURL (item) {
    var string = item.value;
    if (!~string.indexOf("http")) {
        string = "http://" + string;
    }
    item.value = string;
    return item
}
