function applyRemoveAttribute() {

    $( ".btn-remove-attribute" ).on( "click", function() { 
        var colum = $( this ).parent();
        var line = colum.parent();

        line.remove();
    });
}

applyRemoveAttribute();

$( "#btn-add-check" ).on( "click", function() {
   
    var inputAttribute = $("#attribute-check-new").clone();
    var selectOperator = $("#operator-check-new").clone();
    var inputValue = $("#value-check-new").clone();
    var buttonRemove = $("#btn-remove-check-new").clone();

    inputAttribute.attr( "id", "" );
    selectOperator.attr( "id", "" );
    inputValue.attr( "id", "" );
    buttonRemove.attr( "id", "" );

    inputAttribute.attr( "name", "atributo-verificacao[]" );
    selectOperator.attr( "name", "operador-verificacao[]" );
    inputValue.attr( "name", "valor-verificacao[]" );

    var attribute = $( "<td></td>" ).append( inputAttribute );
    var operator = $( "<td></td>" ).append( selectOperator );
    var value = $( "<td></td>" ).append( inputValue );
    var remove = $( "<td></td>" ).append( buttonRemove );

    var attributeLine = $( "<tr></tr>").append( attribute )
        .append( operator )
        .append( value )
        .append( remove );

    $("#attributes-check").prepend( attributeLine );

    applyRemoveAttribute();
})

$( "#btn-add-reply" ).on( "click", function() {
   
    var inputAttribute = $("#attribute-reply-new").clone();
    var selectOperator = $("#operator-reply-new").clone();
    var inputValue = $("#value-reply-new").clone();
    var buttonRemove = $("#btn-remove-reply-new").clone();

    inputAttribute.attr( "id", "" );
    selectOperator.attr( "id", "" );
    inputValue.attr( "id", "" );
    buttonRemove.attr( "id", "" );

    inputAttribute.attr( "name", "atributo-resposta[]" );
    selectOperator.attr( "name", "operador-reposta[]" );
    inputValue.attr( "name", "valor-reposta[]" );

    var attribute = $( "<td></td>" ).append( inputAttribute );
    var operator = $( "<td></td>" ).append( selectOperator );
    var value = $( "<td></td>" ).append( inputValue );
    var remove = $( "<td></td>" ).append( buttonRemove );

    var attributeLine = $( "<tr></tr>").append( attribute )
        .append( operator )
        .append( value )
        .append( remove );

    $("#attributes-reply").prepend( attributeLine );

    applyRemoveAttribute();
})

