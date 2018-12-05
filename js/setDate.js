function setDate() {
    var date = new Date();
    var y = date.getFullYear();
    var m = date.getMonth();
    var d = date.getDate();
    document.getElementById("setDate").innerHTML = y+ "-" +m+ "-" +d;
} 