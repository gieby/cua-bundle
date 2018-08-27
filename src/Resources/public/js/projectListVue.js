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
            projects : [],
            detail : {
                title : null,
                place : null,
                principal : null,
                status : null,
                task : null,
                year_comp : null,
                year_build : null,
                cost : null,
                url : null,
                media : null,
                description : null

            }
        },
        created: function () {
            httpGetAsync('https://www.codeunique.de/ajax/project/list', function(data) {
                vm.projects = JSON.parse(data);
            });
        },
        methods : {
            displayDetails : function(event) {
                var details = document.getElementById('details');
                httpGetAsync('https://www.codeunique.de/ajax/project/' + event.target.dataset.id, function (data) {
                    vm.detail = JSON.parse(data);
                    setTimeout(() => {
                        jQuery('.mod_rocksolid_slider').rstSlider();    
                    }, 10);
                });
                var reference = checkForSameRow(event.target);
                document.getElementById('projects').insertBefore(details, reference.nextElementSibling);
            }
        }
    });
}

var vm;

window.addEventListener("load", setupList);