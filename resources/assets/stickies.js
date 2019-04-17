/*! Stickies - v0.0.1 - 2019-03-10
* http://pixelgods.net
*
* Extension for Laravel-Admin
*
* The notes are automatically saved to DB every 30 seconds on page change and
*
* Based on/using Post It All! - https://github.com/txusko/PostItAll
*
* Copyright Martin Daskalov; Licensed MIT */

// The following code detects URL changes and triggers
history.pushState = ( f => function pushState(){
    var ret = f.apply(this, arguments);
    window.dispatchEvent(new Event('pushState'));
    window.dispatchEvent(new Event('locationchange'));
    return ret;
})(history.pushState);

// history.replaceState = ( f => function replaceState(){
//     var ret = f.apply(this, arguments);
//     window.dispatchEvent(new Event('replaceState'));
//     window.dispatchEvent(new Event('locationchange'));
//     return ret;
// })(history.replaceState);

window.addEventListener('popstate',()=>{
    window.dispatchEvent(new Event('locationchange'))
});

window.addEventListener('locationchange', function(){
    locChange();
});

window.onbeforeunload = function(evt) {
    // Cancel the event (if necessary)
    evt.preventDefault();
    // Google Chrome requires returnValue to be set
    saveStickies();
    // evt.returnValue = '';
    return null;
};

/**
 *  Set some global configs then load all stickies and call locChange to handle the visibility.
 */
$(window).on('load', function() {
    doOnLoad();
    window.setInterval(function(){
        saveStickies();
    }, 30000);
});

function doOnLoad() {
    localStorage.clear();
    // Set some global vars
    $.PostItAll.changeConfig('global', {
        htmlEditor  : false, // For some reason the HTML editor is not working with me.
        addArrow    : 'all'
    });
    $.fn.postitall.globals.savable = true;
    // page = Domain will help us load all the stickies on the initial load so later we can work with them.
    $.fn.postitall.globals.filter = 'domain';
    loadAll();
}

function pseudo() {

}

/**
 * Handles the hide/show action based on the pathname from the URL so we can have stickies for different pages
 */
function locChange() {
    saveStickies();
    // Start the new page by hiding all stickies
    $.PostItAll.hide();

    // iterate local storage and decide which one to show based on the current pathname
    var path = window.location.pathname;
    for ( var i = 0, len = localStorage.length; i < len; ++i ) {
        var oKey = localStorage.key(i) ;
        var object = {}
        object[oKey] = JSON.parse(localStorage.getItem(localStorage.key(i)));
        var id = object[oKey].id;

        if (path === object[oKey].page) {
            $.PostItAll.show(id.toString());
        }
    }
}

/**
 * Load the stickies from DB and send them back to be loaded in localStorage.
 */
function loadAll() {

    // $.fn.postitall.defaults.page = window.location.pathname;
    // console.log(LA.token);
    // Send post request to StickiesController
    $.ajax({
        url: '/admin/stickies/getAll',
        type: 'POST',
        data: {
            // "stickies":  stickies,
        },
        // contentType: 'application/json; charset=UTF-8',
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': LA.token//$('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (!response.success) {
                // Handle if some of the inserts/updates fail
                handleError(response);
            } else {
                Object.values(response.data).forEach(sticky => {
                    //use value here
                    localStorage.setItem(sticky.name, sticky.sticky);
                });

                $.PostItAll.load(
                    '',
                    {
                        // onChange: function(id) {
                        //     console.log("cahange: " + id);
                        //     saveStickies();
                        // },
                        onDelete: function(id) {
                            console.log("delete: " + id);
                            deleteSticky(id);
                            //call delete action
                        },
                    }
                );

                locChange();


                // Success message
                // $(function () {
                //     toastr.success('Stickies retrieved successfully', null, []);
                // });
            }
        },
        error: handleError = function (response) {
            $(function () {
                var msg = '';
                for (var i = 0; i < response.error.length; i++) {
                    msg += response.error[i] + '<br>';
                }
                toastr.error('Error retrieving the following Stickies:<br>'+msg, null, []);
            });
        }
    });
}

/**
 * Create a new sticky.
 */
function createSticky() {
    // Make sure we refresh the pathname as the lib is not doing it for us.
    $.fn.postitall.defaults.page = window.location.pathname;
    // Create the sticky
    $.PostItAll.new(
        "<p style='text-align:center'>your note here</p>",
        {
            // onChange: function(id) {
            //     console.log("onChange: " + id);
            //     saveStickies();
            // },
            onDelete: function(id) {
                console.log("onDelete");
                deleteSticky(id);
            },
        }
    );
}

/**
 * Gather all the stickies from localStorage and sends them to StickiesController for insert/update in DB
 */
function saveStickies() {
    // Get all stickies from localStorage and save them in array
    var stickies = [];
    for ( var i = 0, len = localStorage.length; i < len; ++i ) {
        var oKey = localStorage.key(i) ;
        var object = {}
        object[oKey] = localStorage.getItem(localStorage.key(i));
        stickies.push(object);
    }

    // Send post request to StickiesController
    $.ajax({
        url: '/admin/stickies/saveAll',
        type: 'POST',
        data: {
            "stickies":  stickies
        },
        // contentType: 'application/json; charset=UTF-8',
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': LA.token//$('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (!response.success) {
                // Handle if some of the inserts/updates fail
                handleError(response);
            } else {
                $(function () {
                    // toastr.success('Stickies saved successfully', null, []);
                });
                // console.log(response);
            }
        },
        error: handleError = function (response) {
            $(function () {
                var msg = '';
                for (var i = 0; i < response.error.length; i++) {
                    msg += response.error[i] + '<br>';
                }
                // toastr.error('Error saving the following Stickies:<br>'+msg, null, []);
            });
        }
    });
}


/**
 * Delete a sticky
 */
function deleteSticky(id) {
    // Send post request to StickiesController
    $.ajax({
        url: '/admin/stickies/delete',
        type: 'POST',
        data: {
            "id":  id,
            "path" : window.location.pathname,
            "prefix" : $.fn.postitall.globals.prefix
        },
        // contentType: 'application/json; charset=UTF-8',
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': LA.token//$('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (!response.success) {
                // Handle if some of the inserts/updates fail
                handleError(response);
            } else {
                $(function () {
                    toastr.success('Stickies deleted successfully', null, []);
                });
            }
        },
        error: handleError = function (response) {
            $(function () {
                var msg = '';
                for (var i = 0; i < response.error.length; i++) {
                    msg += response.error[i] + '<br>';
                }
                toastr.error('Error deleting the following Stickies:<br>'+msg, null, []);
            });
        }
    });
}