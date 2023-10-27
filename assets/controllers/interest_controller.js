import { Controller } from '@hotwired/stimulus';

/*
 *
 *
 *
 *
 *
 *
 */
export default class extends Controller {

    static targets = [ 'button', 'prototype' ]
    connect() {
        console.log('interest works !')

    }

    add(event){
        console.log("click")
        console.log(prototypeTarget.dataset.prototype)
        event.preventDefault()
    }
}
