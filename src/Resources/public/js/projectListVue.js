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
    var result, prev, next;

    // vorherigen & nächsten Eintrag(!!) finden - Detail-Ansicht ignorieren
    if(el.previousElementSibling && el.previousElementSibling.classList.contains('entry')) {
        prev = el.previousElementSibling;
    } else if(el.previousElementSibling && el.previousElementSibling.previousElementSibling) {
        prev = el.previousElementSibling.previousElementSibling;
    }

    if(el.nextElementSibling && el.nextElementSibling.classList.contains('entry')) {
        next = el.nextElementSibling;
    } else if(el.nextElementSibling && el.nextElementSibling.nextElementSibling) {
        next = el.nextElementSibling.nextElementSibling;
    }

    if(prev && ref < prev.offsetTop) {
        result = prev.previousElementSibling;
    } else if (next && ref == next.offsetTop) {
        result = checkForSameRow(next,ref);
    } else if (next && ref != next.offsetTop) {
        result = el
    } else {
        result = el;
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
                detail.loadDetails(event.target.dataset.id);
            }
        }
    });
}

var vm,detail;

function initDetail() {detail = new Vue({
    el: '#details',
    data : {
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
    },
    methods: {
        loadDetails : function(id) {
            httpGetAsync('https://www.codeunique.de/ajax/project/' + id, function (data) {
                vm.detail = JSON.parse(data);
                setTimeout(() => {
                    jQuery('.mod_rocksolid_slider').rstSlider();    
                }, 10);
            });
            var reference = checkForSameRow(document.querySelector('.entry[data-id="' + id + '"]'));
            document.getElementById('projects').insertBefore(details, reference.nextElementSibling);
        }
    }
}
)}

window.addEventListener("load", setupList);
window.addEventListener("load",initDetail);