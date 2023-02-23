$(function () {
    $('.error').hide()
    $("#login-btn").click(function () {
        let checked = true
        let loginBox = document.getElementById('form-box');

        $('.error').hide()
        var username = $("input#username").val()
        if (username === "") {
            $("label#username_missing_error").show()
            $("input#username").focus()
            checked = false
        } else if (!/^[A-Za-z][A-Za-z0-9]*$/.test(username)) {
            loginBox.setAttribute('height', 390)
            $("label#username_format_error").show()
            $("input#username").focus()
            checked = false
        }

        var password = $("input#password").val()
        if (password === "") {
            $("label#password_missing_error").show()
            $("input#password").focus()
            checked = false
        } else if (!/^[A-Za-z][A-Za-z0-9!*\S]{6,14}\d$/.test(password)) {
            $("label#password_format_error").show()
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