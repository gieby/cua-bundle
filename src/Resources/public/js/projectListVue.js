function httpGetAsync(yourUrl,callback){
    var req = new XMLHttpRequest();
    req.open("GET",yourUrl,true);
    req.onload = function e() {
        if(req.status === 200) {
            callback(req.responseText);
        }
    }
    req.send(null);         
}

function checkForSameRow(el,ref) {  
    var ref = ref || el.offsetTop;
    var result;

    // vorherigen & nächsten Eintrag(!!) finden - Detail-Ansicht ignorieren
    var prev = (el.previousElementSibling.classList.contains('entry')) ? el.previousElementSibling : el.previousElementSibling.previousElementSibling;
    var next = (el.nextElementSibling.classList.contains('entry')) ? el.nextElementSibling : el.nextElementSibling.nextElementSibling;

    if(ref < prev.offsetTop) {
        result = prev.previousElementSibling;
    } else if (ref == next.offsetTop) {
        result = checkForSameRow(next,ref);
    } else if (ref != next.offsetTop) {
        result = el
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
            });
        },
        methods : {
            displayDetails : function(event) {
                var details = document.getElementById('details');
                var reference = checkForSameRow(event.target);
                document.getElementById('projects').insertBefore(details, reference.nextElementSibling);
            }
        }
    });
}

var vm;

window.addEventListener("load", setupList);

/**
 * <li id="details">
        <div id="detail-slider">
            {{ detail.slider }}
        </div>
        <div id="detail-content">
            <div id="project-title">{{ detail.title }}</div>
            <div id="project-place">{{ detail.place }}</div>
            <div id="project-desc">{{ detail.description }}</div>
        </div>
    </li>
 */