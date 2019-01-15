$(".select-btn").click(function(e){
    $(".post-dropdown").hide();
    $(".img-dropdown").hide();
    $(".select-dropdown").hide();
    $(".login-dropdown").hide();
    $(this).parent().find('ul').show("fast");
});
  
$(document).click(function(e) {
    if ($(e.target).closest('nav').length === 0) {     
        $(".post-dropdown").hide("fast");
        $(".img-dropdown").hide("fast");
        $(".select-dropdown").hide("fast");
        $(".login-dropdown").hide("fast");
    }
});

$(".displayAll").click(function(){
    $(".displayAll").toggleClass("hide");
    $(".hideAll").toggleClass("hide");
});

$(".hideAll").click(function(){
    $(".displayAll").toggleClass("hide");
    $(".hideAll").toggleClass("hide");
});

$(".changeTitleBtn").click(function(){
    $(".edit").toggleClass("hide");
    $(".changeTitleBtn").toggleClass("hide");
    $(".cancelBtn").toggleClass("hide");
});

$(".cancelBtn").click(function(){
    $(".edit").toggleClass("hide");
    $(".changeTitleBtn").toggleClass("hide");
    $(".cancelBtn").toggleClass("hide");
});

window.onload = function() {
    $(".message").addClass("animateOn");
}