require('../css/app.css');

const $ = require('jquery');
window.Popper = require('popper.js').default;
window.Holder = require('holderjs');

import 'bootstrap';

$(document).ready(function(){

    $(".refresh-quantity").on('click',function(){
        var quantity = $(this).parents("tr").find("input[name='quantity']").val();
        var produit_id = $(this).parents("tr").find("input[type='hidden']").val();

        $.ajax({
            type: 'POST',
            url: "/panier/modifier",
            data: {
                quantity: quantity,
                produit_id: produit_id
            },
            timeout: 3000,
            success: function(data){
                location.reload();
            },
            error: function(){
                $('#zone').html('Cette requête AJAX n\'a pas abouti');
            }
        });
    });
});
