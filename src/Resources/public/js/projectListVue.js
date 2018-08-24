function httpGetAsync(yourUrl,callback){
    var req = new XMLHttpRequest(); // a new request
    req.open("GET",yourUrl,true);
    req.onload = function e() {
        if(req.status === 200) {
            callback(req.responseText);
        }
    }
    req.send(null);         
}

function handleJSON(data) {
    return JSON.parse(data);
}

function lazyLoadThumbs() {
    var thumbs = document.getElementsByClassName('projectThumb');

    for (let i = 0; i < thumbs.length; i++) {
        var pic = thumbs.item(i).firstElementChild;
        pic.setAttribute('src',pic.getAttribute('data-src'));
        pic.removeAttribute('data-src');
    }
}

var projects = new Vue({
    el : '#projects',
    data : {
        projects : []
    },
    created: function () {
        httpGetAsync('https://www.codeunique.de/ajax/project/list', function(data) {
            projects.data.projects = JSON.parse(data);
        })
    }
});

window.addEventListener("load", function() {lazyLoadThumbs()});