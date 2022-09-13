
/*** Generator of new under form to add a trick's picture ***/

/*
$(document).ready(function() {
    if( $('[data-toggle="popover"]') != null){
        $('[data-toggle="popover"]').popover();
    }

});
 */
const addItem = (e) => {
    const collectionHolder = document
        .querySelector(e.currentTarget.dataset.collection);

    const item = document.createElement('div');
    item.classList.add('col-4');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(/__name__/g, collectionHolder.dataset.index);

    item.querySelector('.btn-remove').addEventListener('click', () => item.remove());

    collectionHolder.appendChild(item);
    collectionHolder.dataset.index++;

}

document.querySelectorAll('.btn-add').forEach(btn => btn.addEventListener('click', addItem));

document
    .querySelectorAll('.btn-remove')
    .forEach(
        btn => btn.addEventListener(
            'click',
            (e) => console.log(e.currentTarget
                .closest('.existing-item')
                .remove())
        )
    );
