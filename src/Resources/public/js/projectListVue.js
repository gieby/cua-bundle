
var stateInfo = {
    'lastId': null,
    'loadingState': 0 // 0: nichts wird geladen bzw. alles geladen; 1: Inhalte werden geladen
}

function lazyLoadThumbs() {
    var thumbs = document.getElementsByClassName('projectThumb');

    for (let i = 0; i < thumbs.length; i++) {
        var pic = thumbs.item(i).firstElementChild;
        pic.setAttribute('src',pic.getAttribute('data-src'));
        pic.removeAttribute('data-src');
    }
}

function handlePortfolioClick(sender) {
    //Funktion regelt, welche Subfunktionen aufgerufen werden, basierend auf Zustand
    // --> Element laden, Element schließen, anderes Element schließen & hier laden

    
    if (stateInfo.loadingState != 1) {
        if (sender.getAttribute('data-active') == 'true') { //das Element, welches wir gerade anklicken zeigt schon Detailinfos
            // schliessen und aktives Element löschen
            stateInfo.loadingState = 1;
            closeDetails();
            setTimeout(function() {
                sender.setAttribute('data-active','false');
            stateInfo.lastId = null;
            stateInfo.loadingState = 0;
            },400);
        } else {
            // ein anderes Element KÖNNTE aktiv sein, entsprechend vorgehen
            closeDetails();
            if(stateInfo.lastId != null) {
                $('.cua_project_entry[data-id="'+ stateInfo.lastId +'"]').first().attr('data-active','false');
                stateInfo.lastId = null;
            }
            stateInfo.loadingState = 1;
            sender.setAttribute('data-active','true');
            setTimeout(function() {
                displayDetails(sender.getAttribute('data-id'));
                stateInfo.lastId = sender.getAttribute('data-id');
                stateInfo.loadingState = 0;
            },400);
            
        }
    }
}

function closeDetails() {
    $('.cua_project_entry[data-id=' + stateInfo.lastId + ']').first().attr('data-active','false');
    $('#cua_project_detail > #detailClose').fadeOut(100);
    $('#cua_project_detail').slideUp(400,function() {
        $(this).remove();
    });
}

// hängt den Bereich an, welcher die Detailsinfos für das angeklickte Element halten soll
function prepareDetailArea(id,callback) {

    /**
     * Holt eine Liste möglicher Ankerpunkte für die Detailansicht.
     * Sollte diese Liste kein Element enthalten (warum auch immer...), wird das
     * Element genommen, welches auf das geklickte folgt.
     */
    var anchors = $(".cua_project_entry[data-id="+id+"].cua_project_clear, .cua_project_entry[data-id="+id+"]:last-of-type, .cua_project_entry[data-id="+id+"] ~ .cua_project_clear");
    if(anchors.length == 0) {
        anchors = $(".cua_project_entry[data-id="+id+"] + .cua_project_entry");
    }

    var last = anchors.first();

    if($('body').hasClass('mobile')) {
        last = $(".cua_project_entry[data-id='"+id+"']").first();
    }
    
    $(last).after("<div id='cua_project_detail' data-id='"+id+"'><div id='indicator'></div><div id='detailClose' onCLick='closeDetails()'></div><div class='inside'><div id='detailMedia'></div><div id='detailContent'></div></div></div>");
    callback();
}

// lädt die Details zu einem Projekt
function getDetails(id,callback) {
    $.getJSON("/ajax/project/" + id, success = function (data) {
            $('#cua_project_detail[data-id="'+id+'"] #detailContent').html(data['content']);
            $('#cua_project_detail[data-id="'+id+'"] #detailMedia').html(data['media']);
            $('#cua_project_detail[data-id="'+id+'"]').slideDown(400, function() {
                stateInfo.loadingState = 0;
            });
        }
    );
    callback();
}

// scrollt das Browserfenster an die entsprechende Stelle
function scrollTo(id,callback) {
    var offsetTop = Math.floor($('.cua_project_entry[data-id="'+id+'"]').first().offset()['top'] - 14);
    if($(window).innerWidth() <= 1024) {
        offsetTop -= 32;
    }
    $('html, body').animate({scrollTop: offsetTop}, 500, function() {
        callback();
    });
}

function positionIndicator(id,callback) {
    var offsetLeft= ($('.cua_project_entry[data-id="'+id+'"]').first().offset()['left'] - $('.cua_project_entry[data-id="'+id+'"]').first().offsetParent().offset()['left'])
                     + ($('.cua_project_entry[data-id="'+id+'"]').first().width()/2) - ($('#cua_project_detail #indicator').first().width()/2); 
    $('#cua_project_detail #indicator').css('left',offsetLeft+'px');
    callback();
}


function displayDetails(id) {
    prepareDetailArea(id, function() {
        positionIndicator(id, function() {
            getDetails(id,function() {
                scrollTo(id, function() {

                });
            });
        });
    });
}

document.addEventListener("DOMContentLoaded", function() {lazyLoadThumbs()});