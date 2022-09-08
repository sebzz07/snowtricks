/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';


// start the Stimulus application
import './bootstrap';


const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

/*** Generator of new under form to add a trick's picture ***/
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

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

/*** ReadMore feature on the main page ***/
function getTricks(){
    let user;

    if (document.getElementById("user") != null) {
        user = true;
    } else {
        user = false;
    }

    const ajaxQuery = new XMLHttpRequest();

    let url = "/nextTricks/"+offset;
    ajaxQuery.open("GET", url);
    offset += 5;
    ajaxQuery.onload = function(){
        const result = JSON.parse(ajaxQuery.responseText);
        const html = result.map(function(trick) {
            let content =`
      <div class="col mb-5">
        <div class="card h-100">
            <!-- New badge-->
            <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">
                New
            </div>
            <!-- Trick image-->
            <img class="card-img-top" src="https://picsum.photos/seed/picsum/450/300" alt="..."/>
            <!-- Trick details-->
            <div class="card-body p-4">
                <div class="text-center">
                    <!-- Trick name-->
                    <h5 class="fw-bolder">{{ trick.name }}</h5>
                    <p><a href="#" class="btn btn-info">#tag</a></p>
                    <p>Created at {{ trick.createdAt|date("m/d/Y") }} </p><br>
                    <p> Updated at {{ trick.modifiedAt|date("m/d/Y") }}</p>
                    <p>{{ trick.posts|length }} comment(s) </p><br>
                </div>
            </div>
            <!-- Trick actions-->
            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="{{ path('app_trick', { 'slug': trick.slug }) }}">See this trick...</a></div>
            </div>
        </div>
    </div>`;
            if (user) {
                content += `
      <a class="btn btn-primary mt-auto mx-1" href="{{ path('app_trick_edit', { 'slug': trick.slug }) }}">Edit</a>
        {% if trick.publicationStatusTrick == 'Published' %}
            <a class="btn btn-primary mt-auto mx-1" href="{{ path('app_trick_status', { 'slug': trick.slug,'publicationStatus': 'Unpublished' }) }}">Unpublish</a>
        {% else %}
            <a class="btn btn-primary mt-auto mx-1" href="{{ path('app_trick_status', { 'slug': trick.slug,'publicationStatus': 'Published' }) }}">publish</a>
        {% endif %}`;
            }
            content += `
            </div>
          </div>
        </div>
        </div>`;

            return content;
        }).join('');

        const tricks = document.querySelector('.tricks');

        tricks.innerHTML += html;
        //messages.scrollTop = messages.scrollHeight;
    }
    ajaxQuery.send();
}
let offset = 5;
//getTricks();
document.getElementById("more").addEventListener('click', function(e) {
    e.preventDefault();
    getTricks();
});