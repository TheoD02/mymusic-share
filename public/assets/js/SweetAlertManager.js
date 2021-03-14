class SweetAlertManager
{
    /**
     * Afficher une alerte avec un temps d'affichage (default 2000ms)
     * @param title     Titre de l'alerte
     * @param message   Details du message
     * @param duration  Temps d'affichage en millisecondes
     * @param type
     * @param showConfirmButton
     * @param position
     */
    showTimerAlert(title, message, duration = 2000, type = 'success', showConfirmButton = true, position = 'center')
    {
        let timerInterval;
        Swal.fire({
            position         : position,
            title            : title,
            icon             : type,
            html             : message,
            timer            : duration,
            showConfirmButton: showConfirmButton,
            timerProgressBar : true,
            didOpen          : (toast) =>
            {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
        });
    }

    /**
     * Affiche une alerte qui nécessite une action de l'utilisateur
     *
     * @param title                 Titre de l'alerte
     * @param message               Message de l'alerte
     * @param type                  Type d'alerte [e.g success, warning, error]
     * @param confirmButton         Activer le bouton de confirmation
     * @param confirmButtonText     Texte du bouton de confirmation
     * @param cancelButton          Activer le bouton d'annulation
     * @param cancelButtonText      Texte du bouton d'annulation
     *
     * @return true|false           Selon si l'utilisateur interagie avec le bouton confirmer ou annuler
     */
    showSimpleAlert(title, message, type, confirmButton = true, confirmButtonText = 'Valider', cancelButton = true, cancelButtonText = 'Annuler')
    {
        Swal.fire({
            title            : title,
            text             : message,
            icon             : type,
            showCancelButton : cancelButton,
            showConfirmButton: confirmButton,
            cancelButtonText : cancelButtonText,
            confirmButtonText: confirmButtonText,
        }).then((result) =>
        {
            return result.isConfirmed;
        });
    }

    /**
     * Afficher une message toast
     *
     * @param title             Titre du message toast
     * @param position          Position du toast [e.g 'top', 'top-start', 'top-end', 'center', 'center-start', 'center-end', 'bottom', 'bottom-start', or 'bottom-end'.]
     * @param type              Type du toast [e.g success, warning, error]
     * @param showConfirmButton Afficher le bouton de confirmation
     * @param duration          Temps d'affichage du toast
     */
    showToast(title, position = 'center', type = 'success', showConfirmButton = true, duration = 3000)
    {
        const Toast = Swal.mixin({
            toast            : true,
            position         : position,
            showConfirmButton: showConfirmButton,
            timer            : duration,
            timerProgressBar : true,
            didOpen          : (toast) =>
            {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
        });
        Toast.fire({
            icon : type,
            title: '<p class="p-3">' + title + '</p>',
        });
        Toast.stopTimer();
    }
}