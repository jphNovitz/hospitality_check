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

    static targets = [ 'button', 'remove', 'prototype' ]
    connect() {
        console.log('interest works !')

    }

    add(event){
        event.preventDefault()
        console.log("click")
        let prototype = this.prototypeTarget.dataset.prototype
        let index = this.prototypeTarget.dataset.index
        let newForm = prototype.replace(/__name__/g, index)
        let row = document.createElement('span')
        this.prototypeTarget.dataset.index++
        row.innerHTML = newForm
        let wrapper = document.getElementsByClassName('js-interest-wrapper')[0]
        wrapper.insertBefore(row, this.buttonTarget)
        // wrapper.innerHTML += newForm
        console.log(wrapper)

    }
    remove(event){
        event.preventDefault()
        console.log("click remove")
        let target = event.target
        // console.log(this.removeTarget.parentNode)
        let prototype = this.prototypeTarget.dataset.prototype
        let index = this.prototypeTarget.dataset.index
        this.prototypeTarget.dataset.index--
        let wrapper = document.getElementsByClassName('js-interest-wrapper')[0]
        // wrapper.remove(this.removeTarget)
        target.parentNode.remove()

    }
}
