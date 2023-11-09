import { Controller } from '@hotwired/stimulus';

/*
 * This Controller manage rows in interest form
 * Add and remove action
 * using form prototype from symfony
 *
 */
export default class extends Controller {

    static targets = [ 'button', 'remove', 'prototype' ]
    connect() {
        console.log('interest works !')

    }

    add(event){
        event.preventDefault()

        let prototype = this.prototypeTarget.dataset.prototype
        let index = this.prototypeTarget.dataset.index
        let newRow = prototype.replace(/__name__/g, index)
        let row = document.createElement('div')
        let wrapper = document.getElementsByClassName('js-interest-wrapper')[0]

        this.prototypeTarget.dataset.index++

        row.innerHTML = newRow
        console.log(wrapper)
        console.log(row)
        wrapper.insertBefore(row, this.buttonTarget)
    }
    remove(event){
        event.preventDefault()

        let target = event.target
        target.parentNode.remove()

        // this.prototypeTarget.dataset.index--

    }
}
