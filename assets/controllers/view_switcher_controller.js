import {Controller} from '@hotwired/stimulus';

/*
 * This controller manage the form and show elements
 * div is composed by an show panel and a form panel (hidden)
 * this trigger hide show template and show form on button click
 */
export default class extends Controller {

    static targets = ['button', 'left', 'right']

    connect() {
        console.log('view switcher works !')

    }
    toggle(event) {
        event.preventDefault()
        this.leftTarget.parentNode.classList.toggle('float-left')
        this.leftTarget.parentNode.classList.toggle('float-right')
       /* console.log(event.currentTarget)
        this.showTarget.classList.toggle("-ml-[100%]")
        /!*this.showTarget.classList.toggle("opacity-0")
        this.showTarget.classList.toggle("max-w-0")*!/
        // this.showTarget.classList.toggle("-ml-1")
        this.formTarget.classList.toggle("opacity-0")
        this.formTarget.classList.toggle("max-h-0")
        if (this.buttonTarget.innerText === 'Update') this.buttonTarget.innerText = "Show"
        else this.buttonTarget.innerText = 'Update'*/

    }
}
