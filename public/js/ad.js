$('#add-image').click(function () {

    // Récupérer le n° des futurs champs
    const index = +$('widget-count').val();
    // Récupérer le prototype des entrées
    const tmpl = $('#announce_images').data('prototype').replace(/__name__/g, index);
    // Injecter le code dans la div
    $('#announce_images').append(tmpl);
    // On ajoute 1 à la valeur initiale de la collection
    $('#widget-count').val(index + 1);

    deleteButtons();
});
function updateCounter() {
    const count = $('#announce_images div.form-group').length;
    // On met à jour la valeur de widget-counter

    $('#widget-count').val(count);
}
function deleteButtons() {
    $('button[data-action = "delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();
    });
}
updateCounter();
deleteButtons();