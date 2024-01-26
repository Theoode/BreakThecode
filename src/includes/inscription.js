$(document).ready(function () {
    $("#form-inscription").submit(function (event) {
        event.preventDefault();

        var email = $("input[name='email']").val();
        var pseudo = $("input[name='pseudo']").val();
        var date_naissance = $("input[name='date_naissance']").val();
        var password = $("input[name='password']").val();
        var confirmerMotDePasse = $("input[name='confirmationMotDePasse']").val();

        // Envoyer la requête AJAX
        $.ajax({
            type: "POST",
            url: "../inscription.php",
            data: {
                email: email,
                pseudo: pseudo,
                date_naissance: date_naissance,
                password: password,
                confirmationMotDePasse: confirmerMotDePasse,
                inscription: "true"
            },
            success: function (response) {
                $("#inscriptionSuccess").html(response);
            }
        });
    });
});