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
    if(ref == el.nextElementSibling.offsetTop) {
        result = el.nextElementSibling;
    } else {
        result = checkForSameRow(el.nextElementSibling,ref);
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
                console.log(' ===== NEUER CLICK =====');
                console.log(event.target.dataset.id);
                console.log(checkForSameRow(event.target));
                console.log(' =======================');
            }
        }
    });
}

var vm;

window.addEventListener("load", lazyLoadThumbs);
window.addEventListener("load",setupList);