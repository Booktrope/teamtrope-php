jQuery(document).ready(function($){
    
    
    $( ".bprn_field_rule" ).each(function( index ) {
        var block = $(this);
        var input = block.find('input');
        var list_items = block.find('.bprn_list_fields li');
        
        //enable clicking fields items
        list_items.click(function() {
            var code = $(this).find('code').html();
            input.val(input.val()+' '+code);
            $(this).addClass('selected');
            //bprn_rule_update(block);
        });
        
        //focus out
        input.change(function() {
            //bprn_rule_update(block);
        });
        
        
        
    });
    
    /*
    
    function bprn_rule_update(block){
        
        
        //TO FIX make regex work
        //has anyone any idea ?
        
        var input = block.find('input');
        var regex = new RegExp("(field-\\d+)", "g");
        
        var split_rule = input.val().split(regex);

    }
    */

});



