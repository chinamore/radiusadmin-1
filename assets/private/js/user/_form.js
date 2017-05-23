$("#input-add-group").autocompleter({ 
    
    source: dir + "/json/groups/"
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
            url: dir + "/json/groups/exist?name=" + group,
            dataType: "json",
            success: function(data) {

                if( data.result ) {
                
                    $("#select-groups").append( "<option value='" + group + "'>" + group + "</option>" );
                }
            }
        });
      
    }

});

$("#btn-up-group").on( "click", function() { 

    $("#select-groups option").each( function( index, option ) {
    
        if( option.selected && index > 0 ) {
            
            var prevIndex = index - 1;

            $( option ).insertBefore( $("#select-groups option:eq(" + prevIndex + ")" ) );
        }
    });
});

$("#btn-down-group").on( "click", function() { 

    $("#select-groups option").each( function( index, option ) {
    
        if( option.selected && index < ( $("#select-groups option").length - 1 ) ) {
            
            var nextIndex = index + 1;

            $( option ).insertAfter( $("#select-groups option:eq(" + nextIndex + ")" ) );
        }
    });
});

$("#btn-remove-group").on( "click", function() { 

    $("#select-groups option:selected").remove();
});

