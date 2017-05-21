
$("#input-add-group").autocompleter({ 
    
    source: dir + "/json/grupos/",
    minChars: 3,
});

$("#btn-remove-group").on( "click", function() { 

    $("#select-groups option:selected").remove();
});

$("#btn-add-group").on( "click", function() { 

    var group = $("#input-add-group").val();
    
    $("#input-add-group").val("");

    if( $.trim( group ).length <= 0 ) {
        
        return;
    }

    var groupsSelect = $.map( $("#select-groups option") ,function(option) {
        return option.value;
    });

    if( $.inArray( group, groupsSelect ) === -1 ) {
         
        $.ajax({
            url: dir + "/json/grupos/existe?nome=" + group,
            dataType: "json",
            success: function(data) {

                if( data.result ) {
                
                    $("#select-groups").append( "<option value='" + group + "'>" + group + "</option>" );
                }
            }
        });
      
    }

});
