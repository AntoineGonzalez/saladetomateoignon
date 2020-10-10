$(document).ready(function() {
    let $form = $(".add-comment-form")
    let $toggleBtn = $(".toggle-form")

    $toggleBtn.on("click", function(e) {
        e.preventDefault()
        if($form.is(":visible")) {
            $form.hide()
        } else {
            $form.show()
        }
    })
})
