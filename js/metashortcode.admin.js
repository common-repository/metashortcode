jQuery(function() {
    //@see https://stackoverflow.com/a/2880929
    var qsParams;
    (window.onpopstate = function () {
        var match,
            pl     = /\+/g,  // Regex for replacing addition symbol with a space
            search = /([^&=]+)=?([^&]*)/g,
            decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
            query  = window.location.search.substring(1);

        qsParams = {};
        while (match = search.exec(query)) {
           qsParams[decode(match[1])] = decode(match[2]);
       }
    })();
    if (qsParams['page'] === 'metashortcode_settings') {
        var metashortcode_validate = function(){
            if (!jQuery('#metashortcode_enabled_1').prop('checked')) {
                if (confirm("The MetaShortcode module is currently disabled. Are you sure this is what you want?") === true) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        };
        jQuery('form').submit(function(e){
            if( !metashortcode_validate() ){
              e.preventDefault(); 
              return; 
            }
        });
    }
});