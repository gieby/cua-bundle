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

function checkForSameRow(el,ref) {  
    var ref = ref || el.offsetTop;
    var result = el;

    if(ref != el.nextElementSibling.offsetTop && ref != el.nextElementSibling.nextElementSibling.offsetTop) {
        return result;
    } else {
        result = checkForSameRow(el.nextElementSibling, ref);
    }

    return result;
}

function setupList() {
    vm = new Vue({
        el : '#projects',
        data : {
            projects : []
        },
        created: function () {
            httpGetAsync('https://www.codeunique.de/ajax/project/list', function(data) {
                vm.projects = JSON.parse(data);
            })
        },
        methods : {
            displayDetails : function(event) {
                var details = document.getElementById('details');
                var reference = checkForSameRow(event.target);
                document.getElementById('projects').insertBefore(details, reference);
            }
        }
    });
}

var vm;

window.addEventListener("load", lazyLoadThumbs);
window.addEventListener("load", setupList);