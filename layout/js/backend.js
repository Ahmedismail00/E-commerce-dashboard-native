$(document).ready(function(){
    "use strict";

    // hide placeholder on focus

    $('[placeholder]').focus(function(){

        $(this).attr('data-text',$(this).attr('placeholder')); // put what is in attr(placeholder) in attr(data-text)

        $(this).attr('placeholder','');

    }).blur(function(){
        $(this).attr('placeholder' , $(this).attr('data-text')); // put what is in attr(data-text) in attr(placeholder)
    });

    // Add Asterisk (*) on required fields
    
    // convert password field to text field (show password)
    var passwordField = $('.password');
    $('.show-pass').hover(function(){
        passwordField.attr('type','text');
    },function(){
        passwordField.attr('type','password');
    });

    // Confirmation message     
    $(".confirm").click(function(){
        return confirm('Are you sure ?');
    });

    // Categories view 
    $(".view").click(function(){
        $(this).next('.full-view').fadeToggle(100);
    })
    // dashboard view 
    $(".view").click(function(){
        $(this).parent().next('.full-view').fadeToggle(100);
    })

    
    
});