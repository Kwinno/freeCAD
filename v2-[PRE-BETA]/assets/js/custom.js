function pleaseWaitMsg() {
    toastr.warning('Please wait...')
}
function notReadyMsg() {
    toastr.warning('Opps! This feature is not ready for public use yet! Check back later.')
}
function update10codeID(id) {
    var i = id.id;
    alert(i);
}
function changeSignal() {
    $.ajax({
        url: "inc/backend/user/dispatch/signal100.php",
        cache: false,
        success: function(result) {
            toastr.info('Please wait...', 'System:', {
                timeOut: 10000
            })
        }
    });
}

$(document).ready(function () {
    // document.oncontextmenu = document.body.oncontextmenu = function () { return false; }
    // toastr.warning("This is not meant for Production use. Please report any bugs to Tyler#7918", {
    //   timeOut: 10000
    // })
    $('#staffPanelWizard').bootstrapWizard({'tabClass': 'nav nav-tabs navtab-wizard nav-justified'});
    $('#editingUserPanelWizard').bootstrapWizard({'tabClass': 'nav nav-tabs navtab-wizard nav-justified'});
});

function disableClick() {
    $("input[type='submit']").attr("disabled", false);

    $("form").submit(function(){
        $("input[type='submit']").attr("disabled", true);
        setTimeout(function(){ $("input[type='submit']").attr("disabled", false); }, 5000);
        return true;
    })

    $("button[type='submit']").attr("disabled", false);
    $("form").submit(function(){
        $("button[type='submit']").attr("disabled", true);
        setTimeout(function(){ $("button[type='submit']").attr("disabled", false); }, 5000);
        return true;
    })
}

$( function() {
  var availableTags = [
    "Great Ocean Highway",
    "Senora Freeway",
    "Interstate 1 (Los Santos Freeway)",
    "Interstate 2 (Del Perro Freeway)",
    "Interstate 4 (Olympic Freeway)",
    "Interstate 5 (La Puerta Freeway)",
    "U.S. Route 1 (Great Ocean Highway)",
    "U.S. Route 11 (Tongva Drive)",
    "U.S. Route 13 (Senora Freeway)",
    "U.S. Route 15 (Palomino Freeway)",
    "U.S. Route 20 (Elysian Fields Freeway)",
    "U.S. Route 68",
    "San Andreas State Route 14 (North Rockford Drive)",
    "San Andreas State Route 16 (Mad Wayne Thunder Drive)",
    "San Andreas State Route 17",
    "San Andreas State Route 18",
    "San Andreas State Route 19",
    "San Andreas State Route 22",
    "Abattoir Ave",
    "Abe Milton Pkwy",
    "Adam's Apple Blvd",
    "Aguja St",
    "Alta St",
    "Amarillo Vista",
    "Amarillo Way",
    "Americano Way",
    "Arsenal St",
    "Atlee St",
    "Autopia Pkwy",
    "Bait St",
    "Banham Canyon Dr",
    "Barbareno Rd",
    "Bay City Incline",
    "Baytree Canyon Rd",
    "Boulevard Del Perro",
    "Bridge St",
    "Brouge Ave",
    "Buccaneer Way",
    "Buen Vino Rd",
    "Caesar Place",
    "Calais Ave",
    "Capital Blvd",
    "Carcer Way",
    "Carson Ave",
    "Chum St",
    "Chupacabra St",
    "Clinton Ave",
    "Cockingend Dr",
    "Conquistador St",
    "Cortes St",
    "Cougar Ave",
    "Covenant Ave",
    "Cox Way",
    "Crusade Rd",
    "Davis Ave",
    "Decker St",
    "Dorset Dr",
    "Dorset Pl",
    "Dry Dock St",
    "Dunstable Dr",
    "Dunstable Ln",
    "Dutch London St",
    "East Galileo Ave",
    "East Mirror Dr",
    "Eastbourne Way",
    "Eclipse Blvd",
    "Edwood Way",
    "El Burro Blvd",
    "El Rancho Blvd",
    "Elgin Ave",
    "Equality Way",
    "Exceptionalists Way",
    "Fantastic Pl",
    "Fenwell Pl",
    "Forced Labor Pl",
    "Forum Dr",
    "Fudge Ln",
    "Galileo Rd",
    "Ginger St",
    "Glory Way",
    "Goma St",
    "Greenwich Pkwy",
    "Greenwich Way",
    "Grove St",
    "Hanger Way",
    "Hardy Way",
    "Hawick Ave",
    "Heritage Way",
    "Hillcrest Ave",
    "Hillcrest Ridge Access Rd",
    "Imagination Court",
    "Industry Passage",
    "Innocence Blvd",
    "Inseno Rd",
    "Invention Court",
    "Jamestown St",
    "Kimble Hill Dr",
    "Kortz Dr",
    "Labor Pl",
    "Lake Vinewood Dr",
    "Las Lagunas Blvd",
    "Las Lagunas Pl",
    "Liberty St",
    "Lindsay Circus",
    "Little Bighorn Ave",
    "Macdonald St",
    "Mad Wayne Thunder Dr",
    "Magellan Ave",
    "Marathon Ave",
    "Marlowe Dr",
    "Melanoma St",
    "Meteor St",
    "Milton Rd",
    "Mirror Park Blvd",
    "Mirror Pl",
    "Morningwood Blvd",
    "Mount Haan Dr",
    "Mount Haan Rd",
    "Mount Vinewood Dr",
    "Movie Star Way",
    "Mutiny Rd",
    "Nikola Ave",
    "Nikola Pl",
    "Normandy Dr",
    "North Archer Ave",
    "North Conker Ave",
    "North Rockford Dr",
    "Occupation Ave",
    "Orchardville Ave",
    "Palomino Ave",
    "Peaceful St",
    "Perth St",
    "Picture Perfect Dr",
    "Plaice Pl",
    "Playa Vista",
    "Popular St",
    "Portola Dr",
    "Power St",
    "Prosperity St",
    "Prosperity Street Promenade",
    "Red Desert Ave",
    "Richman St",
    "Rockford Dr",
    "Roy Lowenstein Blvd",
    "Rub St",
    "Sam Austin Dr",
    "San Andreas Ave",
    "San Vitus Blvd",
    "Sandcastle Way",
    "Senora Rd",
    "Senora Way",
    "Shank St",
    "Signal St",
    "Sinner St",
    "South Boulevard Del Perro",
    "South Mo Milton Dr",
    "South Rockford Dr",
    "South Shambles St",
    "Spanish Ave",
    "Steele Way",
    "Strangeways Dr",
    "Strawberry Ave",
    "Supply St",
    "Sustancia Rd",
    "Swiss St",
    "Tackle St",
    "Tangerine St",
    "Tongva Dr",
    "Tower Way",
    "Tug St",
    "Utopia Gardens",
    "Vespucci Blvd",
    "Vinewood Blvd",
    "Vinewood Park Dr",
    "Vitus St",
    "Voodoo Pl",
    "West Eclipse Blvd",
    "West Mirror Dr",
    "Whispymound Dr",
    "Wild Oats Dr",
    "York St",
    "Zancudo Barranca",
    "Zancudo Rd",
    "Del Perro Freeway",
    "Elysian Fields Freeway",
    "La Puerta Freeway",
    "Los Santos Freeway",
    "Olympic Freeway",
    "Palomino Freeway",
    "Algonquin Blvd",
    "Alhambra Dr",
    "Armadillo Ave",
    "Calafia Rd",
    "Casabel Ave",
    "Cassidy Trail",
    "Cat-Claw Ave",
    "Chianski Passage",
    "Cholla Rd",
    "Cholla Springs Ave",
    "Duluoz Ave",
    "East Joshua Rd",
    "El Gordo Dr",
    "Fort Zancudo Approach Rd",
    "Grapeseed Ave",
    "Grapeseed Main St",
    "Joad Ln",
    "Joshua Rd",
    "Lesbos Ln",
    "Lolita Ave",
    "Marina Dr",
    "Meringue Ln",
    "Mountain View Dr",
    "Niland Ave",
    "North Calafia Way",
    "Nowhere Rd",
    "O'Neil Way",
    "Paleto Blvd",
    "Panorama Dr",
    "Procopio Dr",
    "Procopio Promenade",
    "Pyrite Ave",
    "Raton Pass",
    "Route 68",
    "Seaview Rd",
    "Smoke Tree Rd",
    "Union Rd",
    "Zancudo Ave",
    "Zancudo Trail",
    "24/7 (Innocence Boulevard)",
    "24/7 (Clinton Avenue)",
    "24/7 (Barbareno Road)",
    "24/7 (Ineseno Road)",
    "24/7 (Tataviam Truckstop)",
    "24/7 (Route 68)",
    "Earl's Mini-Mart & Gas Station (Senora Freeway)",
    "24/7 (Niland Avenue and Alhambra Drive)",
    "Globe Oil Gas Station (Senora Freeway)",
    "24/7 (Vinewood Boulevard)",
    "24/7 (Hawick Avenue)",
    "24/7 (Vinewood Plaza)",
    "24/7 (Elgin Avenue)",
    "24/7 (Korean Plaza)",
    "24/7 (Vespucci Boulevard)",
    "RON Gas Station (Route 68)",
    "Pacific Standard Public Deposit Bank (Alta Street and Vinewood Boulevard)",
    "Maze Bank Tower (Pillbox Hill)",
    "Fleeca Bank (Vespucci Boulevard)",
    "Fleeca Bank (Boulevard Del Perro)",
    "Fleeca Bank (Hawick Avenue / Meteor Street)",
    "Fleeca Bank (Hawick Avenue)",
    "Fleeca Bank (Vespucci Mall)",
    "Fleeca Bank (Great Ocean Highway)",
    "Fleeca Bank (Route 68)",
    "Blaine County Savings Bank (Cascabel Avenue and Paleto Boulevard)",
    "Blaine County Savings Bank (Route 1)",
    "Central Los Santos Medical Center",
    "Mount Zonah Medical Center",
    "Pillbox Hill Medical Center",
    "Sandy Shores Medical Center",
    "St. Fiacre Hospital",
    "Eclipse Medical Tower",
    "Portola Trinity Medical Center",
    "Ammu-Nation (Adam's Apple Boulevard)",
    "Ammu-Nation (Lindsay Circus)",
    "Ammu-Nation (Popular Street)",
    "Ammu-Nation (Tataviam Truckstop)",
    "Ammu-Nation (Paleto Bay)",
    "Ammu-Nation (Algonquin Boulevard)",
    "Ammu-Nation (Boulevard Del Perro)",
    "Ammu-Nation (Vinewood Plaza)",
    "Ammu-Nation (325 Vespucci Boulevard)",
    "Ammu-Nation (Chumash Plaza)"

  ];
  $( "#street_ac" ).autocomplete({
    minLength: 5,
    source: availableTags
  });
  $( "#street_ac2" ).autocomplete({
    minLength: 5,
    source: availableTags
  });
});

function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }
    else
    {
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
        end = dc.length;
        }
    }
    // because unescape has been deprecated, replaced with decodeURI
    //return unescape(dc.substring(begin + prefix.length, end));
    return decodeURI(dc.substring(begin + prefix.length, end));
}
