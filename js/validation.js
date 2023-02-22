$(function () {
    $('.error').hide()
    $("#login-btn").click(function () {
        let checked = true

        $('.error').hide()
        var username = $("input#username").val()
        if (username === "") {
            $("label#username_missing_error").show()
            $("input#username").focus()
            checked = false
        }

        var password = $("input#password").val()
        if (password === "") {
            $("label#password_missing_error").show()
            $("input#password").focus()
            checked = false
        } 

        if (checked) {
            // alert("First name: " + username + "\n" + "Password: " + password + "\n" )
            location.href = '../index.html'
            
            return true
        }
        return false
    })
})