import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello.html.twig" attribute will cause
 * this controller to be executed. The name "hello.html.twig" comes from the filename:
 * hello_controller.js -> "hello.html.twig"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {
        this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
    }
}
