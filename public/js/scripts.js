const addItem = (e) => {
    const collectionHolder = document.querySelector(e.currentTarget.dataset.collection);
    let index = parseInt(collectionHolder.dataset.index);
    const prototype = collectionHolder.dataset.prototype;
    console.log(collectionHolder.dataset);
    collectionHolder.innerHTML += prototype.replace(/__name__/g, index);
    collectionHolder.dataset.index = index + 1;
}

document.querySelectorAll('.btn-add').forEach(btn=> btn.addEventListener('click', addItem))