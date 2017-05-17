
$(".search-groups").autocompleter({ 
    
    source: dir + "/grupos/json",
    callback: function (value, index) {
                console.log('Value ' + value + ' are selected (with index ' + index + ').');
                        }

});
