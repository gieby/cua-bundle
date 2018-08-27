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
    var result = el;

    if(ref != el.nextElementSibling.offsetTop 
        && ref != el.nextElementSibling.nextElementSibling.offsetTop 
        && ref != el.nextElementSibling.nextElementSibling.nextElementSibling.offsetTop
      ) {
        return result;
    } else if (el.nextElementSibling.classList.contains('entry') && ref == el.nextElementSibling.offsetTop) {
        result = checkForSameRow(el.nextElementSibling, ref);
    } else {
        result = checkForSameRow(el.nextElementSibling.nextElementSibling, ref);
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