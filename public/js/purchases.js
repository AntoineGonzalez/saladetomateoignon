$(document).ready(function() {
    $(".retractable").each(function () {
        $(this).on("click" , function(){
            if($(this).next().is(":visible")){
                $(this).next().hide();
            }else{
                $(this).next().show();
            }
        });

        console.log($(this).next());
    });
});