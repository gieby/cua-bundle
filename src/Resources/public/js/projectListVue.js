var getJSON = function(url, res, err) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    xhr.onload = function() {
      var status = xhr.status;
      if (status === 200) {
        res(xhr.response);
      } else {
        err(status);
      }
    };
    xhr.send();
};

function lazyLoadThumbs() {
    var thumbs = document.getElementsByClassName('projectThumb');

    for (let i = 0; i < thumbs.length; i++) {
        var pic = thumbs.item(i).firstElementChild;
        pic.setAttribute('src',pic.getAttribute('data-src'));
        pic.removeAttribute('data-src');
    }
}

window.addEventListener("load", function() {lazyLoadThumbs()});

function getProjects() {
    var projects = getJSON('https://www.codeunique.de/ajax/project/list',JSON.parse,console.log);

    console.log(projects);
}